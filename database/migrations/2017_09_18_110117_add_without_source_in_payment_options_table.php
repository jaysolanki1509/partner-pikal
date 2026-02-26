<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWithoutSourceInPaymentOptionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('payment_options', function(Blueprint $table)
		{
			$table->boolean("without_source")->after("name");
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('payment_options', function(Blueprint $table)
		{
			$table->dropColumn("without_source");
		});
	}

}
