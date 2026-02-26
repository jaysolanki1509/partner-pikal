<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddImagePathMenusTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('menus', function(Blueprint $table)
		{
            DB::statement('ALTER TABLE `menus` CHANGE `image` `image` VARCHAR(255) NULL DEFAULT NULL;');
            DB::statement('UPDATE `menus` SET `image`=NULL WHERE 1;');

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
			//
		});
	}

}
