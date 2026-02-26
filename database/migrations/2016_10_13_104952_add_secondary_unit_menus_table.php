<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSecondaryUnitMenusTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('menus', function(Blueprint $table)
		{
			$table->string('secondary_units')->nullable();
			DB::statement('ALTER TABLE `menus` CHANGE `unit_id` `unit_id` INT( 11 ) NOT NULL ;');
			$table->index('alias');
			$table->index('is_sell');
			$table->index('discountable');
			$table->index('taxable');
			$table->index('created_by');
			$table->index('updated_by');
			$table->index('brand');
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
