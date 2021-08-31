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
            $table->string('status')->default("1")->comment('0 = deactive, 1 = active')->after('name');
        });
        Schema::table('states', function (Blueprint $table) {
            $table->string('status')->default("1")->comment('0 = deactive, 1 = active')->after('country_id');
        });
        Schema::table('cities', function (Blueprint $table) {
            $table->string('status')->default("1")->comment('0 = deactive, 1 = active')->after('state_id');
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
            $table->dropColumn('status');
        });
        Schema::table('states', function (Blueprint $table) {
            $table->dropColumn('status');
        });
         Schema::table('cities', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
