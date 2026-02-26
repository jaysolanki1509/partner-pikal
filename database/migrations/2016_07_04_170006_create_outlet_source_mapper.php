<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOutletSourceMapper extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('outlet_source_mapper', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('outlet_id');
			$table->integer('source_id');
			$table->integer('created_by');
			$table->index('outlet_id');
			$table->index('source_id');
			$table->index('created_by');
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
		Schema::table('outlet_source_mapper', function(Blueprint $table)
		{
			//
		});
	}

}
