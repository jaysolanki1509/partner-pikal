<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingAccountStatementsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('booking_account_statements', function(Blueprint $table)
		{
			$table->increments('id');
            $table->integer('booking_id')->unsigned();
            $table->foreign('booking_id')->references('id')->on('bookings');
            $table->date("transaction_date");
            $table->text("description");
            $table->integer('folio_id')->unsigned()->nullable();
            $table->foreign('folio_id')->references('id')->on('folios');
            $table->float("charges");
            $table->string("taxes");
            $table->float("discount");
            $table->float("payments");
            $table->integer('payment_option_id')->unsigned()->nullable();
            $table->foreign('payment_option_id')->references('id')->on('payment_options');
            $table->integer('payment_source_id')->unsigned()->nullable();
            $table->foreign('payment_source_id')->references('id')->on('sources');
            $table->integer('created_by')->unsigned();
            $table->foreign('created_by')->references('id')->on('owners');
            $table->integer('updated_by')->unsigned();
            $table->foreign('updated_by')->references('id')->on('owners');
            $table->integer('deleted_by')->unsigned()->nullable();
            $table->foreign('deleted_by')->references('id')->on('owners');
			$table->timestamps();

            $table->index('transaction_date');
            $table->index('discount');
            $table->index('payments');
            $table->index('charges');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('booking_account_statements');
	}

}
