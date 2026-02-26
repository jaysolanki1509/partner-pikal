<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSalePerPersonAmdActiveItemInDailySummaries extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('daily_summaries', function(Blueprint $table)
		{
            $table->integer("active_item")->default(0);
            $table->float("tot_sale_per_person");
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
            $table->dropColumn("active_item");
            $table->dropColumn("tot_sale_per_person");
		});
	}

}
