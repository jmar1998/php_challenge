<?php
namespace ASPTest\components\validators;
class StringValidator extends BaseValidator{
    protected $config = [
        'min' => null,
        'max' => null
    ];
    public function validate() : array
    {
        if (!is_string($this->value)) {
           $this->addError("The {$this->label} should be an string");
        }
        if ((!empty($this->config['max']) && strlen($this->value) > $this->config['max'])) {
            $this->addError("The {$this->label} cant be greater than {$this->config['max']} characters");
        }
        if ((!empty($this->config['min']) && strlen($this->value) < $this->config['min'])) {
            $this->addError("The {$this->label} should have at least {$this->config['min']} characters");
        }
        return $this->errors;
    }
}