<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStateCodeInStatesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('states', function(Blueprint $table)
		{
            $table->integer('country_id')->unsigned()->after('id');
            $table->foreign('country_id')->references('id')->on('countries');
            $table->string('state_code')->after('id')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('states', function(Blueprint $table)
		{
			$table->dropColumn('country_id');
			$table->dropColumn('state_code');
		});
	}

}
