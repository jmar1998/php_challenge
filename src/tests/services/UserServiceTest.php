<?php

namespace ASPTest\test\components;

use ASPTest\services\UserService;
use PHPUnit\Framework\TestCase;

final class UserServiceTest extends TestCase
{
    /** @var UserService */
    private $userService = null;
    protected function setUp(): void
    {
        $this->userService = new UserService();
        parent::setUp();
    }
    /**
     * @dataProvider names
     * @runInSeparateProcess
     * @return void
     */
    public function testValidateFirstName($name, $expected)
    {
        $this->assertEquals($expected, empty($this->userService->validateAttibutes([
            'firstName' => $name
        ], ['skipOnUndefined' => !is_null($name)])));
    }
    /**
     * @dataProvider names
     * @return void
     */
    public function testValidateLastName($name, $expected)
    {
        $this->assertEquals($expected, empty($this->userService->validateAttibutes([
            'lastName' => $name
        ], ['skipOnUndefined' => !is_null($name)])));
    }
    /**
     * @dataProvider emails
     * @return void
     */
    public function testValidateEmail($email, $expected)
    {
        $this->assertEquals($expected, empty($this->userService->validateAttibutes([
            'email' => $email
        ], ['skipOnUndefined' => !is_null($email)])));
    }
    /**
     * @dataProvider ages
     * @return void
     */
    public function testValidateAge($age, $expected)
    {
        $this->assertEquals($expected, empty($this->userService->validateAttibutes([
            'age' => $age
        ], ['skipOnUndefined' => !is_null($age)])));
    }
    /**
     * @dataProvider passwords
     * @return void
     */
    public function testValidatePassword($password, $expected)
    {
        $this->assertEquals($expected, empty($this->userService->validatePassword($password, $password)));
    }
    public function testValidateDifferentPasswords()
    {
        $this->assertEquals(true, !empty($this->userService->validatePassword("Abcdef*1", "Abcdef*2")));
    }
    public function testValidateSamePasswords()
    {
        $this->assertEquals(true, empty($this->userService->validatePassword("Abcdef*1", "Abcdef*1")));
    }
    public function names(): array
    {
        return [
            "Less than 2 characters" => ["J", false],
            "More than 36 characters" => [str_repeat("J", 36), false],
            "Empty name" => [null, false],
            "Valid Name" => ['Jose', true]
        ];
    }
    public function emails(): array
    {
        return [
            "Invalid email without domain and extension" => ["test", false],
            "Invalid email without extension" => ["test@gmail", false],
            "Valid Email" => ["test@gmail.com", true],
            "Empty Email" => [null, false],
        ];
    }
    public function ages(): array
    {
        return [
            "Negative age" => [-1, false],
            "More than 150" => [151, false],
            "Valid Age" => [23, true],
            "Empty Age" => [null, false],
        ];
    }
    public function passwords(): array
    {
        return [
            "Less than 6 characters" => ["ABCDE", false],
            "Without special characters" => ["Abcde1", false],
            "Without uppercase characters" => ["abcdef1*", false],
            "Without numbers" => ["Abcdef*", false],
            "Perfect password" => ["Abcdef*1", true],
        ];
    }
}
