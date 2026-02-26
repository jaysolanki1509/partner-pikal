<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDeletedAtInPrintersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('printers', function(Blueprint $table)
		{
			$table->SoftDeletingTrait();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('printers', function(Blueprint $table)
		{
			$table->dropSoftDeletingTrait();
		});
	}

}
