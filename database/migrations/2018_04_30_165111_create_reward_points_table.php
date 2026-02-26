<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRewardPointsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('reward_points', function(Blueprint $table)
		{
			$table->increments('id');
            $table->dateTime('txn_date');
            $table->integer('user_id');
            $table->integer('outlet_id');
            $table->double('debit')->default('0.00');
            $table->double('credit')->default('0.00');
            $table->double('balance')->default('0.00');
            $table->text('desc')->nullable();
            $table->SoftDeletingTrait();
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
		Schema::drop('reward_points');
	}

}
