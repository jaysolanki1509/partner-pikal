<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveOutletIdAddCraetedByCouponCodesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('coupon_codes', function(Blueprint $table)
		{
			$table->dropColumn("outlet_id");
            $table->integer("created_by")->after("max_value");
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('coupon_codes', function(Blueprint $table)
		{
			//
		});
	}

}
