<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsSaleInMenuTitlesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('menu_titles', function(Blueprint $table)
		{
			$table->boolean('is_sale')->default(0)->after('title_order');
            $table->index('is_sale');
            $table->index('outlet_id');
            $table->index('created_by');
            $table->index('title');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('menu_titles', function(Blueprint $table)
		{
			$table->removeColumn('is_sale');
		});
	}

}
