<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEmailUsernameToBillingInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('billing_information', function (Blueprint $table) {
           $table->string('username')->nullable()->after('user_id');
           $table->string('email')->nullable()->after('username');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('billing_information', function (Blueprint $table) {
            $table->dropColumn('username');
            $table->dropColumn('email');
        });
    }
}
