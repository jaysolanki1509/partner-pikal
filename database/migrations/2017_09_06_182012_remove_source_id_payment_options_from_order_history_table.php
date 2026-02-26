<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveSourceIdPaymentOptionsFromOrderHistoryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('order_history', function(Blueprint $table)
		{
			$table->dropColumn("payment_option_id");
			$table->dropColumn("source_id");
            $table->string("payment_modes")->after("total")->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('order_history', function(Blueprint $table)
		{
			//
		});
	}

}
