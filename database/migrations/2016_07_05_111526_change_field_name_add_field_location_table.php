<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeFieldNameAddFieldLocationTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('locations', function(Blueprint $table)
		{
			DB::statement('ALTER TABLE  `locations` CHANGE  `default_outlet_id`  `outlet_id` INT( 10 ) UNSIGNED NULL DEFAULT NULL ;') ;
			$table->tinyInteger('default_location')->default(0)->after('outlet_id');
			$table->index('outlet_id');
			$table->index('default_location');
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
