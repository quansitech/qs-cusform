# qs-cusform 自定义表单

## 用法
### 1.安装及执行迁移
```shell script
composer require quansitech/cus-form
php artisan migrate
```
### 2.新增自定义表单和表单项
在后台中新增操作自定义表单，地址 http://[host]:[port]/admin/Form/add <br>

### 3.前台获取自定义表单项
```php
/**
 * @param $form_id 表单id
 * @param $apply_id 表单提交id[可选]
 * @return array
 */
$items=\CusForm\CusForm::getInstance()->getItemData($form_id,$apply_id);
```
返回值示例
```php
$items=[
    [
      'title' =>  '单行文本',
      'type' =>  'text',
      'options' =>  '', 
      'tips' =>  '提示',
      'placeholder' =>  '占位符', 
      'required' =>  '0',
      'other_limit' =>  '{"min_limit":""}', 
      'name' =>  'cus_form_2',
      'value' =>  '123' 
    ]
];
```

### 4.保存提交内容
```php
/**
 * $form_data示例 :
 * ['cus_form_2'=>'123']
 */
$apply_id= \CusForm\CusForm::getInstance()->saveContent($form_id,$form_data);
```
name为上一步返回items中的name <br>
返回apply_id，请自行保存

### 5.后台展示对应提交内容
```php
// $builder为对应的FormBuilder
$builder=\CusForm\CusForm::getInstance()->generateFormItem($builder,$form_id,$apply_id);
$builder->display();
```

### 新增多选文本及单选文本类型
格式说明
```json
//title为答案名称， need_text为true表示点选后必须填写额外的内容
[
    {"title": "语文", "need_text": true},
    {"title": "数学", "need_text": false}
]
```

多选文本提交格式说明
```json
//如果定义了need_text的项，而内容提交时没有text，将会无法提交
[
  {"title": "语文", "text": "90分"},
  {"title": "数学"}
]
```

单选文本提交格式说明
```json
//如果定义了need_text的项，而内容提交时没有text，将会无法提交
{"title": "其他", "text": "社会大学"}
```

### 其它说明
在config.php中加入以下代码可限制自定义表单项的类型
```php
'CUS_FORM_ITEM_TYPES' => [
    'text', //单行文本
    'select', //下拉选择
    'checkbox', //多选框
    'picture', //图片上传
    'file', //文件上传
],
```
具体对应类型请查看 [FormItemModel.php](https://github.com/quansitech/qs-cusform/blob/master/src/Model/FormItemModel.php) 中的"$selectedOptions"配置