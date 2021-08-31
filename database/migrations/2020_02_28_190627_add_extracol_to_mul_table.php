<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExtracolToMulTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('RAZORPAY_SECRET')->nullable()->after('RAZORPAY_KEY');
        });

        Schema::table('plans_purchase', function (Blueprint $table) {
            $table->string('payment_method')->nullable()->after('expire_date');
            $table->string('payment_id')->nullable()->after('payment_method');
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
            $table->dropColumn('RAZORPAY_SECRET');
        });
        
        Schema::table('plans_purchase', function (Blueprint $table) {
            $table->dropColumn('payment_method');
            $table->dropColumn('payment_id');
        });
    }
}
