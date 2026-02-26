<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexOrdersInvoiceTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('orders', function(Blueprint $table) {
			$table->index('created_at');
			$table->index('updated_at');
			$table->index('table_start_date');
			$table->index('table_end_date');
			$table->index('order_unique_id');
			$table->index('cancelorder');
			$table->index('paid_type');
			//$table->foreign('outlet_id')->references('id')->on('outlets');
			$table->index('order_type');
			$table->index('status');
			$table->index('device_id');
		});
		Schema::table('invoice_details', function(Blueprint $table) {
			DB::statement('ALTER TABLE `invoice_details` CHANGE `order_id` `order_id` INT( 11 ) UNSIGNED NOT NULL') ;
			$table->foreign('order_id')->references('order_id')->on('orders')->nullable();
			$table->index('created_at');
			$table->index('updated_at');
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
