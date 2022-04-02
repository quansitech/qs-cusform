<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AltQsFormAddUpdateDate extends Migration
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
        Schema::table('qs_form', function (Blueprint $table) {
            $table->integer('updated_date')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('qs_form', function (Blueprint $table) {
            $table->dropColumn('updated_date');
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
