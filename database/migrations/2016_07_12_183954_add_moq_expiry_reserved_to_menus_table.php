<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoqExpiryReservedToMenusTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('menus', function(Blueprint $table)
		{
			$table->float('moq')->nullable();
			$table->float('reserved')->nullable();
			$table->date('expiry')->nullable();
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
			$table->dropColumn('expiry');
			$table->dropColumn('reserved');
			$table->dropColumn('moq');
		});
	}

}
