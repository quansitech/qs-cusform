<?php
namespace CusForm\Schema\Components;

use CusForm\Helper;
use CusForm\Schema\Validator\ValidatorFactory;
use Illuminate\Support\Str;
use stdClass;

abstract class BaseComponent{

    public $sign;
    public $type;
    public $title;
    public $x_decorator;
    public $x_component;
    public $x_component_props;
    public $enum;
    public $required;
    public $description;
    public $default;
    public $x_validator;
    public $x_pattern;
    public $validator;
    public $value;

    public function __construct(string $sign){
        $this->sign = $sign;
        $this->x_component_props = new stdClass();
        $this->x_decorator = 'FormItem';
    }

    public function value($value) : BaseComponent{
        $this->value = $value;
        return $this;
    }

    public function type(string $type) : BaseComponent{
        $this->type = $type;
        return $this;
    }

    public function title($title) : BaseComponent{
        $this->title = $title;
        return $this;
    }

    public function required(bool $required = true) : BaseComponent{
        if($required){
            $this->required = $required;
            $this->validator[] = 'required';
        }
        return $this;
    }

    public function description(string $description): BaseComponent{
        $this->description = $description;
        return $this;
    }

    public function default($default) : BaseComponent{
        $this->default = $default;
        return $this;
    }

    public function validator(string $validator) : BaseComponent{
        if(!qsEmpty($validator)){
            $this->x_validator = $validator;
            $this->validator[] = $validator;
        }
        return $this;
    }

    public function pattern(string $pattern) : BaseComponent{
        $this->x_pattern = $pattern;
        return $this;
    }

    public function enum(?array $enum): BaseComponent{
        if(!qsEmpty($enum)){
            $this->enum = $enum;
            $this->validator[] = 'enum';
        }
        return $this;
    }

    public function validate() : array{
        foreach($this->validator as $validator_name){
            if(preg_match('/^\{\{([\s\S]*?)\}\}$/', $validator_name)){
                continue;
            }
            $validator = ValidatorFactory::make($validator_name, $this);
            $r = $validator->validate();
            if($r === false){
                return [false, $validator->errorMsg()];
            }
        }

        return [true, ''];
    }

    public function readonly(): BaseComponent{
        $this->pattern('disabled');
        return $this;
    }


    public function build(){
        $sign = !qsEmpty($this->sign) ? $this->sign: Str::random(11);

        $component = new stdClass();
        $component->type = $this->type;
        $component->title = $this->title;
        $this->description && $component->description = $this->description;
        Helper::wrapComponentIllegalProp($component, 'x-decorator', $this->x_decorator);
        Helper::wrapComponentIllegalProp($component, 'x-component', $this->x_component);
        $this->x_validator && Helper::wrapComponentIllegalProp($component, 'x-validator', $this->x_validator);
        $this->x_pattern && Helper::wrapComponentIllegalProp($component, 'x-pattern', $this->x_pattern);
        Helper::wrapComponentIllegalProp($component, 'x-component-props', $this->x_component_props);
        Helper::wrapComponentIllegalProp($component, 'x-designable-id', $sign);
        !qsEmpty($this->default) && $component->default = $this->default;
        $this->required && $component->required = $this->required;
        count((array)$this->enum) > 0 && $component->enum = $this->enum;
        return [$sign, $component];
    }
}