<?php
namespace CusForm\Schema\Components;

use CusForm\Helper;

class ComponentFactory{

    static function make(string $sign, object $properties) : BaseComponent{

        $common = new CommonComponent($sign);
        $common->title($properties->title)
            ->validator(self::getValidator($properties))
            ->required(!!$properties->required)
            ->enum($properties->enum);
        return $common;
    }

    static protected function getValidator(object $properties): string{
        $validator = Helper::getComponentIllegalProp($properties, 'x-validator');
        if(qsEmpty($validator)){
            return '';
        }

        if(is_array($validator)){
            $validator = $validator[0];
        }

        return $validator;
    }
}