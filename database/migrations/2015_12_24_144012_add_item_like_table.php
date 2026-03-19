<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddItemLikeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('item_like', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('res_id');
            $table->string('item_id');
            $table->integer('like');
            $table->integer('user_mobile_number');
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
