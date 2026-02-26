<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomAmenitiesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('room_amenities', function(Blueprint $table)
		{
			$table->increments('id');
            $table->integer('outlet_id')->unsigned();
            $table->foreign('outlet_id')->references('id')->on('outlets');
            $table->string('name');
            $table->text('description');
            $table->integer('created_by')->unsigned();
            $table->foreign('created_by')->references('id')->on('owners');
            $table->integer('updated_by')->unsigned();
            $table->foreign('updated_by')->references('id')->on('owners');
            $table->integer('deleted_by')->unsigned()->nullable();
            $table->foreign('deleted_by')->references('id')->on('owners');
            $table->timestamps();
            $table->SoftDeletingTrait();

            $table->index('name');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('room_amenities');
	}

}
