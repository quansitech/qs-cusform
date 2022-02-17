<?php
namespace CusForm\Schema\Validator;

use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Validation;

class Enum extends BaseValidator{


    public function validate(): bool
    {
        if(qsEmpty($this->component->value)){
            return true;
        }

        $source = collect($this->component->enum)->map(function($value){
            return $value->value;
        })->all();

        $validator = Validation::createValidator();
        $violations  = $validator->validate($this->component->value, [new Choice($source)]);
        if (0 !== count($violations)) {
            return false;
        }
        else{
            return true;
        }

    }

    public function errorMsg(): string
    {
        return "{$this->component->title}选择值不正确";
    }
}