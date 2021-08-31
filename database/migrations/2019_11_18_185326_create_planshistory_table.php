<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlanshistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plans_history', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('uuid');
            $table->string('plan_id');
            $table->string('modified_name')->nullable();
            $table->string('modified_price')->nullable();
            $table->string('modified_limit')->nullable();
            $table->string('modified_admin_id')->nullable();
            $table->string('modified_date')->nullable();
            $table->string('status')->default('0')->comment('0 = Added, 1 = updated,2 = deleted, 3 = status Changed');
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
        Schema::dropIfExists('plans_history');
    }
}
