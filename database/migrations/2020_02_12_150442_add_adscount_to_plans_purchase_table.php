<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdscountToPlansPurchaseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('plans_purchase', function (Blueprint $table) {
            $table->string('ads_count')->after('ads_limit');
            $table->string('expire_date')->after('ads_count');
            $table->enum('type', ['0','1','2','3'])->default('0')->comment('0= Paid_Ad ,1 = Featured_Ad , 2 = TopListed , 3 = Pearl')->after('expire_date');
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
            $table->dropColumn('ads_count');
            $table->dropColumn('expire_date');
            $table->dropColumn('type');
        });
    }
}
