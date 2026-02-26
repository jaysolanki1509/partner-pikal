<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveUniqueEmailAddUniqueUsername extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('owners', function(Blueprint $table)
		{
            $table->dropUnique('owners_email_unique');
            $table->unique('user_name');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('owners', function(Blueprint $table)
		{
			$table->unique('email');
            $table->dropUnique('owners_user_name_unique');
		});
	}

}
