<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffingTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('staffing', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('outlet_id');
			$table->integer('staff_role_id');
			$table->integer('staff_shift_id');
			$table->integer('qty');
			$table->integer('created_by');
			$table->integer('updated_by');
			$table->timestamps();
			$table->softDeletes();

			$table->index('qty');
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
		Schema::table('staffing', function(Blueprint $table)
		{
			//
		});
	}

}
