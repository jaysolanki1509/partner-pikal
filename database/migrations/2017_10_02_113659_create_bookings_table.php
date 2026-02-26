<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::drop('booking');
		Schema::create('bookings', function(Blueprint $table)
		{
            $table->increments('id');
            $table->integer('outlet_id')->unsigned();
            $table->foreign('outlet_id')->references('id')->on('outlets');
            $table->integer('company_id')->unsigned()->nullable();
            $table->foreign('company_id')->references('id')->on('companies');
            $table->integer('guest_id')->unsigned();
            $table->foreign('guest_id')->references('id')->on('guests');
            $table->date('check_in_date');
            $table->date('check_out_date');
            $table->integer('duration');
            $table->integer('no_of_extra_bed');
            $table->string('purpose');
            $table->integer('guest_source_id')->unsigned()->nullable();
            $table->foreign('guest_source_id')->references('id')->on('guest_sources');
            $table->integer('arrival_mode_id')->unsigned()->nullable();
            $table->foreign('arrival_mode_id')->references('id')->on('arrival_departure_modes');
            $table->string('arrival_number');   //train number-bus number
            $table->time('arrival_time');
            $table->integer('departure_mode_id')->unsigned()->nullable();
            $table->foreign('departure_mode_id')->references('id')->on('arrival_departure_modes');
            $table->string('departure_number');     //train number-bus number
            $table->time('departure_time');
            $table->string('booking_type'); //single booking, multiple booking, group booking
            $table->string('reservation_type'); //check in, temprory booking,
            $table->integer('no_of_rooms');
            $table->integer('adult');
            $table->integer('child');
            $table->string('card_detail');
            $table->float('sub_total');
            $table->float('total');
            $table->float('deposit');
            $table->string('taxes');
            $table->float('discount');
            $table->float('discount_percentage');
            $table->text('cancellation_reason');
            $table->float('cancellation_charge');
            $table->boolean('cancellation_booking');
            $table->integer('payment_option_id')->unsigned()->nullable();
            $table->foreign('payment_option_id')->references('id')->on('payment_options');
            $table->integer('payment_source_id')->unsigned()->nullable();
            $table->foreign('payment_source_id')->references('id')->on('sources');
            $table->string('preferences');
            $table->integer('created_by')->unsigned();
            $table->foreign('created_by')->references('id')->on('owners');
            $table->integer('updated_by')->unsigned();
            $table->foreign('updated_by')->references('id')->on('owners');
            $table->integer('deleted_by')->unsigned()->nullable();
            $table->foreign('deleted_by')->references('id')->on('owners');
            $table->softDeletes();
            $table->timestamps();

            $table->index('check_in_date');
            $table->index('check_out_date');
            $table->index('duration');
            $table->index('arrival_number');
            $table->index('no_of_extra_bed');
            $table->index('arrival_time');
            $table->index('booking_type');
            $table->index('reservation_type');
            $table->index('no_of_rooms');
            $table->index('adult');
            $table->index('child');
            $table->index('sub_total');
            $table->index('total');
            $table->index('deposit');
            $table->index('discount');
            $table->index('discount_percentage');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('bookings');
	}

}
