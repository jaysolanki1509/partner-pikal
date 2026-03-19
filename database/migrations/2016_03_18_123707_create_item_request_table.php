<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemRequestTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//

		Schema::create('item_request', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('what_item_id');
			$table->string('what_item');
			$table->integer('owner_to');
			$table->integer('owner_by');
			$table->date('when');
			$table->decimal('qty', 4, 2);
			$table->decimal('existing_qty', 4, 2);
			$table->string('satisfied')->default('No');
			$table->integer('satisfied_by')->nullable();
			$table->date('satisfied_when')->nullable();
			$table->decimal('statisfied_qty', 4, 2)->nullable();
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
		Schema::drop('item_request');
	}

}
