<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsAdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::table('ads', function (Blueprint $table) {
            $table->string('status')->default("1")->comment('0 = deactive, 1 = active')->after('seller_information');
            $table->string('approve_status')->default("0")->comment('0 = Pending, 1 = Approve')->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::table('proofs', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('approve_status');
        });
    }
}
