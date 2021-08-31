<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('parent_categories', function (Blueprint $table) {
            $table->string('uuid')->nullable()->after('id');
        });
        Schema::table('sub_categories', function (Blueprint $table) {
            $table->string('uuid')->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('parent_categories', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
        Schema::table('sub_categories', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
}
