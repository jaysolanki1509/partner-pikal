<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class RemoveFieldsFromSourcesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('sources', function(Blueprint $table)
		{
			$table->dropColumn(['outlet_id', 'source_percent']);
			DB::statement('ALTER TABLE `sources` CHANGE `source_name` `name` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ;') ;
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('sources', function(Blueprint $table)
		{
			//
		});
	}

}
