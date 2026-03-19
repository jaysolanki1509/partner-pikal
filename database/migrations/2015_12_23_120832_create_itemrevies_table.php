<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemreviesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('reviews', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('order_id');
            $table->string('order_created_at');
            $table->integer('rating');
            $table->integer('fav');
            $table->string('item_id');
            $table->string('review');
            $table->string('mobile');
            $table->string('resid');
            $table->string('username');
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
		//
	}

}
