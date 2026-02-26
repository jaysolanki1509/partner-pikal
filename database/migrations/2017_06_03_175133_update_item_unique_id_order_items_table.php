<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateItemUniqueIdOrderItemsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('order_items', function(Blueprint $table)
		{
            DB::statement('ALTER TABLE `order_items` MODIFY COLUMN `item_unique_id` varchar(255) DEFAULT 0;');

		});

        Schema::table('outlets', function(Blueprint $table)
        {
            $table->string('parse_order_email')->after('email_id')->nullable();
        });

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('order_items', function(Blueprint $table)
		{
			//
		});
	}

}
