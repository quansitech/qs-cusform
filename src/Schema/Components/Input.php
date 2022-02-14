<?php
namespace CusForm\Schema\Components;

class Input extends BaseComponent {

    public function __construct(string $sign, string $title){
        parent::__construct($sign);

        $this->x_component = 'Input';
        $this->type = 'string';
        $this->title = $title;
    }

    public function placeholder($placeholder){
        $this->x_component_props->placeholder = $placeholder;
        return $this;
    }

    public function allowClear($allowClear){
        $this->x_component_props->allowClear = $allowClear;
        return $this;
    }

    public function maxLength($maxLength){
        $this->x_component_props->maxLength = $maxLength;
        return $this;
    }

}