<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDiscountsToPremiumplandetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('premiumplansdetails', function (Blueprint $table) {
            $table->string('discounts')->nullable()->after('validity')->comment('discounts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('premiumplansdetails', function (Blueprint $table) {
            $table->dropColumn('discounts');
        });
    }
}
