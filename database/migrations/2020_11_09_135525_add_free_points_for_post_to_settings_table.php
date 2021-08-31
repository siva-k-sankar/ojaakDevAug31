<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFreePointsForPostToSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('no_free_ads_post_per_month')->nullable()->after('no_free_ads_point_per_month')->comment('Number of Post free ads per 30days');
            $table->string('no_feature_ads_post_per_month')->nullable()->after('no_free_ads_post_per_month')->comment('Number of Post feature ads per 30days');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('no_free_ads_post_per_month');
            $table->dropColumn('no_feature_ads_post_per_month');
        });
    }
}
