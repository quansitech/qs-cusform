<?php
namespace CusForm\Schema\Validator;

use Symfony\Component\Validator\Constraints\EmailValidator;
use Symfony\Component\Validator\Constraints\RegexValidator;
use Symfony\Component\Validator\Validation;

class Phone extends BaseValidator{

    public function validate(): bool
    {
        if(qsEmpty($this->component->value)){
           return true;
        }

        if(preg_match('/^\d{3}-\d{8}$|^\d{4}-\d{7}$|^\d{11}$/', $this->component->value, $matches)){
            return true;
        }
        else{
            return false;
        }
    }

    public function errorMsg(): string
    {
        return "{$this->component->title}必须是手机号码";
    }
}