<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMenuCusForm extends Migration
{
    private $menuData = array(
        '表单管理'=>array(
            array(
                'name'=>'index',       //（必填）
                'title'=>'表单列表',    //（必填）'
                'controller'=>'Form',//（必填）
                'sort' => 0, //排序       //（选填）
                'remark'=> '',//备注      //（选填）
                'status'=>1,//状态        //（选填）
            ),
        ),
    );
    public function beforeCmmUp()
    {
        //
    }

    public function beforeCmmDown()
    {
        //
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        $menuGenerate = new \Larafortp\MenuGenerate();
        $menuGenerate->insertAll($this->menuData);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        $menuGenerate = new \Larafortp\MenuGenerate();
        $menuGenerate->insertAllRollback($this->menuData);
    }

    public function afterCmmUp()
    {
        //
    }

    public function afterCmmDown()
    {
        //
    }
}
