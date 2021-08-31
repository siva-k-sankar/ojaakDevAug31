<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeToTopAdsPlanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('top_ads_plan', function (Blueprint $table) {
             $table->enum('type', ['1', '2','3'])->default('1')->comment(' 1 = Featured_ad , 2 = TopListed , 3 = Pearl')->after('comments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('top_ads_plan', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
}
