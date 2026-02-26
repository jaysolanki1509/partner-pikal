<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexesToTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('orders', function(Blueprint $table)
		{
			//DB::statement("ALTER TABLE orders DROP INDEX order_unique_id_2;");
			/*$table->index('reset');
			$table->index('source_id');
			$table->index('updated');

			$table->index('invoice');*/
			$table->index('invoice_no');
		});

		Schema::table('order_items', function(Blueprint $table)
		{
			DB::statement("ALTER TABLE  `order_items` CHANGE  `category_name`  `category_name` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ;");

			$table->index('order_id');
			$table->index('item_id');
			$table->index('item_unique_id');
			$table->index('item_name');
			$table->index('item_quantity');
			$table->index('item_price');
			$table->index('item_total');
			$table->index('created_at');
			$table->index('updated_at');
			$table->index('category_id');
			$table->index('category_name');
		});

		Schema::drop('recipes');

		Schema::table('recipeDetails', function(Blueprint $table)
		{
			$table->index('owner_id');
			$table->index('outlet_id');
			$table->index('menu_title_id');
			$table->index('menu_item_id');
			$table->index('recipe_name');
			$table->index('referance');
			$table->index('created_at');
			$table->index('updated_at');
			$table->index('updated_by');
			$table->index('deleted_at');
		});

		Schema::table('ingredients', function(Blueprint $table)
		{
			$table->index('recipeDetails_id');
			$table->index('ing_item_id');
			$table->index('qty');
			$table->index('created_at');
			$table->index('updated_at');
		});

		Schema::table('menus', function(Blueprint $table)
		{
			$table->index('item');
			$table->index('item_order');
			$table->index('price');
			$table->index('unit_id');
			$table->index('cuisine_type_id');
			$table->index('active');
			$table->index('created_at');
			$table->index('updated_at');
			$table->index('food');
			$table->index('like');
			$table->index('user_like_mobile_number');

			$table->index('deleted_at');
			$table->index('buy_price');
			$table->index('secondary_units');
		});

		Schema::table('menu_titles', function(Blueprint $table)
		{
			$table->index('title_order');
			$table->index('active');
			$table->index('created_at');
			$table->index('updated_at');
			$table->index('food');
		});

		Schema::table('stocks', function(Blueprint $table)
		{
			$table->index('quantity');
			$table->index('created_at');
			$table->index('updated_at');
			$table->index('deleted_at');
		});
		Schema::table('stock_history', function(Blueprint $table)
		{
			$table->index('quantity');
			$table->index('created_at');
			$table->index('updated_at');
			$table->index('deleted_at');
			$table->index('type');
			$table->index('reason');
		});

		Schema::table('consumptions', function(Blueprint $table)
		{
			$table->index('consume');
			$table->index('created_at');
			$table->index('updated_at');
		});

		Schema::table('unit', function(Blueprint $table)
		{
			$table->index('name');
			$table->index('created_at');
			$table->index('updated_at');
		});

		Schema::table('item_request', function(Blueprint $table)
		{
			$table->index('what_item_id');
			$table->index('created_at');
			$table->index('updated_at');
			$table->index('what_item');
			$table->index('owner_to');
			$table->index('owner_by');
			$table->index('when');
			$table->index('qty');
			$table->index('existing_qty');
			$table->index('satisfied');
			$table->index('satisfied_by');
			$table->index('satisfied_when');
			$table->index('statisfied_qty');
			$table->index('location_for');
			$table->index('location_from');
		});

		Schema::table('item_settings', function(Blueprint $table) {
			$table->index('outlet_id');
			$table->index('item_id');
			$table->index('is_active');
			$table->index('is_sale');
			$table->index('created_by');
			$table->index('updated_by');
			$table->index('created_at');
			$table->index('updated_at');
		});

		Schema::table('kot', function(Blueprint $table) {
			$table->index('order_unique_id');
			$table->index('table_no');
			$table->index('person_no');
			$table->index('price');
			$table->index('quantity');
			$table->index('status');
			$table->index('reason');
			$table->index('kot_time');
			$table->index('created_at');
			$table->index('updated_at');
			$table->index('deleted_at');
			$table->index('print_count');
		});

		Schema::table('locations', function(Blueprint $table) {
			$table->index('name');
			$table->index('created_by');
			$table->index('updated_by');
			$table->index('created_at');
			$table->index('updated_at');
			$table->index('deleted_at');
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
