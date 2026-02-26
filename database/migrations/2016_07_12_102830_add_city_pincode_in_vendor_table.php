<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCityPincodeInVendorTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('vendors', function(Blueprint $table)
		{
			$table->integer('country_id')->unsigned();
			$table->integer('state_id')->unsigned();
			$table->integer('city_id')->unsigned();
			$table->string('pincode',20)->after("city_id");
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('vendors', function(Blueprint $table)
		{
			$table->dropColumn('state_id');
			$table->dropColumn('country_id');
			$table->dropColumn('pincode');
			$table->dropColumn('city_id');
		});
	}

}
