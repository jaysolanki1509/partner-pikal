<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsInventoryCategoryInCategoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
    public function up()
    {
        Schema::table('menu_titles', function(Blueprint $table)
        {
            $table->boolean("is_inventory_category")->after("active");
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
            $table->dropColumn('is_inventory_category')->after("active");
        });
    }

}
