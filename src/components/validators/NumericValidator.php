<?php
namespace ASPTest\components\validators;
class NumericValidator extends BaseValidator{
    public function validate() : array
    {
        if (!is_numeric($this->value)) {
           $this->addError("The {$this->label} should be a number");
        }
        if (isset($this->config['max']) && $this->value > $this->config['max']) {
            $this->addError("The {$this->label} cant be greater than {$this->config['max']}");
        }
        if (isset($this->config['min']) && $this->value < $this->config['min']) {
            $this->addError("The {$this->label} cant be less than {$this->config['min']}");
        }
        return $this->errors;
    }
}