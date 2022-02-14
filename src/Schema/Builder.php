<?php
namespace CusForm\Schema;

use CusForm\Helper;
use CusForm\Schema\Components\BaseComponent;

class Builder{
    protected $json_schema;
    protected $before_components = [];
    protected $after_components = [];

    public function __construct(Object $json_schema){
        $this->json_schema = $json_schema;
    }

    public function addBefore(BaseComponent $component){
        array_push($this->before_components, $component);
        return $this;
    }

    public function addAfter(BaseComponent $component){
        array_push($this->after_components, $component);
        return $this;
    }

    protected function buildBeforeComponents(){
        $built = [];
        foreach($this->before_components as $component){
            list($sign, $component) = $component->build();
            $built[$sign] = $component;
        }

        return $built;
    }

    protected function buildAfterComponents(){
        $built = [];
        foreach($this->after_components as $component){
            list($sign, $component) = $component->build();
            $built[$sign] = $component;
        }

        return $built;
    }

    protected function mergeAndReindex(){
        $first = $this->buildBeforeComponents();
        $mid = (array)$this->json_schema->schema->properties;
        $last = $this->buildAfterComponents();

        $all = $first+$mid+$last;

        $index = 0;
        foreach($all as $sign => &$component){
            Helper::wrapComponentIllegalProp($component, 'x-index', $index);
            $index++;
        }

        $this->json_schema->schema->properties = (object)$all;
    }

    public function build(){
        $this->mergeAndReindex();
        return $this->json_schema;
    }
}