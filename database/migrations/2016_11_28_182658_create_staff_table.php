<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('staff', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->integer('per_day');
			$table->integer('outlet_id');
			$table->integer('staff_role_id');
			$table->integer('staff_shift_id');
			$table->integer('created_by');
			$table->integer('updated_by');
			$table->timestamps();
			$table->SoftDeletingTrait();

			$table->index('name');
			$table->index('per_day');
			$table->index('outlet_id');
			$table->index('staff_role_id');
			$table->index('staff_shift_id');
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
		//
	}

}
