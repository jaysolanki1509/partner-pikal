<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

		Schema::create('accounts', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->boolean('enable_inventory')->default(false);
			$table->timestamps();
			$table->SoftDeletingTrait();

			$table->index('name');
			$table->index('enable_inventory');
		});

		Schema::table('owners', function(Blueprint $table)
		{
			$table->integer('account_id')->nullable()->after('id');
			$table->index('account_id');
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
