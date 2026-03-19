<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockHistoryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('stock_history', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('from')->nullable();
			$table->integer('to')->nullable();
			$table->integer('item_id')->unsigned();
			$table->integer('unit_id');
			$table->integer('created_by')->unsigned();
			$table->integer('updated_by')->unsigned();
			$table->integer('quantity');
			$table->enum('type', array('add', 'remove'));
			$table->foreign('item_id')->references('id')->on('menus')->nullable();
			$table->foreign('created_by')->references('id')->on('owners')->nullable();
			$table->foreign('updated_by')->references('id')->on('owners')->nullable();
			$table->timestamps();
			$table->softDeletes();
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
