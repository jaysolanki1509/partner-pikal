<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGuestsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('guests', function(Blueprint $table)
		{
			$table->increments('id');
            $table->integer('outlet_id')->unsigned();
            $table->foreign('outlet_id')->references('id')->on('outlets');
            $table->string('guest_no');
            $table->integer('salutation_id')->unsigned()->nullable();
            $table->foreign('salutation_id')->references('id')->on('salutations');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('gender',10);
            $table->date('dob');
            $table->string('email',100);
            $table->string('mobile',15);
            $table->string('id_proof');
            $table->string('id_number',50)->nullable();
            $table->text('address')->nullable();
            $table->integer('country_id')->unsigned()->nullable();
            $table->foreign('country_id')->references('id')->on('countries');
            $table->integer('state_id')->unsigned()->nullable();
            $table->foreign('state_id')->references('id')->on('states');
            $table->integer('city_id')->unsigned()->nullable();
            $table->foreign('city_id')->references('id')->on('cities');
            $table->string('pincode',10)->nullable();
            $table->string('fax',15)->nullable();
            $table->string('phone',15)->nullable();
            $table->string('guest_preferences')->nullable();
            $table->boolean('vip')->default(0);
            $table->boolean('blacklisted')->default(0);
            $table->integer('created_by')->unsigned();
            $table->foreign('created_by')->references('id')->on('owners');
            $table->integer('updated_by')->unsigned();
            $table->foreign('updated_by')->references('id')->on('owners');
            $table->integer('deleted_by')->unsigned()->nullable();
            $table->foreign('deleted_by')->references('id')->on('owners');
            $table->softDeletes();
            $table->timestamps();

            $table->index('guest_no');
            $table->index('first_name');
            $table->index('last_name');
            $table->index('gender');
            $table->index('dob');
            $table->index('email');
            $table->index('mobile');
            $table->index('id_proof');
            $table->index('id_number');
            $table->index('pincode');
            $table->index('fax');
            $table->index('phone');

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('guests');
	}

}
