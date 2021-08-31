<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVerifiedlistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('verifiedlist', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->comment('slug');
            $table->string('description');
            $table->string('status')->default('0')->comment('Required = 0, Optional= 1');
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
        Schema::dropIfExists('verifiedlist');
    }
}
