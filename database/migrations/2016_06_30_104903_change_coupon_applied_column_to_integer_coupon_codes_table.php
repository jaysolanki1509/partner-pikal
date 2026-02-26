<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeCouponAppliedColumnToIntegerCouponCodesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('ordercouponmappers', function(Blueprint $table) {
			DB::statement('ALTER TABLE  `ordercouponmappers` CHANGE  `coupon_applied`  `coupon_applied` INT( 11 ) NOT NULL ;') ;
			$table->index('coupon_applied');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
