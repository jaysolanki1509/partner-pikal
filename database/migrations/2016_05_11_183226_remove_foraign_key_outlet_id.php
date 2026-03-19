<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveForaignKeyOutletId extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('menu_titles', function(Blueprint $table)
		{
			$table->dropForeign('menu_titles_outlet_id_foreign');
		});
        Schema::table('menus', function(Blueprint $table1)
		{
			$table1->dropForeign('menus_outlet_id_foreign');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('menu_titles', function(Blueprint $table)
		{
			//
		});
	}

}
