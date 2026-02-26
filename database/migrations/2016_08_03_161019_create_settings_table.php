<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('setting_master', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->string('setting_name');
            $table->string('setting_default');
            $table->index('created_by');
            $table->index('updated_by');
            $table->timestamps();
            $table->SoftDeletingTrait();
        });

		Schema::create('outlet_settings', function(Blueprint $table)
		{
			$table->increments('id');
            $table->integer('outlet_id');
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->integer('setting_id');
            $table->string('setting_value');
            $table->index('outlet_id');
            $table->index('created_by');
            $table->index('updated_by');
			$table->timestamps();
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
		Schema::drop('outlet_settings');
		Schema::drop('setting_master');
	}

}
