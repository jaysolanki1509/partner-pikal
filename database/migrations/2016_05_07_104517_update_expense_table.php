<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateExpenseTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('expense', function($table) {

			DB::statement("ALTER TABLE `expense` CHANGE `expense_date` `expense_date` DATE NULL DEFAULT NULL;");
			$table->dropColumn(['created_date']);
			$table->integer('updated_by');

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
