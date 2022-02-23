<?php
namespace CusForm\Schema\Components;

use Illuminate\Support\Str;

class Text extends BaseComponent {

    public function __construct(string $title = ''){
        $sign = Str::random(11);

        parent::__construct($sign);

        $this->x_component = 'Text';
        $this->type = 'string';
        $this->title = $title;
    }

    public function content(string $content) : Text
    {
        $this->x_component_props->content = $content;
        return $this;
    }
}