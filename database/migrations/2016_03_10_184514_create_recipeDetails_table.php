<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecipeDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('recipeDetails', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('outlet_id');
			$table->integer('menu_title_id');
			$table->integer('menu_item_id');
			$table->integer('referance');
			$table->integer('unit_id');
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
		Schema::drop('recipeDetails');
	}

}
