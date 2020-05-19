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

    private static $itemType=array(
        self::TEXT=>'单行文本',
        self::TEXTAREA=>'多行文本',
        self::SELECT=>'下拉选择',
        self::CHECK_BOX=>'多选',
        self::CITY=>'城市',
        self::RADIO=>'单选',
        self::PICTURE=>'图片',
        self::PICTURES=>'多图',
        self::FILE=>'文件',
        self::DESCRIPTION=>'说明文本',
    );

    const OTHER_LIMIT_LIST=[
        'min_limit'=>['title'=>'最小字数限制','error_msg'=>'__FIELD__必需__VALUE__个字以上','check'=>'minLimit']
    ];

    public static function minLimit($data,$value){
        return (mb_strlen($data)>=$value);
    }

    public static function getItemTypeList(){
        if (!C('CUS_FORM_ITEM_TYPES')) {
            return self::$itemType;
        }
        $types=C('CUS_FORM_ITEM_TYPES');
        $res=[];
        foreach ($types as $type) {
            $res[$type]=self::getItemType($type);
        }
        return $res;
    }

    public static function getItemType($type){
        return self::$itemType[$type];
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