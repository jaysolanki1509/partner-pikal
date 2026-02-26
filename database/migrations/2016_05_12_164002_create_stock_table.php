<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('stocks', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('item_id')->unsigned();
			$table->integer('unit_id')->unsigned();
			$table->integer('location_id')->unsigned();
			$table->integer('created_by')->unsigned();
			$table->integer('updated_by')->unsigned();
			$table->integer('quantity');
            $table->foreign('location_id')->references('id')->on('locations')->nullable();
            $table->foreign('item_id')->references('id')->on('menus')->nullable();
			$table->foreign('created_by')->references('id')->on('owners')->nullable();
			$table->foreign('updated_by')->references('id')->on('owners')->nullable();
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
		//
	}

}
