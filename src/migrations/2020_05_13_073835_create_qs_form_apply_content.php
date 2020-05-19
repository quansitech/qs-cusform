<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQsFormApplyContent extends Migration
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
        Schema::create('qs_form_apply_content', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('form_apply_id')->default(0)->comment('表单提交id');
            $table->integer('form_item_id')->default(0)->comment('表单项id');
            $table->string('content',1000)->default('')->comment('提交内容');
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
        Schema::dropIfExists('qs_form_apply_content');
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
