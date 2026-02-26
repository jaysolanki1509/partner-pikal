<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRequestItemInStockLevelTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('stock_level', function(Blueprint $table)
		{
			$table->string('request_item')->default('true');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('stock_level', function(Blueprint $table)
		{
			$table->dropColumn('request_item');
		});
	}

}
