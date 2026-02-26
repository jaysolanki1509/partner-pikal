<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomTypesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('room_types', function(Blueprint $table)
		{
            $table->increments('id');
            $table->integer('outlet_id')->unsigned();
            $table->foreign('outlet_id')->references('id')->on('outlets');
            $table->string('name');
            $table->string('short_name')->nullable();
            $table->integer('base_occupancy')->default(1);
            $table->integer('higher_occupancy')->default(1);
            $table->boolean('extra_bed_allowed');
            $table->integer('no_of_beds_allowed')->default(0);
            $table->float('base_price')->default(0.00);
            $table->float('higher_price_per_person')->default(0.00);
            $table->float('extra_bed_price')->default(0.00);
            $table->text('amenities')->nullable();

            $table->text('description');
            $table->integer('created_by')->unsigned();
            $table->foreign('created_by')->references('id')->on('owners');
            $table->integer('updated_by')->unsigned();
            $table->foreign('updated_by')->references('id')->on('owners');
            $table->integer('deleted_by')->unsigned()->nullable();
            $table->foreign('deleted_by')->references('id')->on('owners');
            $table->timestamps();
            $table->softDeletes();

            $table->index('name');
            $table->index('short_name');
            $table->index('base_occupancy');
            $table->index('higher_occupancy');
            $table->index('extra_bed_allowed');
            $table->index('no_of_beds_allowed');
            $table->index('base_price');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::drop('room_types');
	}

}
