<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConsumptionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('consumptions', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('transaction_id');
			$table->index('transaction_id');
			$table->integer('item_id')->unsigned();
			$table->index('item_id');
			$table->float('consume');
		});

		Schema::table('stock_history', function(Blueprint $table)
		{
			$table->string('transaction_id')->after('id');
			$table->index('transaction_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('consumptions', function(Blueprint $table)
		{
			//
		});
	}

}
