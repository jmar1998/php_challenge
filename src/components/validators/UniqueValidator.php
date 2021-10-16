<?php
namespace ASPTest\components\validators;

use ASPTest\components\database\DbComponent;
use ASPTest\components\validators\BaseValidator;
use Exception;

class UniqueValidator extends BaseValidator
{
    /** @var DbComponent */
    private $dbComponent = null;
    private $table = null;
    private $attribute = null;
    public function __construct($label, $value, $config = [])
    {
        if (empty($config['dbComponent']) || !($config['dbComponent'] instanceof DbComponent)) {
            throw new Exception("You must set a valid instance from dbComponent");
        }
        $this->dbComponent = $config['dbComponent'];
        if (!isset($config['table'], $config['attribute'])) {
            throw new Exception("You must set the table and attribute");
        }
        $this->table = $config['table'];
        $this->attribute = $config['attribute'];
        parent::__construct($label, $value, $config);
    }
    public function validate(): array
    {
        $query = $this
            ->dbComponent
            ->PDO
            ->prepare("SELECT * FROM `{$this->table}` WHERE `{$this->attribute}` = :email");
        $query->execute([':email' => $this->value]);
        if ($query->fetch() !== false) {
            $this->addError("The {$this->label} : '{$this->value}' provided already exists");
        }
        return $this->errors;
    }
}
