<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveSubordIdAddOrderIdOrderCancMapperTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('order_cancellation_mapper', function(Blueprint $table)
		{
			$table->dropColumn("suborder_id");
			$table->dropColumn("order_date");
			$table->integer('order_id')->after('outlet_id');
		});
	}

	/**
	 * Rever migrations.
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
