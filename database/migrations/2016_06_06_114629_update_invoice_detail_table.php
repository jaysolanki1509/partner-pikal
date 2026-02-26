<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateInvoiceDetailTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('invoice_details', function(Blueprint $table)
		{
			$table->dropColumn('tax_type');
			$table->dropColumn('tax_percent');
			$table->dropColumn('price_after_tax');
			$table->float('sub_total');
			$table->float('discount')->nullable();
			$table->text('taxes')->nullable();
			$table->float('round_off')->nullable();
			$table->float('total');
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
