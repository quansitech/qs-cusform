<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeprecatedV2 extends Migration
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
        Schema::table('qs_form_item',function (Blueprint $table){
            $table->rename('qs_form_item_deprecated');
        });

        Schema::table('qs_form_apply_content',function (Blueprint $table){
            $table->rename('qs_form_apply_content_deprecated');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('qs_form_item_deprecated',function (Blueprint $table){
            $table->rename('qs_form_item');
        });

        Schema::table('qs_form_apply_content_deprecated',function (Blueprint $table){
            $table->rename('qs_form_apply_content');
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
