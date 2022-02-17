<?php
namespace CusForm\Schema\Validator;

class Required extends BaseValidator{


    public function validate(): bool
    {
        return !qsEmpty($this->component->value);
    }

    public function errorMsg(): string
    {
        return "{$this->component->title}必填";
    }
}