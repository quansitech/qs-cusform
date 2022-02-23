<?php
namespace CusForm;

use Qscmf\Lib\DBCont;
use stdClass;

class Helper{

    static public function iJson(){
        if(strpos($_SERVER['HTTP_CONTENT_TYPE'], 'application/json') === false){
            return;
        }
        $input=file_get_contents('php://input');
        return json_decode($input);
    }

    static public function responseJson($data) : void{
        header('Content-Type:application/json; charset=utf-8');
        qs_exit($data);
    }

    static public function wrapComponentIllegalProp(stdClass &$object, string $prop, $value) : void{
        $object->$prop = $value;
    }

    static public function getComponentIllegalProp(stdClass $object, string $prop){
        return $object->$prop;
    }

    static public function getNidBy(string $module = MODULE_NAME,string $controller = CONTROLLER_NAME, string $action = 'index') : int{
        $module_ent = D('Node')->where(['name' => $module, 'level' => DBCont::LEVEL_MODULE, 'status' => DBCont::NORMAL_STATUS])->find();

        $controller_ent = D('Node')->where(['name' => $controller, 'level' => DBCont::LEVEL_CONTROLLER, 'status' => DBCont::NORMAL_STATUS, 'pid' => $module_ent['id']])->find();

        $action_ent = D('Node')->where(['name' => $action, 'level' => DBCont::LEVEL_ACTION, 'status' => DBCont::NORMAL_STATUS, 'pid' => $controller_ent['id']])->find();

        return $action_ent['id'];
    }
}
