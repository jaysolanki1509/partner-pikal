<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressTableAll extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

        Schema::create('address', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('state');
            $table->string('city');
            $table->string('country');
            $table->string('address_tag');
            $table->string('user_mobile_number');
            $table->string('address');
            $table->string('locality');
            $table->string('pincode',10);
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
        Schema::drop('address');
	}

}
