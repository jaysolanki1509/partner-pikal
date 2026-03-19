<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeedbackDesignTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('feedback', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('outlet_id');
            $table->string('field_name');
            $table->string('field_type');
            $table->string('line_value')->nullable();
            $table->string('option_value')->nullable();
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
        Schema::drop('feedback');
	}

}
