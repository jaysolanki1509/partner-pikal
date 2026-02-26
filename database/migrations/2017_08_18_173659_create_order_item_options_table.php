<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderItemOptionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('order_item_options', function(Blueprint $table)
		{
            $table->increments('id');
            $table->integer('order_id');
            $table->integer('order_item_id');
            $table->integer('option_item_id');
            $table->integer('qty');
            $table->float('option_item_price');
            $table->timestamps();

            $table->index('qty');
            $table->index('order_id');
            $table->index('order_item_id');
            $table->index('option_item_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('order_item_options', function(Blueprint $table)
		{
			//
		});
	}

}
