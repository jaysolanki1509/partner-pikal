<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrinterItemBindTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('printer_item_bind', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('category_id');
			$table->integer('item_id');
			$table->integer('outlet_id');
			$table->integer('printer_id');
			$table->integer('created_by');
			$table->integer('updated_by');
			$table->string('mac_address');

			$table->index('category_id');
			$table->index('item_id');
			$table->index('outlet_id');
			$table->index('printer_id');
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
		Schema::table('printer_item_bind', function(Blueprint $table)
		{
			//
		});
	}

}
