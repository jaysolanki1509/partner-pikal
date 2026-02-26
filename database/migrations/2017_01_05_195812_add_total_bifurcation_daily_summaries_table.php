<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTotalBifurcationDailySummariesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('daily_summaries', function(Blueprint $table)
		{
			$table->text('total_bifurcation')->nullable()->after('total_taxes');
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
