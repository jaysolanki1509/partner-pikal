<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUniqueNumbersCountDailySummariesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('daily_summaries', function(Blueprint $table)
		{
			$table->integer('today_unique_mobiles');
            $table->integer('total_unique_mobiles');
            $table->index('today_unique_mobiles');
            $table->index('total_unique_mobiles');
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
