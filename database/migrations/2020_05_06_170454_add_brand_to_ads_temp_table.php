<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBrandToAdsTempTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ads_temp', function (Blueprint $table) {
            $table->integer('brand_id')->nullable()->after('sub_categories');
            $table->integer('model_id')->nullable()->after('brand_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ads_temp', function (Blueprint $table) {
            $table->dropColumn('brand_id');
            $table->dropColumn('model_id');
        });
    }
}
