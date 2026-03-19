<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldDailySummariesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('daily_summaries', function(Blueprint $table)
		{
			$table->float('total_cheque')->after('total_cash');
			$table->float('total_unpaid')->after('total_cheque');

			$table->index('total_cheque');
			$table->index('total_unpaid');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('daily_summaries', function(Blueprint $table)
		{
			//
		});
	}

}
