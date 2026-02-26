<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAppviewToOutletTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('outlets', function(Blueprint $table)
		{
            $table->string('app_layout')->default("tabbed_layout")->after('delivery_numbers');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('outlets', function(Blueprint $table)
        {
            $table->dropColumn('app_layout');
        });
	}

}
