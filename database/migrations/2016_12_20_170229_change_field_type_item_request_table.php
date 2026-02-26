<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeFieldTypeItemRequestTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('item_request', function(Blueprint $table)
		{
			$table->dateTime('when')->change();
			$table->dateTime('satisfied_when')->change();
		});

		Schema::table('response_deviation', function(Blueprint $table)
		{
			$table->dateTime('request_when')->change();
			$table->dateTime('satisfied_when')->change();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('item_request', function(Blueprint $table)
		{
			//
		});
	}

}
