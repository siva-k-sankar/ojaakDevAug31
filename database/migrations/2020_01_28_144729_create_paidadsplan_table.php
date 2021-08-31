<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaidadsplanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paidadsplan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('uuid');
            $table->string('category');
            $table->string('plan_name');
            $table->string('validity');
            $table->string('wallet_points');
            $table->string('quantity_1');
            $table->string('quantity_3');
            $table->string('quantity_5');
            $table->string('quantity_10');
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
        Schema::dropIfExists('paidadsplan');
    }
}
