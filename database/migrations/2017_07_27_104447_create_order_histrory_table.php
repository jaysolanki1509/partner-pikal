<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderHistroryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('order_history', function(Blueprint $table)
		{
			$table->increments('id');
            $table->integer('order_id');
            $table->string('invoice_no')->nullable();
            $table->integer('user_mobile_no')->nullable();
            $table->text('address')->nullable();
            $table->string('owner');
            $table->string('status')->default('delivered');
            $table->string('order_type');
            $table->text('custom_fields')->nullable();
            $table->float('sub_total');
            $table->float('discount');
            $table->string('taxes')->nullable();
            $table->float('round_off');
            $table->float('total');
            $table->integer('payment_option_id');
            $table->integer('source_id');
            $table->float('delivery_charge');
            $table->boolean('zoho_sync')->default(0);
            $table->text('zoho_message')->nullable();
            $table->timestamps();

            $table->index('order_id');
            $table->index('invoice_no');
            $table->index('owner');
            $table->index('order_type');
            $table->index('payment_option_id');
            $table->index('source_id');
            $table->index('zoho_sync');


		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('order_history', function(Blueprint $table)
		{
			//
		});
	}

}
