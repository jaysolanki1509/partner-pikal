<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMethodInRecipedetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('recipeDetails', function(Blueprint $table)
		{
			$table->longText("ingred_method")->after("unit_id");
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('recipeDetails', function(Blueprint $table)
		{
			$table->dropColumn("ingred_method");
		});
	}

}
