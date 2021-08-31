<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Addusedtofreepointstable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('freepoints', function (Blueprint $table) {
            
            $table->Integer('used')->comment('0 = notused, 1 = used')->after('status');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('freepoints', function (Blueprint $table) {
            //
        });
    }
}
