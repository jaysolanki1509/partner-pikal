<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMigrationOrderCancellationMapper extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('order_cancellation_mapper', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('outlet_id');
            $table->string('suborder_id');
            $table->string('order_date');
            $table->string('reason');
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
