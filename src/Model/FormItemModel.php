<?php


namespace CusForm\Model;


use Gy_Library\GyListModel;
use phpDocumentor\Reflection\Types\This;
use Think\Exception;

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
            'component' => 'text'
        ],
        [
            'value' => self::TEXTAREA,
            'text' => '多行文本',
            'component' => 'text'
        ],
        [
            'value' => self::SELECT,
            'text' => '下拉选择',
            'component' => 'input'
        ],
        [
            'value' => self::CHECK_BOX,
            'text' => '多项选择',
            'component' => 'input'
        ],
        [
            'value' => self::CITY,
            'text' => '城市地区',
            'component' => null
        ],
        [
            'value' => self::RADIO,
            'text' => '单项选择',
            'component' => 'input'
        ],
        [
            'value' => self::PICTURE,
            'text' => '图片上传',
            'component' => null
        ],
        [
            'value' => self::PICTURES,
            'text' => '多图上传',
            'component' => 'pictures'
        ],
        [
            'value' => self::FILE,
            'text' => '附件上传',
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
        'min_limit'=>[
            'title'=>'最小字数限制',
            'error_msg'=>'__FIELD__必需__VALUE__个字以上',
            'check'=>'minLimit',
            'tips'=>'仅文本类型有效'
            ]
    ];

    public static function minLimit($data,$value){
        return (mb_strlen($data)>=$value);
    }

    public static function getSelectOptions(){
        if (!C('CUS_FORM_ITEM_TYPES')) {
            return self::$selectedOptions;
        }
        $types=C('CUS_FORM_ITEM_TYPES');
        return array_values(array_filter(self::$selectedOptions,function ($item) use ($types){
            return array_search($item['value'],$types)!==false;
        }));
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

    public function checkLimit($data,$formItem,$type=[],$limit_type=''){
        if ($type && !in_array($formItem['type'],$type)){
            return true;
        }
        if (!$data){
            return true;
        }

        $other_limit=json_decode($formItem['other_limit'],true);
        if ($other_limit) {
            foreach ($other_limit as $key=>$item) {
                if ($limit_type && $limit_type!=$key){
                    return true;
                }
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

    public function parseCheckboxText($data, $options){
        $res_arr = self::parseCommonText($data, $options, function($option, $data){
            //基于option项格式化数据，如果need_text为true，又没填写就会返回抛出异常
            //所有检查完毕后，返回option匹配的结果，没有匹配项就返回空数组
            $res = [];
            collect($data)->each(function($item, $index) use ($option, &$res){
                if($option['title'] == $item['title']){
                    if($option['need_text'] == true && qsEmpty($item['text'])){
                        E($option['title'] . ' 未填写完全');
                    }

                    $res[$option['title']] = isset($item['text']) ? $item['text'] : null;
                }
            });

            if(empty($res)){
                return [];
            }

            if(is_null($res[key($res)])){
                return ['title' => key($res)];
            }
            else{
                return ['title' => key($res), 'text' => $res[key($res)]];
            }
        });

        if(empty($res_arr)){
            $res_arr = '';
        }

        if(is_array($res_arr)){
            return json_encode($res_arr);
        }
        else{
            return $res_arr;
        }

    }

    public function parseRadioText($data, $options){
        $res_arr = self::parseCommonText($data, $options, function($option, $data){
            //基于option项格式化数据，如果need_text为true，又没填写就会返回抛出异常
            //检查完毕后，返回option匹配的结果，没有匹配项就返回空数组

            if($option['title'] != $data['title']){
                return null;
            }

            if($option['title'] == $data['title']){
                if($option['need_text'] == true && qsEmpty($data['text'])){
                    E($option['title'] . ' 未填写完全');
                }

                return $data;
            }
        });

        if(empty($res_arr)){
            $res_arr = '';
        }

        if(is_array($res_arr)){
            return json_encode($res_arr);
        }
        else{
            return $res_arr;
        }
    }

    protected function parseCommonText($data, $options, \closure $fn){
        //空内容不做处理
        if(empty($data)){
            return '';
        }

        //统一转出数组内容
        if(is_string($data)){
            $content_arr = json_decode($data, true);
        }
        else{
            $content_arr = $data;
        }

        $options = json_decode(htmlspecialchars_decode($options), true);
        //检测内容格式是否正确，且text内容项有没填写
        try{
            $res = collect($options)->map(function($option, $index) use ($data, $fn){
                return call_user_func($fn, $option, $data);
            })->filter()->all();
            return $res;
        }
        catch(Exception $ex){
            $this->error = $ex->getMessage();
            return false;
        }
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