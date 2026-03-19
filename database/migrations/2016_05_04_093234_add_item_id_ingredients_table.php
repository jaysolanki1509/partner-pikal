<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddItemIdIngredientsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('ingredients', function(Blueprint $table)
		{
			$table->dropColumn('name');
			$table->integer('ing_item_id')->after('recipeDetails_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('ingredients', function(Blueprint $table)
		{
            $table->string('name');
            $table->dropColumn('ing_item_id');
		});
	}

}
