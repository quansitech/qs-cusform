<?php
namespace CusForm\Schema\Components;

class Textarea extends BaseComponent {

    public function __construct(string $sign, string $title){
        parent::__construct($sign);

        $this->x_component = 'Input.TextArea';
        $this->type = 'string';
        $this->title = $title;
    }

    public function placeholder(string $placeholder) : TextArea{
        $this->x_component_props->placeholder = $placeholder;
        return $this;
    }

    public function maxLength(int $maxLength) : TextArea{
        $this->x_component_props->maxLength = $maxLength;
        return $this;
    }

    public function showCount(bool $showCount) : TextArea{
        $this->x_component_props->showCount = $showCount;
        return $this;
    }
}