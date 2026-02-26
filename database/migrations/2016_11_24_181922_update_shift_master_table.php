<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateShiftMasterTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement('DROP TABLE `shift_master`');

		Schema::create('shift_master', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('outlet_id');
			$table->string('name');
			$table->string('from');
			$table->string('to');
			$table->integer('created_by');
			$table->integer('updated_by');
			$table->timestamps();
			$table->SoftDeletingTrait();

			$table->index('name');
			$table->index('from');
			$table->index('to');
			$table->index('outlet_id');
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
