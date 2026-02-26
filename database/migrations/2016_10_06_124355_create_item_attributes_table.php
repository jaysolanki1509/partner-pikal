<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemAttributesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('item_attributes', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name');
			$table->integer('created_by');
			$table->integer('updated_by');
			$table->index('created_by');
			$table->index('updated_by');
			$table->timestamps();
			$table->SoftDeletingTrait();
		});
		Schema::create('outlet_item_attributes_mapper', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('attribute_id');
			$table->integer('outlet_id');
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
		//
	}

}
