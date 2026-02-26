<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableBooking extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('booking', function(Blueprint $table)
		{
            $table->increments('id');
            $table->integer('outlet_id');
            $table->string('name', 60);
            $table->string('contact_no',15)->nullable();
            $table->integer('table_id')->nullable();
            $table->string('proof_type', 25);
            $table->string('proof_id', 100);
            $table->string('reason', 150)->nullable();
            $table->timestamp('start_time');
            $table->timestamp('end_time')->nullable();
            $table->SoftDeletingTrait();
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
        Schema::drop('booking');
	}

}
