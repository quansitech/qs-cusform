<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQsFormItem extends Migration
{

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
        Schema::create('qs_form_item', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('form_id')->default(0)->comment('formID');
            $table->string('title',255)->default('')->comment('标题');
            $table->string('type',50)->default('text')->comment('类型');
            $table->string('options',1000)->default('')->comment('选项');
            $table->string('tips',255)->default('')->comment('提示');
            $table->string('placeholder',255)->default('')->comment('占位符');
            $table->tinyInteger('required')->default(0)->comment('是否必填');
            $table->text('other_limit')->comment('其它限制');
            $table->integer('sort')->default(0)->comment('排序');
            $table->integer('create_date')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('qs_form_item');
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
