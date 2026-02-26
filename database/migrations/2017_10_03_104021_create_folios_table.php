<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFoliosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('folios', function(Blueprint $table)
		{
			$table->increments('id');
            $table->string("folio_no");
            $table->integer('guest_id')->unsigned();
            $table->foreign('guest_id')->references('id')->on('guests');
            $table->integer('booking_id')->unsigned();
            $table->foreign('booking_id')->references('id')->on('bookings');
            $table->float("sub_total");
            $table->string("taxes");
            $table->float("discount");
            $table->float("total");
            $table->integer('created_by')->unsigned();
            $table->foreign('created_by')->references('id')->on('owners');
            $table->integer('updated_by')->unsigned();
            $table->foreign('updated_by')->references('id')->on('owners');
            $table->integer('deleted_by')->unsigned()->nullable();
            $table->foreign('deleted_by')->references('id')->on('owners');
            $table->softDeletes();
			$table->timestamps();

            $table->index('folio_no');
            $table->index('discount');
            $table->index('total');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('folios');
	}

}
