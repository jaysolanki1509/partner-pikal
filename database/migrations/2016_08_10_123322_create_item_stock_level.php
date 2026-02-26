<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemStockLevel extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('stock_level', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('category_id');
			$table->integer('item_id');
			$table->integer('unit_id');
			$table->integer('location_id');
			$table->float('opening_qty')->nullable();
			$table->float('order_qty')->nullable();
			$table->float('reserved_qty')->nullable();
			$table->integer('created_by');
			$table->integer('updated_by');

			$table->index('category_id');
			$table->index('item_id');
			$table->index('unit_id');
			$table->index('location_id');
			$table->index('opening_qty');
			$table->index('order_qty');
			$table->index('reserved_qty');
			$table->index('created_by');
			$table->index('updated_by');

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
		Schema::table('stock_level', function(Blueprint $table)
		{
			//
		});
	}

}
