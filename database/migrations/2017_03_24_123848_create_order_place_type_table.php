<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderPlaceTypeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('order_place_type', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->integer('outlet_id');
			$table->integer('created_by');
			$table->integer('updated_by');
			$table->timestamps();
			$table->softDeletes();

			$table->index('name');
			$table->index('outlet_id');
			$table->index('created_by');
			$table->index('updated_by');
			$table->index('created_at');
			$table->index('updated_at');
			$table->index('deleted_at');
		});

		Schema::table('orders', function(Blueprint $table)
		{
			$table->integer('order_place_id')->default(0)->after('outlet_id');
			$table->tinyInteger('is_custom')->default(0)->after('outlet_id');

			$table->index('order_place_id');
			$table->index('is_custom');
		});



	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('order_place_type', function(Blueprint $table)
		{
			//
		});
	}

}
