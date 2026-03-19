<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDailySummariesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('daily_summaries', function(Blueprint $table)
		{
			$table->increments('id');
            $table->date('report_date');
            $table->integer('outlet_id');
            $table->integer('total_orders');
            $table->double('total_sells');
            $table->double('total_discount');
            $table->double('total_nc_order');
            $table->double('total_taxes');
            $table->double('total_online');
            $table->double('total_cash');
            $table->double('gross_total');
            $table->double('gross_average');
            $table->integer('total_unique_item_sell');
            $table->integer('total_item_sell');
            $table->integer('total_person_visit');
            $table->string('top_selling_item');
            $table->integer('top_selling_item_id');
            $table->double('lowest_order');
            $table->double('highest_order');
            $table->integer('cancel_order_count');
            $table->double('cancel_order_amount');
			$table->timestamps();
            $table->index('report_date');
            $table->index('outlet_id');
            $table->index('total_orders');
            $table->index('total_sells');
            $table->index('total_discount');
            $table->index('total_nc_order');
            $table->index('total_taxes');
            $table->index('total_online');
            $table->index('total_cash');
            $table->index('gross_total');
            $table->index('gross_average');
            $table->index('total_unique_item_sell');
            $table->index('total_item_sell');
            $table->index('total_person_visit');
            $table->index('top_selling_item');
            $table->index('top_selling_item_id');
            $table->index('lowest_order');
            $table->index('highest_order');
            $table->index('cancel_order_count');
            $table->index('cancel_order_amount');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('daily_summaries');
	}

}
