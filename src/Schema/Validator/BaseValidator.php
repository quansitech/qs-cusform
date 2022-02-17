<?php
namespace CusForm\Schema\Validator;

use CusForm\Schema\Components\BaseComponent;

abstract class BaseValidator{

    protected $component;

    public function __construct(BaseComponent $component){
        $this->component = $component;
    }

    abstract public function validate() : bool;

    abstract public function errorMsg() : string;
}