<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOutletMenuBind extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('outlet_menu_bind', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('outlet_id');
			$table->integer('menu_id');
			$table->integer('item_id');
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
		Schema::drop('outlet_menu_bind');
	}

}
