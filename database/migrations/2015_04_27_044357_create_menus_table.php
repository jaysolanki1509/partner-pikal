<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenusTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 **/
	public function up()
	{
		Schema::create('menus', function(Blueprint $table)
		{
			$table->increments('id');
            $table->integer('outlet_id')->unsigned();
            $table->foreign('outlet_id')->references('id')->on('outlets')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('menu_title_id')->unsigned();
            $table->foreign('menu_title_id')->references('id')->on('menu_titles')->onUpdate('cascade')->onDelete('cascade');
            $table->string('item')->nullable();
            $table->string('price',30)->nullable();
            $table->string('details')->nullable();
            $table->integer('cuisine_type_id')->unsigned()->nullable();
            $table->string('options')->nullable();
            $table->string('active');
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
		Schema::drop('menus');
	}

}
