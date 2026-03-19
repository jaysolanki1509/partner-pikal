<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddItemwiseDiscountMenuTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('menus', function(Blueprint $table)
		{
			$table->string('discount_type')->nullable()->after('tax_slab');
            $table->float('discount_value')->nullable('discount_type')->after('discount_type');

            $table->index('discount_type');
            $table->index('discount_value');

		});
        Schema::table('orders', function(Blueprint $table)
        {
            $table->boolean('itemwise_discount')->default(0);
        });
        Schema::table('order_items', function(Blueprint $table)
        {
            $table->string('item_discount')->after('tax_slab')->nullable();
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('menus', function(Blueprint $table)
		{
			//
		});
	}

}
