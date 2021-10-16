<?php
namespace ASPTest\components\validators;
class EmailValidator extends StringValidator{
    public function validate() : array
    {
        parent::validate();
        if (filter_var($this->value, FILTER_VALIDATE_EMAIL) === false) {
            $this->addError("The '{$this->value}' should be a valid email.");
        }
        return $this->errors;
    }
}