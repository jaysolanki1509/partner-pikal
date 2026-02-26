<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class DropeOrderReceiveFromOwnersAndAddToOutletbind extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('owners', function(Blueprint $table)
        {
            $table->dropColumn('order_receive');
        });
        Schema::table('outlets_mapper', function(Blueprint $table)
        {
            $table->string('order_receive',500)->nullable()->after('owner_id');
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
