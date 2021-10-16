<?php

namespace ASPTest\services;

use ASPTest\components\database\DbComponent;
use ASPTest\components\validators\{EmailValidator, NumericValidator, PasswordValidator, StringValidator, UniqueValidator, ValidatorSet};
use PDO;

class UserService
{
    /** @var DbComponent */
    private $dbComponent = null;
    public function __construct()
    {
        $this->dbComponent = new DbComponent();
    }
    /**
     * @param string $string
     * @param string $salt
     * @return string
     */
    private function generatePassword(string $string, string $salt): string
    {
        return password_hash($salt . $string, PASSWORD_BCRYPT, [
            'cost' => 10,
        ]);
    }
    /**
     * @return string
     */
    private function generateRandomString(): string
    {
        return hash('crc32', uniqid());
    }
    /**
     * @param mixed $id
     * @return mixed
     */
    public function exists($id)
    {
        $query = $this
            ->dbComponent
            ->PDO
            ->prepare("SELECT * FROM `user` WHERE `id` = :id");
        $query->execute([':id' => $id]);
        if ($query->fetch() === false) {
            return ["The user requested dont exists"];
        }
        return [];
    }
    /**
     * @param array $attributes
     * @return array|false
     */
    public function create(array $attributes)
    {
        $salt = $this->generateRandomString();
        $result = $this->dbComponent->PDO
            ->prepare(<<<SQL
                INSERT INTO 
                `user`(`first_name`, `email`, `last_name`, `age`, `password`, `salt`) 
                VALUES(:firstName, :email, :lastName, :age, :password, :salt)
            SQL)
            ->execute([
                ':firstName' => $attributes['firstName'],
                ':lastName' => $attributes['lastName'],
                ':age' => $attributes['age'],
                ':email' => $attributes['email'],
                ':salt' => $salt,
                ':password' => $this->generatePassword($this->generateRandomString(), $salt)
            ]);
        if ($result) {
            return $this
                ->dbComponent->PDO
                ->query(<<<SQL
                    SELECT 
                    `id`,
                    `first_name`,
                    `last_name`,
                    `age`,
                    `email`,
                    '***********' as password FROM `user` WHERE `id` = LAST_INSERT_ID()
                SQL)
                ->fetch(PDO::FETCH_ASSOC);
        }
        return false;
    }
    /**
     * @param mixed $id
     * @param array $attributes
     * @return bool
     */
    public function update($id, array $attributes): bool
    {
        $salt = $this->generateRandomString();
        $updateQuery = "UPDATE `user` SET ";
        $columnsQuery = [];
        $params = [];
        $attributes['salt'] = $salt;
        foreach ($attributes as $column => $value) {
            $columnsQuery[] .= "`$column` = :$column";
            $params[":$column"] = $column == 'password' ? $this->generatePassword($value, $salt) : $value;
        }
        $updateQuery .= implode(',', $columnsQuery) . " WHERE `id` = :id;";
        $params[':id'] = $id;
        return $this->dbComponent->PDO
            ->prepare($updateQuery)
            ->execute($params) === true;
    }
    /**
     * @param string $password
     * @param string|null $passwordConfirmation
     * @return void
     */
    public function validatePassword(string $password, ?string $passwordConfirmation = null)
    {
        if (!empty($passwordConfirmation) && $password !== $passwordConfirmation) return ["The passwords are not equal"];
        return (new PasswordValidator("Password", $password, [
            'min' => 6,
            'specialCharacters' => true,
            'upperCase' => true,
            'numbers' => true
        ]))->validate();
    }
    /**
     * @param array $attributes
     * @param array $config
     * @return array
     */
    public function validateAttibutes(array $attributes, $config = []): array
    {
        return (new ValidatorSet([
            new StringValidator("First Name", ($attributes['firstName'] ?? null), [
                'required' => true,
                'min' => 2,
                'max' => 35
            ]),
            new StringValidator("Last Name", ($attributes['lastName'] ?? null), [
                'required' => true,
                'min' => 2,
                'max' => 35,
            ]),
            new EmailValidator("Email", ($attributes['email'] ?? null), [
                'max' => 255
            ]),
            new NumericValidator("Age", ($attributes['age'] ?? null), [
                'required' => false,
                'max' => 150,
                'min' => 0,
            ]),
            new UniqueValidator("Email", ($attributes['email'] ?? null), [
                'dbComponent' => $this->dbComponent,
                'table' => 'user',
                'attribute' => 'email'
            ])
        ], $config))->validate();
    }
}
