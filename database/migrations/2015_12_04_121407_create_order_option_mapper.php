<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderOptionMapper extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('order_options', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('order_id');
            $table->string('item_id');
            $table->string('item_name');
            $table->string('order_option');
            $table->string('order_price');
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
		//
	}

}
