<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSlotNameToTimeslotTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('timeslot', function(Blueprint $table)
		{
			$table->string('slot_name')->after('outlet_id')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('timeslot', function(Blueprint $table)
		{
			$table->dropColumn('slot_name');
		});
	}

}
