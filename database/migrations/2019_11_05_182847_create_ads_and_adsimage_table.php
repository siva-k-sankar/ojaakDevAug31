<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdsAndAdsimageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ads', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('uuid');
            $table->string('categories');
            $table->string('sub_categories');
            $table->string('title');
            $table->string('cities');
            $table->string('price');
            $table->text('description');
            $table->string('tags');
            $table->string('seller_id');
            $table->string('seller_information')->default("1")->comment('0 = hide, 1 = show');
            
            $table->timestamps();
        });
        Schema::create('ads_image', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('ads_id');
            $table->string('image');
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
        Schema::dropIfExists('ads');
        Schema::dropIfExists('adsimage');
    }
}
