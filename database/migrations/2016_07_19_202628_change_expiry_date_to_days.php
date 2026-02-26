<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeExpiryDateToDays extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('menus', function(Blueprint $table)
		{
		    $table->dropColumn('expiry');
		});
        Schema::table('menus', function(Blueprint $table)
		{
            $table->integer('expiry')->nullable()->after('reserved');
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
		});
        Schema::table('menus', function(Blueprint $table)
		{
			$table->date('expiry');
		});
	}

}
