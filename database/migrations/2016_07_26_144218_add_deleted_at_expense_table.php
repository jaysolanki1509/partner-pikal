<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDeletedAtExpenseTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('expense', function(Blueprint $table)
		{
			$table->index('created_by');
			$table->index('updated_by');
			$table->index('expense_for');
			$table->index('expense_by');
			$table->index('verify');
			$table->index('created_at');
			$table->index('updated_at');
			$table->index('expense_date');
			$table->SoftDeletingTrait();
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
