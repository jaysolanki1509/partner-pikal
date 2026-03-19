<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReceiveDateInvoiceStatusToPurchaseTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('purchases', function(Blueprint $table)
		{
			$table->date('received_date')->nullable();
			$table->index('received_date');
            $table->string('invoice_no')->nullable();
            $table->index('invoice_no');
            $table->string('status')->nullable();
            $table->index('status');
            $table->boolean('verified')->default(0);
            $table->date('verified_date')->nullable();
            $table->index('verified_date');
            $table->integer('verified_by')->nullable();
            $table->index('verified_by');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('purchases', function(Blueprint $table)
		{
			$table->dropColumn('verified_by');
			$table->dropColumn('verified_date');
			$table->dropColumn('verified');
			$table->dropColumn('status');
			$table->dropColumn('invoice_no');
			$table->dropColumn('receive_date');
		});
	}

}
