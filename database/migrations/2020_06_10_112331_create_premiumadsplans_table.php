<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePremiumadsplansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('premiumadsplans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('uuid');
            $table->string('category');
            $table->string('plan_name');
            $table->string('validity')->comment('VALIDITY FOR EACH AD AND WALLET POINTS
FROM ACTIVATION ');
            $table->string('ads_points');
            $table->string('discount')->nullable();
            $table->string('status')->nullable()->comment('0=inactive,1=active')->default('1');
            $table->string('comments')->nullable();
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
        Schema::dropIfExists('premiumadsplans');
    }
}
