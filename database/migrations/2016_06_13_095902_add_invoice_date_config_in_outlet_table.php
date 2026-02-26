<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInvoiceDateConfigInOutletTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('outlets', function(Blueprint $table)
        {
            $table->boolean('invoice_date')->default(true)->after("invoice_digit");
            $table->boolean('order_no_reset')->default(true)->after("invoice_digit");
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
            $table->dropColumn('invoice_date');
            $table->dropColumn('order_no_reset');
        });
	}

}
