<?php
namespace CusForm;

use CusForm\Schema\Components\ComponentFactory;
use Qscmf\Lib\DBCont;
use stdClass;
use Think\Exception;

class CusForm
{
    private static $_instance;

    private function __construct()
    {
    }

    public static function getInstance() : CusForm{
        if (!self::$_instance){
            self::$_instance=new CusForm();
        }
        return self::$_instance;
    }

    public function formSchema(int $form_id): ?string{
        return D("Form")->where(['id' => $form_id, 'deleted' => DBCont::NO_BOOL_STATUS])->getField('json_schema');
    }

    public function getApplySchema(int $apply_id, string $mode = 'edit') : stdClass{
        $ent = D("FormApply")->where(['id' => $apply_id])->find();
        $form_id = $ent['form_id'];
        $json_content = $ent['json_content'];
        if(!$json_content){
            throw new Exception("找不到填写的表单内容");
        }

        $json_schema = $this->formSchema($form_id);
        if (!$json_schema) {
            throw new Exception("表单不存在");
        }

        $schema = json_decode($json_schema);

        $content = json_decode($json_content);
        foreach($content as $sign => $value){
            if(isset($schema->schema->properties->$sign)){
                $schema->schema->properties->$sign->default = $value;
            }

            if($mode === 'readonly'){
                Helper::wrapComponentIllegalProp($schema->schema->properties->$sign, 'x-pattern', 'disabled');
            }
        }

        return $schema;
    }

    public function submitApply(int $form_id, stdClass $post_object) : array
    {
        $json_schema = $this->formSchema($form_id);
        if (!$json_schema) {
            throw new Exception("表单不存在");
        }

        $object_schema = json_decode($json_schema);
        $new_post_object = $this->filter($object_schema, $post_object);
        list($r, $errMsg) = $this->validate($object_schema, $new_post_object);
        if ($r === false) {
            return [false, $errMsg];
        }

        $add_data['form_id'] = $form_id;
        $add_data['create_date'] = time();
        $add_data['json_content'] = json_encode($new_post_object);
        $r = D("FormApply")->add($add_data);
        if ($r === false) {
            return [false, D("FormApply")->getError()];
        }
        return [true, ''];
    }

    protected function filter(object $json_schema, stdClass $post_object) : stdClass{
        $new = new stdClass();
        foreach($post_object as $key => $value){
            if(isset($json_schema->schema->properties->$key)){
                $new->$key = $value;
            }
        }
        return $new;
    }

    /*
     * return [flag, errMsg] flag = true 通过验证， false表示不通过， errMsg出错原因
     */
    protected function validate(stdClass $json_schema, stdClass $post_object) : array{
        foreach($json_schema->schema->properties as $sign => $property){
            $component = ComponentFactory::make($sign, $property);
            if(isset($post_object->$sign)){
                $component->value($post_object->$sign);
                list($r, $errMsg) = $component->validate();

                if($r === false){
                    return [$r, $errMsg];
                }
            }
        }

        return [true, ''];
    }

}