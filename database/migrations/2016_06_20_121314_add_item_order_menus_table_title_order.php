<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddItemOrderMenusTableTitleOrder extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('menus', function(Blueprint $table)
		{
			$table->integer("item_order")->default(1)->after('item');
		});
		Schema::table('menu_titles', function(Blueprint $table)
		{
			$table->integer("title_order")->default(1)->after('title');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('menus', function(Blueprint $table)
		{
			$table->dropColumn('item_order');
		});
		Schema::table('menu_titles', function(Blueprint $table)
		{
			$table->dropColumn('title_order');
		});
	}

}
