<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeQuantityFieldStockStockHistoryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('stocks', function(Blueprint $table)
		{
			DB::statement('ALTER TABLE `stocks` CHANGE `quantity` `quantity` FLOAT( 11 ) NOT NULL ;');
		});
		Schema::table('stock_history', function(Blueprint $table)
		{
			DB::statement('ALTER TABLE `stock_history` CHANGE `quantity` `quantity` FLOAT( 11 ) NOT NULL ;');
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
