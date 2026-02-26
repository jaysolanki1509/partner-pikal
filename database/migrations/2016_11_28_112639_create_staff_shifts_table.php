<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffShiftsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

		Schema::drop('shift_master');

		Schema::create('staff_shifts', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->string('slots');
			$table->integer('created_by');
			$table->integer('updated_by');
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
