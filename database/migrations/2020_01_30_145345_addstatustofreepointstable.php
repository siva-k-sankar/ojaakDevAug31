<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Addstatustofreepointstable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('freepoints', function (Blueprint $table) {
            
            $table->Integer('status')->comment('0 = debit, 1 = credit')->after('point');

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
