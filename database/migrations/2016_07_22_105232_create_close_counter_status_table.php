<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCloseCounterStatusTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('send_close_counter_status', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('outlet_id');
			$table->index('outlet_id');
			$table->dateTime('start_date');
			$table->index('start_date');
			$table->dateTime('close_date');
			$table->index('close_date');
			$table->float('amount')->nullable();
			$table->float('total_from_user');
			$table->float('total_from_db')->nullable();
			$table->string('remarks')->nullable();
			$table->integer('sms_count')->default(0);
			$table->tinyInteger('is_send')->default(0);
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
