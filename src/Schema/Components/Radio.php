<?php
namespace CusForm\Schema\Components;

class Radio extends BaseComponent {

    public function __construct(string $sign, string $title){
        parent::__construct($sign);

        $this->x_component = 'Radio.Group';
        $this->type = 'string | number';
        $this->title = $title;
    }
}