<?php
namespace CusForm\Schema\Components;

use CusForm\Helper;
use Illuminate\Support\Str;
use stdClass;

abstract class BaseComponent{

    protected $sign;
    protected $type;
    protected $title;
    protected $x_decorator;
    protected $x_component;
    protected $x_component_props;
    protected $enum;
    protected $required;
    protected $description;
    protected $default;
    protected $x_validator;
    protected $x_pattern;

    public function __construct(string $sign){
        $this->sign = $sign;
        $this->x_component_props = new stdClass();
        $this->x_decorator = 'FormItem';
    }

    public function type($type){
        $this->type = $type;
        return $this;
    }

    public function title($title){
        $this->title = $title;
        return $this;
    }

    public function required($required){
        $this->required = $required;
        return $this;
    }

    public function description($description){
        $this->description = $description;
        return $this;
    }

    public function default($default){
        $this->default = $default;
        return $this;
    }

    public function validator($validator){
        $this->x_validator = $validator;
        return $this;
    }

    public function pattern($pattern){
        $this->x_pattern = $pattern;
        return $this;
    }


    public function build(){
        $sign = !qsEmpty($this->sign) ? $this->sign: Str::random(11);

        $component = new stdClass();
        $component->type = $this->type;
        $component->title = $this->title;
        $this->description && $component->description;
        Helper::wrapComponentIllegalProp($component, 'x-decorator', $this->x_decorator);
        Helper::wrapComponentIllegalProp($component, 'x-component', $this->x_component);
        $this->x_validator && Helper::wrapComponentIllegalProp($component, 'x-validator', $this->x_validator);
        Helper::wrapComponentIllegalProp($component, 'x-component-props', $this->x_component_props);
        Helper::wrapComponentIllegalProp($component, 'x-designable-id', $sign);
        !qsEmpty($this->default) && $component->default = $this->default;
        $this->required && $component->required = $this->required;
        count($this->enum) > 0 && $component->enum = $this->enum;
        return [$sign, $component];
    }
}