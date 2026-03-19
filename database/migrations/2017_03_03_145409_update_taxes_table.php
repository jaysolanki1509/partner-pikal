<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTaxesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::drop('taxes');

		Schema::create('taxes', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->float('tax_percent')->nullable();
			$table->integer('outlet_id');
			$table->integer('created_by');
			$table->integer('updated_by');
			$table->timestamps();

			$table->index('outlet_id');
			$table->index('created_by');
			$table->index('updated_by');

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{

	}

}
