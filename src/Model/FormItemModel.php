<?php


namespace CusForm\Model;


use Gy_Library\GyListModel;
use phpDocumentor\Reflection\Types\This;

class FormItemModel extends GyListModel
{
    protected $_validate=[
        ['title','require','缺少表单项标题'],
        ['type','require','缺少表单项类型'],
        ['required','require','缺少是否必填']
    ];

    protected $_auto = array(
        array('create_date', "time", parent::MODEL_INSERT, 'function'),
    );
    const TEXT='text';
    const SELECT='select';
    const TEXTAREA='textarea';
    const CHECK_BOX='checkbox';
    const PICTURE='picture';
    const CITY='city';
    const PICTURES='pictures';
    const RADIO='radio';
    const FILE='file';
    const DESCRIPTION='description';
    const CHECKBOX_TEXT='checkbox-text';
    const RADIO_TEXT='radio-text';


    private static $selectedOptions = [
        [
            'value' => self::TEXT,
            'text' => '单行文本',
            'component' => null
        ],
        [
            'value' => self::TEXTAREA,
            'text' => '多行文本',
            'component' => null
        ],
        [
            'value' => self::SELECT,
            'text' => '下拉选择',
            'component' => 'input'
        ],
        [
            'value' => self::CHECK_BOX,
            'text' => '多选',
            'component' => 'input'
        ],
        [
            'value' => self::CITY,
            'text' => '城市',
            'component' => null
        ],
        [
            'value' => self::RADIO,
            'text' => '单选',
            'component' => 'input'
        ],
        [
            'value' => self::PICTURE,
            'text' => '图片',
            'component' => null
        ],
        [
            'value' => self::PICTURES,
            'text' => '多图',
            'component' => null
        ],
        [
            'value' => self::FILE,
            'text' => '文件',
            'component' => null
        ],
        [
            'value' => self::DESCRIPTION,
            'text' => '说明文本',
            'component' => null
        ],
        [
            'value' => self::CHECKBOX_TEXT,
            'text' => '多选文本',
            'component' => 'option-text'
        ],
        [
            'value' => self::RADIO_TEXT,
            'text' => '单选文本',
            'component' => 'option-text'
        ]
    ];

    const OTHER_LIMIT_LIST=[
        'min_limit'=>['title'=>'最小字数限制','error_msg'=>'__FIELD__必需__VALUE__个字以上','check'=>'minLimit']
    ];

    public static function minLimit($data,$value){
        return (mb_strlen($data)>=$value);
    }

    public static function getSelectOptions(){
        return self::$selectedOptions;
    }

    public static function returnItemType(){
        return collect(self::$selectedOptions)->map(function($option, $index){
            return [$option['value'] => $option['text']];
        })->collapse()->all();
    }

    public static function getItemTypeList(){
        if (!C('CUS_FORM_ITEM_TYPES')) {
            return self::returnItemType();
        }
        $types=C('CUS_FORM_ITEM_TYPES');
        $res=[];
        foreach ($types as $type) {
            $res[$type]=self::getItemType($type);
        }
        return $res;
    }

    public static function getItemType($type){
        $itemType = self::returnItemType();
        return $itemType[$type];
    }

    private function _handleLimit(&$data){
        $limit=[];
        foreach (self::OTHER_LIMIT_LIST as $key=>$item) {
            $limit[$key]=$data[$key];
            unset($data[$key]);
        }
        $data['other_limit']=json_encode($limit);
    }

    public function checkLimit($data,$formItem){
        $other_limit=json_decode($formItem['other_limit'],true);
        if ($other_limit) {
            foreach ($other_limit as $key=>$item) {
                $limit=self::OTHER_LIMIT_LIST[$key];
                $fun=$limit['check'];
                if (self::$fun($data,$item)==false){
                    $errmsg=str_replace('__FIELD__',$formItem['title'],$limit['error_msg']);
                    $errmsg=str_replace('__VALUE__',$item,$errmsg);
                    $this->error=$errmsg;
                    return false;
                }
            }
        }
        return true;
    }

    public function addItem($data){
        $this->_handleLimit($data);

        if ($this->createAdd($data)!==false){
            return true;
        }else{
            return false;
        }
    }

    public function editItem($data){
        $this->_handleLimit($data);

        if ($this->createSave($data)!==false){
            return true;
        }else{
            return false;
        }
    }
}