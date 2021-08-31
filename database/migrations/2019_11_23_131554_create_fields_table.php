<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fields', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('uuid');
            $table->string('name')->nullable();
            $table->enum('type', ['text','textarea','checkbox','checkbox_multiple','select','radio','file'])->default('text');
            $table->bigInteger('max')->nullable()->default(255);
            $table->string('default')->nullable();
            $table->tinyInteger('required')->nullable();
            $table->tinyInteger('active')->nullable();
            $table->string('created_by')->nullable();
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
        Schema::dropIfExists('fields');
    }
}
