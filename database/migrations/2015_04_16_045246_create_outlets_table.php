<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOutletsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('outlets', function(Blueprint $table)
		{
            $table->increments('id');
            $table->integer('locality')->unsigned();
            $table->integer('owner_id')->unsigned();
            $table->foreign('owner_id')->references('id')->on('owners')->onUpdate('cascade')->onDelete('cascade');
            $table->string('name')->nullable();
            $table->string('code');
            $table->string('url');
            $table->integer('state_id')->unsigned();
            $table->integer('city_id')->unsigned();
            $table->integer('country_id')->unsigned();
            $table->string('pincode',20);
            $table->string('address');
            $table->string('famous_for');
            $table->string('contact_no');
            $table->string('email_id');
            $table->string('service_type');
            $table->integer('avg_cost_of_two')->unsigned();
            $table->integer('takeaway_cost')->unsigned();
            $table->date('established_date');
            $table->string('outlet_image');
            $table->double('lat');
            $table->double('long');
            $table->string('active')->default('1');
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
		Schema::drop('outlets');
	}


}

