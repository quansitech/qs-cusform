<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnFormDeleteStatus extends Migration
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
        //
        Schema::table('qs_form',function (Blueprint $table){
            $table->tinyInteger('deleted')->default(0)->comment('删除?');
        });
        Schema::table('qs_form_item',function (Blueprint $table){
            $table->tinyInteger('deleted')->default(0)->comment('删除?');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('qs_form',function (Blueprint $table){
            $table->dropColumn('deleted');
        });
        Schema::table('qs_form_item',function (Blueprint $table){
            $table->dropColumn('deleted');
        });
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
