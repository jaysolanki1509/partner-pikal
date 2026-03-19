<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpenseTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('expense', function(Blueprint $table)
		{
            $table->increments('id');
            $table->integer('expense_for');
            $table->integer('created_by');
            $table->string('created_date')->nullable();
            $table->integer('expense_by');
            $table->integer('expense_to');
            $table->double('amount', 8, 2);
            $table->longText('description');
            $table->boolean('verify')->default(0);
            $table->string('expense_date')->nullable();
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
        Schema::drop('expense');
	}

}
