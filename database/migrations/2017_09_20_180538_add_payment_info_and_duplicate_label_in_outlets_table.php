<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPaymentInfoAndDuplicateLabelInOutletsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('outlets', function(Blueprint $table)
		{
			$table->boolean("payment_info")->after("tax_details");
			$table->boolean("duplicate_watermark")->after("tax_details");
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('outlets', function(Blueprint $table)
		{
			$table->dropColumn("duplicate_watermark");
			$table->dropColumn("payment_info");
		});
	}

}
