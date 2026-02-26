<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShiftMasterTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('shift_master', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->string('timing');
			$table->integer('created_by');
			$table->integer('updated_by');
			$table->SoftDeletingTrait();
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
		//
	}

}
