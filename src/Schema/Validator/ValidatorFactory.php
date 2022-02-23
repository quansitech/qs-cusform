<?php
namespace CusForm\Schema\Validator;

use CusForm\Schema\Components\BaseComponent;
use Think\Exception;

class ValidatorFactory{

    static public function make(string $validator, BaseComponent $component) : BaseValidator{
        $validator = ucfirst($validator);
        if(!class_exists("\CusForm\Schema\Validator\\{$validator}")){
            throw new Exception("\CusForm\Schema\Validator\\{$validator} 不存在");
        }

        $cls = "\CusForm\Schema\Validator\\{$validator}";
        return new $cls($component);
    }
}