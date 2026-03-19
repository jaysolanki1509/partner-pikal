<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdercouponmappersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ordercouponmappers', function(Blueprint $table)
		{
			$table->increments('id');
            $table->string('order_id');
            $table->string('coupon_applied');
            $table->string('discounted_value');
            $table->string('total_cost_afterdiscount');

			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('ordercouponmappers');
	}

}
