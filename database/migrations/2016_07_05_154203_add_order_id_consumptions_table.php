<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrderIdConsumptionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('consumptions', function(Blueprint $table)
		{
			$table->integer('unit_id')->unsigned()->after('item_id');
			$table->integer('order_id')->unsigned()->after('unit_id');
			$table->timestamps();
			$table->index('unit_id');
			$table->index('order_id');
		});

		Schema::table('stock_history', function(Blueprint $table)
		{
			$table->integer('order_id')->after('unit_id')->unsigned()->nullable();
			$table->index('unit_id');
			$table->index('order_id');
			$table->index('from_location');
			$table->index('to_location');
			$table->index('created_by');
			$table->index('updated_by');
		});
		Schema::table('stocks', function(Blueprint $table)
		{
			$table->index('item_id');
			$table->index('unit_id');
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
