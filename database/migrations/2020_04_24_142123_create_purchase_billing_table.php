<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseBillingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_billing', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('plan_id');
            $table->string('username')->nullable();
            $table->string('email')->nullable();
            $table->string('businessname')->nullable();
            $table->string('gst')->nullable();
            $table->string('gstquestion')->nullable();
            $table->string('addr1')->nullable();
            $table->string('addr2')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_billing');
    }
}
