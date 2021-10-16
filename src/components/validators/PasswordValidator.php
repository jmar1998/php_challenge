<?php
namespace ASPTest\components\validators;
class PasswordValidator extends StringValidator
{
    public function validate() : array
    {
        parent::validate();
        if (!empty($this->config['specialCharacters']) && !$this->hasSpecialCharacters()) {
            $this->addError("The {$this->label} should have at least 1 special character.");
        }
        if (!empty($this->config['upperCase']) && !$this->hasUpperCaseCharacters()) {
            $this->addError("The {$this->label} should have at least 1 uppercase character.");
        }
        if (!empty($this->config['numbers']) && !$this->hasNumericCharacters()) {
            $this->addError("The {$this->label} should have at least 1 number.");
        }
        return $this->errors;
    }
    private function hasSpecialCharacters(){
        return preg_match('/[^A-Za-z0-9]/m', $this->value) === 1;
    }
    private function hasUpperCaseCharacters(){
        return preg_match('/[A-Z]/m', $this->value) === 1;
    }
    private function hasNumericCharacters(){
        return preg_match('/[0-9]/m', $this->value) === 1;
    }
}
