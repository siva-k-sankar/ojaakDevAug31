<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReadStatusToChatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chats', function (Blueprint $table) {
            $table->string('user_1_read_status')->default("0")->comment('0 = unread, 1 = read')->after('user_2');
            $table->string('user_2_read_status')->default("0")->comment('0 = unread, 1 = read')->after('user_1_read_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chats', function (Blueprint $table) {
            $table->dropColumn('user_1_read_status');
            $table->dropColumn('user_2_read_status');
        });
    }
}
