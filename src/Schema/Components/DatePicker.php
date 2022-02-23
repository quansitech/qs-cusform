<?php
namespace CusForm\Schema\Components;

class DatePicker extends BaseComponent {

    const YEAR = 'year';
    const TIME = 'time';
    const DATE = 'date';
    const MONTH = 'month';
    const QUARTER = 'quarter';

    public function __construct(string $sign, string $title){
        parent::__construct($sign);

        $this->x_component = 'DatePicker';
        $this->type = 'string';
        $this->title = $title;
    }

    public function placeholder(string $placeholder) : DatePicker{
        $this->x_component_props->placeholder = $placeholder;
        return $this;
    }

    public function allowClear(bool $allowClear){
        $this->x_component_props->allowClear = $allowClear;
        return $this;
    }

    public function picker(string $mode) : DatePicker
    {
        $objClass = new \ReflectionClass(__CLASS__);
        $arrConst = $objClass->getConstants();
        $arrConst = array_flip($arrConst);
        if(!$arrConst[$mode]){
            $mode = self::DATE;
        }

        $this->x_component_props->picker = $mode;
        return $this;
    }

    public function showTime(bool $show) : DatePicker
    {
        $this->x_component_props->showTime = $show;
        return $this;
    }

}