<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsInventoryToMenusTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('menus', function(Blueprint $table)
		{
            $table->boolean('is_inventory_item')->after('is_sell')->default(0);

            $table->index('is_inventory_item');
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
            $table->dropColumn('is_inventory_item');
		});
	}

}
