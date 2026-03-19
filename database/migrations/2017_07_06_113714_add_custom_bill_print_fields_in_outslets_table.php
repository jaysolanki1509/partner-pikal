<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCustomBillPrintFieldsInOutsletsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('outlets', function(Blueprint $table)
		{
            $table->string('custom_bill_print_fields')->after('default_taxes')->nullable();
		});
        Schema::table('orders', function(Blueprint $table)
		{
            $table->string('custom_fields')->after('order_type')->nullable();
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
            $table->dropColumn('custom_bill_print_fields');
		});
        Schema::table('orders', function(Blueprint $table)
		{
            $table->dropColumn('custom_fields');
		});
	}

}
