<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateBuyPriceMenusTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

		Schema::table('menus', function($table) {

			DB::statement("DROP INDEX `menus_buy_price_index` ON `menus`");
			DB::statement("ALTER TABLE  `menus` CHANGE  `buy_price`  `buy_price` DOUBLE( 8, 4 ) NOT NULL DEFAULT  '0.00';");
			DB::statement("ALTER TABLE  `menus` ADD INDEX (  `buy_price` ) ;");
			DB::statement("ALTER TABLE  `menus` CHANGE  `price`  `price` DOUBLE( 8, 4 ) NULL DEFAULT NULL ;");
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
