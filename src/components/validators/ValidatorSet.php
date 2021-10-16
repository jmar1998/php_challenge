<?php
namespace ASPTest\components\validators;
class ValidatorSet
{
    private $errors = [];
    /**
     * @var BaseValidator[]
     */
    private $validators = [];
    private $config = [];

    public function __construct(array $validators, array $config = [])
    {
        foreach ($validators as $validator) {
            $this->setValidator($validator);
        }
        $this->config = $config;
    }
    public function setValidator(BaseValidator $validator){
        $this->validators[] = $validator;
    }
    public function validate(){
        foreach ($this->validators as $validator) {
            if ($validator->skip || (!empty($this->config['skipOnUndefined']) && is_null($validator->value))) {
                continue;
            }
            $this->errors = array_merge($this->errors, $validator->validate());
        }
        return $this->errors;
    }
    public function getErrors(){
        return $this->errors;
    }
}
