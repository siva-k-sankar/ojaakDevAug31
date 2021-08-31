<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRefundOrderStatusToPlansPurchaseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('plans_purchase', function (Blueprint $table) {
            $table->string('refund_order_status')->nullable()->after('refund_orderi_d');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('plans_purchase', function (Blueprint $table) {
            $table->dropColumn('refund_order_status');
        });
    }
}
