<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeFromToColumnnameStockHistoryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('stock_history', function(Blueprint $table)
		{
			DB::statement("ALTER TABLE `stock_history` CHANGE `from` `from_location` INT( 11 ) NULL DEFAULT NULL ;");
			DB::statement("ALTER TABLE `stock_history` CHANGE `to` `to_location` INT( 11 ) NULL DEFAULT NULL ;");
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
