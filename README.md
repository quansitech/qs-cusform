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