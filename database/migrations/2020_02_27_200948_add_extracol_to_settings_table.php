<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExtracolToSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('PAYTM_ENV')->nullable()->after('user_buys_product_point');
            $table->string('MERCHANT_ID')->nullable();
            $table->string('MERCHANT_KEY')->nullable();
            $table->string('WEBSITE')->nullable();
            $table->string('CHANNEL')->nullable();
            $table->string('INDUSTRY_TYPE')->nullable();
            $table->string('SALESWALLETGUID')->nullable();
            $table->string('RAZORPAY_KEY')->nullable();
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
            $table->dropColumn('PAYTM_ENV');
            $table->dropColumn('MERCHANT_ID');
            $table->dropColumn('MERCHANT_KEY');
            $table->dropColumn('WEBSITE');
            $table->dropColumn('CHANNEL');
            $table->dropColumn('INDUSTRY_TYPE');
            $table->dropColumn('SALESWALLETGUID');
            $table->dropColumn('RAZORPAY_KEY');
        });
    }
}
