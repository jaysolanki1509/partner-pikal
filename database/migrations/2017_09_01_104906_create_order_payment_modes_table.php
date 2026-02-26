<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderPaymentModesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('order_payment_modes', function(Blueprint $table)
		{
            $table->increments('id');
            $table->integer('order_id');
            $table->integer('payment_option_id');
            $table->integer('source_id');
            $table->float('amount');
            $table->string('transaction_id')->nullable();
            $table->timestamps();

            $table->index('order_id');
            $table->index('payment_option_id');
            $table->index('source_id');
            $table->index('amount');
            $table->index('transaction_id')->nullable();

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('order_payment_modes', function(Blueprint $table)
		{
			//
		});
	}

}
