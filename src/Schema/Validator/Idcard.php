<?php
namespace CusForm\Schema\Validator;

use Symfony\Component\Validator\Constraints\EmailValidator;
use Symfony\Component\Validator\Constraints\RegexValidator;
use Symfony\Component\Validator\Validation;

class Idcard extends BaseValidator{

    public function validate(): bool
    {
        if(qsEmpty($this->component->value)){
           return true;
        }

        if(preg_match('/^\d{15}$|^\d{17}(\d|x|X)$/', $this->component->value, $matches)){
            return true;
        }
        else{
            return false;
        }
    }

    public function errorMsg(): string
    {
        return "{$this->component->title}不是有效的身份证格式";
    }
}