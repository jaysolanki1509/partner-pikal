<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockAgeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('stock_age', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('location_id');
			$table->index('location_id');
			$table->integer('item_id');
			$table->index('item_id');
			$table->integer('unit_id');
			$table->index('unit_id');
			$table->string('transaction_id');
			$table->index('transaction_id');
			$table->date('expiry_date')->nullable();
			$table->index('expiry_date');
			$table->float('quantity');
			$table->integer('created_by');
			$table->integer('updated_by');
			$table->index('created_by');
			$table->index('updated_by');
			$table->timestamps();
			$table->SoftDeletingTrait();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('stock_age', function(Blueprint $table)
		{
			//
		});
	}

}
