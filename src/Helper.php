<?php
namespace CusForm;

class Helper{

    static public function iJson(){
        if(strpos($_SERVER['HTTP_CONTENT_TYPE'], 'application/json') === false){
            return;
        }
        $input=file_get_contents('php://input');
        return json_decode($input);
    }

    static public function responseJson($data){
        header('Content-Type:application/json; charset=utf-8');
        qs_exit($data);
    }

    static public function wrapComponentIllegalProp(&$object, $prop, $value){
        $object->$prop = $value;
    }
}
