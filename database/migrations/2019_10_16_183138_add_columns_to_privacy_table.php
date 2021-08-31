<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToPrivacyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('privacy', function (Blueprint $table) {
            $table->string('offers')->default('1')->comment('0-hide,1-show')->after('view_chat');
            $table->string('recommendations')->default('1')->comment('0-hide,1-show')->after('offers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('privacy', function (Blueprint $table) {
            $table->dropColumn('offers');
            $table->dropColumn('recommendations');
        });
    }
}
