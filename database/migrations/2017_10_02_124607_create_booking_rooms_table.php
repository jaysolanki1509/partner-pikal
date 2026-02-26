<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingRoomsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('booking_rooms', function(Blueprint $table)
		{
			$table->increments('id');
            $table->integer('booking_id')->unsigned();
            $table->foreign('booking_id')->references('id')->on('bookings');
            $table->integer('room_type_id')->unsigned();
            $table->foreign('room_type_id')->references('id')->on('room_types');
            $table->integer('room_id')->unsigned();
            $table->foreign('room_id')->references('id')->on('rooms');
            $table->string('reservation_no');
            $table->integer('guest_id')->unsigned();
            $table->foreign('guest_id')->references('id')->on('guests');
            $table->integer('adult');
            $table->integer('child');
            $table->date('check_in_date');
            $table->date('check_out_date');
            $table->string('reservation_type');
            $table->float('price');
            $table->text('cancellation_reason');
            $table->float('cancellation_charge');
            $table->boolean('cancellation_room');
            $table->integer('created_by')->unsigned();
            $table->foreign('created_by')->references('id')->on('owners');
            $table->integer('updated_by')->unsigned();
            $table->foreign('updated_by')->references('id')->on('owners');
            $table->integer('deleted_by')->unsigned()->nullable();
            $table->foreign('deleted_by')->references('id')->on('owners');
            $table->softDeletes();
			$table->timestamps();

            $table->index('reservation_no');
            $table->index('adult');
            $table->index('child');
            $table->index('check_in_date');
            $table->index('check_out_date');
            $table->index('reservation_type');
            $table->index('price');
            $table->index('cancellation_room');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('booking_rooms');
	}

}
