<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableItemSettings extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('item_settings', function(Blueprint $table)
		{
            $table->increments('id');
            $table->integer('outlet_id')->unsigned();
            $table->integer('item_id')->unsigned();
            $table->boolean('is_active')->default(1);
            $table->boolean('is_sale')->default(1);
            $table->integer('created_by')->unsigned();
            $table->integer('updated_by')->unsigned();
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
        Schema::drop('item_settings');
	}

}
