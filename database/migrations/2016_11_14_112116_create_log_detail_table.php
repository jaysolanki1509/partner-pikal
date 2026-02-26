<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogDetailTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('log_details', function(Blueprint $table)
		{
            $table->increments('id');
            $table->string('device_id')->nullable();
            $table->integer('outlet_id');
            $table->integer('owner_id');
            $table->integer('manufacturer')->nullable();
            $table->integer('model')->nullable();
            $table->string('device_os')->nullable();
            $table->string('app_version')->nullable();
            $table->string('path');
            $table->SoftDeletingTrait();
            $table->timestamps();

            $table->index('device_os');
            $table->index('outlet_id');
            $table->index('app_version');

        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::drop('log_details');
	}

}
