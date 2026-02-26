<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCategoryIdAndNameInOrderItems extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('order_items', function(Blueprint $table)
        {
            $table->integer('categort_id')->nullable();
            $table->text('category_name')->nullable();
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
            $table->dropColumn('categort_id');
            $table->dropColumn('category_name');
        });
	}

}
