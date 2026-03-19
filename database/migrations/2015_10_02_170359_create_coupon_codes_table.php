<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponCodesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('coupon_codes', function(Blueprint $table)
		{
			$table->increments('id');
            $table->string('coupon_code');
            $table->string('min_value');
            $table->string('max_value');
            $table->string('activated_datetime');
            $table->string('expire_datetime');
            $table->string('percentage');
            $table->string('value');
            $table->string('no_of_users');
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
		Schema::drop('coupon_codes');
	}

}
