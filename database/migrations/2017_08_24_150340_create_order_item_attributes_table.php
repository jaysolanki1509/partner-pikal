<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderItemAttributesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('order_item_attributes', function(Blueprint $table)
		{
			$table->increments('id');
            $table->integer('order_item_id');
            $table->integer('attribute_id')->default(0);
            $table->string('attribute_name');
			$table->timestamps();

            $table->index('order_item_id');
            $table->index('attribute_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('order_item_attributes');
	}

}
