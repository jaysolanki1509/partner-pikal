<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoiceBillsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('invoice_bills', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('invoice_no')->nullable();
			$table->integer('vendor_id');
			$table->integer('location_id');
			$table->float('total');
			$table->string('status');
			$table->date('invoice_date')->nullable();
			$table->boolean('verified')->default(0);
			$table->integer('verified_by')->nullable();
			$table->date('verified_date')->nullable();
			$table->integer('created_by');
			$table->integer('updated_by');
			$table->SoftDeletingTrait();
			$table->timestamps();

			$table->index('verified');
			$table->index('location_id');
			$table->index('invoice_no');
			$table->index('vendor_id');
			$table->index('total');
			$table->index('status');
			$table->index('invoice_date');
			$table->index('verified_by');
			$table->index('verified_date');
			$table->index('created_by');
			$table->index('updated_by');
			$table->index('created_at');
			$table->index('updated_at');
			$table->index('deleted_at');
		});

		Schema::drop('purchases');

		Schema::create('purchase', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('invoice_id');
			$table->string('purchase_unique_id');
			$table->integer('item_id');
			$table->double('rate');
			$table->float('quantity');
			$table->float('total');
			$table->date('received_date')->nullable();
			$table->date('manufacture_date')->nullable();
			$table->date('expiry_date')->nullable();
			$table->SoftDeletingTrait();

			$table->unique('purchase_unique_id');
			$table->index('invoice_id');
			$table->index('item_id');
			$table->index('rate');
			$table->index('quantity');
			$table->index('total');
			$table->index('received_date');
			$table->index('manufacture_date');
			$table->index('expiry_date');
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
