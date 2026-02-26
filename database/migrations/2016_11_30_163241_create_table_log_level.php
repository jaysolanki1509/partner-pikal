<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableLogLevel extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('log_level', function(Blueprint $table)
		{
            $table->increments('id');
            $table->integer('outlet_id');
            $table->integer('owner_id');
            $table->integer('level')->default(0);
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->timestamps();
            $table->SoftDeletingTrait();

            $table->index('outlet_id');
            $table->index('owner_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('log_level', function(Blueprint $table)
		{
			//
		});
	}

}
