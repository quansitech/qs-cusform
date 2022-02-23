<?php
namespace CusForm\Schema\Validator;

use Symfony\Component\Validator\Constraints\EmailValidator;
use Symfony\Component\Validator\Constraints\RegexValidator;
use Symfony\Component\Validator\Validation;

class Number extends BaseValidator{

    public function validate(): bool
    {
        if(qsEmpty($this->component->value)){
           return true;
        }

        if(preg_match('/^[+-]?\d+(\.\d+)?$/', $this->component->value, $matches)){
            return true;
        }
        else{
            return false;
        }
    }

    public function errorMsg(): string
    {
        return "{$this->component->title}必须是数字";
    }
}