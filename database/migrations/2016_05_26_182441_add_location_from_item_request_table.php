<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLocationFromItemRequestTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('item_request', function(Blueprint $table)
		{
			DB::statement("ALTER TABLE `item_request` CHANGE `location_id` `location_for` INT( 11 ) NULL DEFAULT NULL ;");
			$table->integer('location_from')->nullable()->after('location_for');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('item_request', function(Blueprint $table)
		{
			//
		});
	}

}
