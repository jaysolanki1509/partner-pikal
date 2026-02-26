<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCreatedByOrderCancellationMapperTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('order_cancellation_mapper', function(Blueprint $table)
		{
			$table->integer('created_by')->nullable();
			$table->index('outlet_id');
			$table->index('order_id');
			$table->index('created_by');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('order_cancellation_mapper', function(Blueprint $table)
		{
			//
		});
	}

}
