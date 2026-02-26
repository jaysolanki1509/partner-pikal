<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCreatedByPrintersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('printers', function(Blueprint $table)
		{
			$table->integer('created_by')->after('mac_address');
			$table->integer('updated_by')->after('created_by');
			$table->index('created_by');
			$table->index('updated_by');
			DB::statement('ALTER TABLE `printers` CHANGE `print_type` `print_type` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL ;');
			DB::statement('ALTER TABLE `printers` CHANGE `outlet_id` `outlet_id` INT( 11 ) NULL ;');
			//$table->dropColumn('print_type');
			//$table->dropColumn('outlet_id');

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('printers', function(Blueprint $table)
		{
			//
		});
	}

}
