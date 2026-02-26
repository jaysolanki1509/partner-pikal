<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tables', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('table_no');
			$table->string('shape')->nullable();
			$table->integer('outlet_id')->unsigned();
			$table->integer('no_of_person')->unsigned();
			$table->tinyInteger('status')->default(0);
			$table->integer('occupied_by')->unsigned()->nullable();
			$table->integer('occupied_mobile')->unsigned()->nullable();
			$table->integer('created_by')->unsigned();
			$table->integer('updated_by')->unsigned();
			$table->string('request_from')->nullable();
			$table->SoftDeletingTrait();
			$table->timestamps();

			$table->index('table_no');
			$table->index('no_of_person');
			$table->index('status');
			$table->index('outlet_id');
			$table->index('occupied_by');
			$table->index('occupied_mobile');
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
		Schema::drop('tables');
	}

}
