<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWalletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('wallet1')->nullable()->comment('this wallets for new users registration.');
            $table->unsignedInteger('wallet2')->nullable()->comment('this wallets for new ads posting.');
            $table->unsignedInteger('wallet3')->nullable()->comment('this wallets for new Ads viewers.');
            $table->unsignedInteger('wallet4')->nullable()->comment('this wallets for new account verification like Facebook,Google, etc.');
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
        Schema::dropIfExists('wallets');
    }
}
