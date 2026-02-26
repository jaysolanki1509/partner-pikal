<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateColumnRequestsItemRequestTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('item_request', function(Blueprint $table)
		{
            $table->decimal('qty', 7, 2)->change();
            $table->decimal('existing_qty', 7, 2)->change();
            $table->decimal('statisfied_qty', 7, 2)->nullable()->change();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('item_request', function(Blueprint $table)
		{
			//
		});
	}

}
