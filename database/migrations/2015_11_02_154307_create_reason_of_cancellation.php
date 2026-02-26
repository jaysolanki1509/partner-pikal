<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReasonOfCancellation extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('cancellation_reason', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('outlet_id');
            $table->string('reason_of_cancellation');
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
        Schema::drop('cancellation_reason');
	}

}
