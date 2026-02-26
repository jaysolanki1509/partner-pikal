<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKotTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('kot', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('outlet_id');
			$table->integer('created_by');
			$table->integer('updated_by');
			$table->integer('kot_id');
			$table->integer('kot_order_id');
			$table->integer('item_id');
			$table->string('item_name');
			$table->float('price');
			$table->integer('quantity');
			$table->string('reason')->nullable();
			$table->dateTime('kot_time');

			$table->index('outlet_id');
			$table->index('created_by');
			$table->index('updated_by');
			$table->index('kot_id');
			$table->index('kot_order_id');
			$table->index('item_id');
			$table->index('item_name');

			$table->SoftDeletingTrait();
			$table->timestamps();
		});

		Schema::table('recipeDetails', function(Blueprint $table)
		{
			$table->integer('updated_by')->nullable();
			$table->SoftDeletingTrait();
		});

		Schema::table('kot', function(Blueprint $table)
		{
			$table->integer('print_count');
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
