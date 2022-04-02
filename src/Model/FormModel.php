<?php


namespace CusForm\Model;


use Gy_Library\GyListModel;

class FormModel extends GyListModel
{
    protected $_validate=array(
        array('title','require','缺少标题')
    );

    protected $_delete_auto = array(
        array('delete', 'FormItem', array('id' => 'form_id')),
    );

    protected $_auto = array(
        array('create_date', "time", parent::MODEL_INSERT, 'function'),
        array('updated_date', "time", parent::MODEL_INSERT, 'function'),
    );
}