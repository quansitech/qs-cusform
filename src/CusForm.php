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
        $json_schema = D("Form")->where(['id' => $form_id, 'deleted' => DBCont::NO_BOOL_STATUS])->getField('json_schema');
        if(!$json_schema){
            return $json_schema;
        }

        $schema = json_decode($json_schema);
        if(!is_object($schema)){
            return $json_schema;
        }

        $this->normalizeUploadAction($schema);

        return json_encode($schema);
    }

    public function getApplySchema(int $apply_id, string $mode = 'edit') : stdClass
    {
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
            if (!isset($schema->schema->properties->$sign)) {
                continue;
            }

            $schema->schema->properties->$sign->default = $value;
            if($mode === 'readonly'){
                Helper::wrapComponentIllegalProp($schema->schema->properties->$sign, 'x-pattern', 'disabled');
            }
        }

        $this->normalizeUploadAction($schema);

        return $schema;
    }

    protected function normalizeUploadAction($schema): void
    {
        if (empty($schema->schema->properties)) {
            return;
        }

        foreach ($schema->schema->properties as $property) {
            $this->normalizeUploadProperty($property);
        }
    }

    /**
     * 表单设计时保存的是所在环境的完整地址，读取时改成当前站点的地址，避免本地测试时请求到生产环境。
     */
    protected function normalizeUploadProperty($property): void
    {
        if (!is_object($property)) {
            return;
        }

        $props = $property->{'x-component-props'} ?? null;
        if (is_object($props)) {
            $component = $property->{'x-component'} ?? null;
            if ($component === 'Upload') {
                $props->action = $this->normalizeEnvUrl(
                    $props->action ?? null,
                    '#(?:^|/)((?:extends/(?:aliyunOss|ObjectStorage)/policyGet(?:/type/[^/]+)?)|(?:api/upload/(?:uploadFile|upload)))/?$#i'
                );
                $props->accept = $this->normalizeUploadAccept($props->accept ?? null);
            } elseif ($component === 'Area') {
                $props->url = $this->normalizeEnvUrl(
                    $props->url ?? null,
                    '#(?:^|/)(extends/area/getAreaById)/?$#i'
                );
            }
        }

        if (is_object($property->properties ?? null)) {
            foreach ($property->properties as $child) {
                $this->normalizeUploadProperty($child);
            }
        }

        if (is_object($property->items ?? null)) {
            $this->normalizeUploadProperty($property->items);
        }
    }

    protected function normalizeUploadAccept($accept)
    {
        if (!is_string($accept) || stripos($accept, 'image/*') === false) {
            return $accept;
        }

        $items = array_values(array_filter(array_map('trim', explode(',', $accept)), 'strlen'));
        foreach (['image/heic', 'image/heif', '.heic', '.heif'] as $heicItem) {
            $exists = false;
            foreach ($items as $item) {
                if (strcasecmp($item, $heicItem) === 0) {
                    $exists = true;
                    break;
                }
            }
            if (!$exists) {
                $items[] = $heicItem;
            }
        }

        return implode(',', $items);
    }

    protected function normalizeEnvUrl($url, string $pattern)
    {
        if (!is_string($url) || $url === '') {
            return $url;
        }

        $path = parse_url($url, PHP_URL_PATH);
        if (!$path || !preg_match($pattern, $path, $matches)) {
            return $url;
        }

        $query = parse_url($url, PHP_URL_QUERY);
        return $this->buildLocalUrl($matches[1], $query ?: '');
    }

    protected function buildLocalUrl(string $route, string $query = ''): string
    {
        $path = (defined('__ROOT__') ? __ROOT__ : '') . '/' . ltrim($route, '/');
        if ($query !== '') {
            $path .= '?' . $query;
        }

        return $path;
    }

    public function getApplyRecord(int $apply_id) : array
    {
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
        $res = [];
        foreach($schema->schema->properties as $sign => $property){
            $object = new stdClass();
            $object->component_type = Helper::getComponentIllegalProp($property, 'x-component');
            $object->title = $property->title;
            $object->value = $content->$sign ?? null;
            array_push($res, $object);
        }

        return $res;
    }


    public function editApply(int $apply_id, stdClass $post_object) : array
    {
        $ent = D("FormApply")->where(['id' => $apply_id])->find();
        if(!$ent){
            throw new Exception("找不到填写的表单内容");
        }

        $json_schema = $this->formSchema($ent['form_id']);
        if (!$json_schema) {
            throw new Exception("表单不存在");
        }

        $schema = json_decode($json_schema);
        $new_post_object = $this->filter($schema, $post_object);
        list($r, $errMsg) = $this->validate($schema, $new_post_object);
        if ($r === false) {
            return [false, $errMsg];
        }

        $ent['json_content'] = json_encode($new_post_object);
        $r = D("FormApply")->save($ent);
        if ($r === false) {
            return [false, D("FormApply")->getError()];
        }
        return [true, ''];
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
        return [$r, ''];
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
