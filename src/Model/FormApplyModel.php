<?php


namespace CusForm\Model;


use Gy_Library\GyListModel;

class FormApplyModel extends GyListModel
{
    protected $_auto = array(
        array('create_date', "time", parent::MODEL_INSERT, 'function'),
    );

}