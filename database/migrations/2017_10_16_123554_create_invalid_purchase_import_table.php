<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvalidPurchaseImportTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('invalid_purchase_import', function(Blueprint $table)
		{
			$table->increments('id');
            $table->integer('invoice_id');
            $table->string('item_code');
            $table->integer('item_id');
            $table->integer('unit_id');
            $table->float('quantity');
            $table->float('rate');
            $table->date('received_date')->nullable();
            $table->date('manufacture_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('reason');
            $table->integer('created_by')->unsigned();
            $table->foreign('created_by')->references('id')->on('owners');
            $table->integer('updated_by')->unsigned();
            $table->foreign('updated_by')->references('id')->on('owners');
            $table->integer('deleted_by')->unsigned()->nullable();
            $table->foreign('deleted_by')->references('id')->on('owners');
            $table->timestamps();
            $table->SoftDeletingTrait();

            $table->index('item_code');
            $table->index('invoice_id');
            $table->index('unit_id');
            $table->index('item_id');
            $table->index('received_date');
            $table->index('manufacture_date');
            $table->index('expiry_date');
            $table->index('created_at');
            $table->index('updated_at');
            $table->index('deleted_at');

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::drop('invalid_purchase_import');
	}

}
