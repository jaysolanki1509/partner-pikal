<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemMasterTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('item_master', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('catagory_id');
			$table->string('item_name');
			$table->integer('display_order');
			$table->decimal('current_stock', 4, 2);
			$table->string('unit');
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
		Schema::drop('item_master');
	}

}
