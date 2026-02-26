<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOwnersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('owners', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('user_name');
			$table->string('email')->unique();
            $table->string('role_id');
            $table->string('timezone');
			$table->string('password', 60);
            $table->integer('contact_no');
            $table->string('gender',10);
            $table->string('state',20);
            $table->string('city',20);
            $table->rememberToken();
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
		Schema::drop('owners');
	}

}
