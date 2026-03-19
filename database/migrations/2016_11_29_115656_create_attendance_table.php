<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttendanceTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('attendance', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('staff_id');
			$table->integer('outlet_id');
			$table->dateTime('in_time');
			$table->dateTime('out_time')->nullable();
			$table->integer('created_by');
			$table->integer('updated_by');
			$table->timestamps();
			$table->softDeletes();

			$table->index('staff_id');
			$table->index('outlet_id');
			$table->index('in_time');
			$table->index('out_time');
			$table->index('created_by');
			$table->index('updated_by');
			$table->index('created_at');
			$table->index('updated_at');
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
