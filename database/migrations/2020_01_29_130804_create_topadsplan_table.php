<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTopadsplanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('top_ads_plan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('validity_7');
            $table->string('validity_15');
            $table->string('validity_30');
            $table->string('discount');
            $table->string('comments');
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
        Schema::dropIfExists('topadsplan');
    }
}
