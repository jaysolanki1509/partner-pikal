<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('campaign', function(Blueprint $table)
		{
			$table->increments('id');
			$table->bigInteger('mobile');
			$table->boolean('verified')->default(0);
			$table->string('otp')->nullable();
			$table->string('outlet_name')->nullable();
			$table->string('owner_name')->nullable();
			$table->string('email')->nullable();
			$table->text('address')->nullable();
			$table->text('path')->nullable();
			$table->timestamps();
			$table->SoftDeletingTrait();

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('campaign', function(Blueprint $table)
		{
			//
		});
	}

}
