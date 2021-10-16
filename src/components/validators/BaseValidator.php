<?php
namespace ASPTest\components\validators;

use Exception;
namespace ASPTest\components\validators;
abstract class BaseValidator{
    protected $label = null;
    public $value = null;
    protected $config = [];
    protected $errors = [];
    public $skip = false;
    public function __construct($label, $value, $config = [])
    {
        if (empty($label)) {
            throw new Exception("The label cannot be empty");
        }
        $this->value = $value;
        $this->label = $label;
        $this->config = $config;
        if (isset($this->config['required']) && !$this->config['required'] && $this->value == '') {
            $this->skip = true;
        }
        if (isset($this->config['required']) && $this->config['required'] && $this->value == '') {
            $this->addError("The $label must have a value");
        }
    }
    abstract function validate() : array;
    public function addError($message){
        $this->errors[] = $message;
    }
}