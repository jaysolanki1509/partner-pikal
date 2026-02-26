<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendorsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('owners', function(Blueprint $table)
		{
			$table->SoftDeletingTrait();
		});
		Schema::create('vendors', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->string('type');
			$table->string('contact_person');
			$table->string('contact_number');
			$table->string('address');
			$table->integer('created_by')->unsigned();
			$table->integer('updated_by')->unsigned();
			$table->foreign('created_by')->references('id')->on('owners')->nullable();
			$table->foreign('updated_by')->references('id')->on('owners')->nullable();
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
		//
	}

}
