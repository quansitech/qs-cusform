<?php
namespace CusForm\Schema\Validator;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Url as UrlValidator;

class Url extends BaseValidator{


    public function validate(): bool
    {
        if(qsEmpty($this->component->value)){
           return true;
        }

        $validator = Validation::createValidator();
        $violations  = $validator->validate($this->component->value, [new UrlValidator()]);
        if (0 !== count($violations)) {
            return false;
        }
        else{
            return true;
        }

    }

    public function errorMsg(): string
    {
        return "{$this->component->title}不是有效的Url";
    }
}