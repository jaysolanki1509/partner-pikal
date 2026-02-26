<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTablePersonFieldsKotTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('kot', function(Blueprint $table)
		{
			$table->string('table_no')->after('outlet_id');
			$table->integer('person_no')->after('table_no')->nullable();
			$table->string('status')->after('quantity');
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
