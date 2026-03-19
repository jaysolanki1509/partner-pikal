<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchasesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('purchases', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('item_id')->unsigned();
            $table->integer('vendor_id')->unsigned();
			$table->integer('unit_id');
            $table->integer('created_by')->unsigned();
			$table->integer('updated_by')->unsigned();
            $table->float('rate');
            $table->integer('quantity');
			$table->integer('location_id');
			$table->foreign('item_id')->references('id')->on('menus')->nullable();
			$table->foreign('vendor_id')->references('id')->on('vendors')->nullable();
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
		Schema::drop('purchases');
	}

}
