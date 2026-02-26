<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResponseDeviationTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('response_deviation', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('transaction_id');
			$table->integer('item_id');
			$table->string('item_name');
			$table->decimal('request_qty', 9, 2);
			$table->integer('request_unit_id');
			$table->decimal('satisfied_qty',9,2);
			$table->integer('satisfied_unit_id');
			$table->integer('for_location_id');
			$table->integer('from_location_id');
			$table->integer('request_by');
			$table->integer('satisfied_by');
			$table->date('request_when');
			$table->date('satisfied_when');
			$table->boolean('mail_sent')->default(false);
			$table->timestamps();

			$table->index('transaction_id');
			$table->index('item_id');
			$table->index('request_unit_id');
			$table->index('satisfied_unit_id');
			$table->index('for_location_id');
			$table->index('from_location_id');
			$table->index('request_by');
			$table->index('satisfied_by');
			$table->index('request_when');
			$table->index('satisfied_when');
			$table->index('mail_sent');

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('response_deviation', function(Blueprint $table)
		{
			//
		});
	}

}
