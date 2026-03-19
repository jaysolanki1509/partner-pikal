<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayumoneysTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('payumoneys', function(Blueprint $table)
		{
			$table->increments('id');
            $table->string('transaction_id');
            $table->string('status');
            $table->string('order_id');
            $table->string('payment_id');
            $table->string('payumoney_id');
            $table->string('amount');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('payumoneys');
	}

}
