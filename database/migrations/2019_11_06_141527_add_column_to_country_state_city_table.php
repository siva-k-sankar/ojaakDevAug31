<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToCountryStateCityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('countries', function (Blueprint $table) {
            $table->string('uuid')->nullable()->after('id');
        });
        Schema::table('states', function (Blueprint $table) {
            $table->string('uuid')->nullable()->after('id');
        });
        Schema::table('cities', function (Blueprint $table) {
            $table->string('uuid')->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
         Schema::table('countries', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
        Schema::table('states', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
         Schema::table('cities', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
}
