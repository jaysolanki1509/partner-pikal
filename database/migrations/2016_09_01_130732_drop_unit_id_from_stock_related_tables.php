<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class DropUnitIdFromStockRelatedTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('stocks', function(Blueprint $table)
        {
            $table->dropColumn('unit_id');
        });
        Schema::table('stock_age', function(Blueprint $table)
        {
            $table->dropColumn('unit_id');
        });
        Schema::table('stock_history', function(Blueprint $table)
        {
            $table->dropColumn('unit_id');
        });
        Schema::table('stock_level', function(Blueprint $table)
        {
            $table->dropColumn('unit_id');
        });
        Schema::table('consumptions', function(Blueprint $table)
        {
            $table->dropColumn('unit_id');
        });
        Schema::table('ingredients', function(Blueprint $table)
        {
            $table->dropColumn('unit_id');
        });
        Schema::table('purchases', function(Blueprint $table)
        {
            $table->dropColumn('unit_id');
        });
        Schema::table('recipeDetails', function(Blueprint $table)
        {
            $table->dropColumn('unit_id');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('stocks', function(Blueprint $table)
        {
            $table->integer('unit_id');
        });
        Schema::table('stock_age', function(Blueprint $table)
        {
            $table->integer('unit_id');
        });
        Schema::table('stock_history', function(Blueprint $table)
        {
            $table->integer('unit_id');
        });
        Schema::table('stock_level', function(Blueprint $table)
        {
            $table->integer('unit_id');
        });
        Schema::table('consumptions', function(Blueprint $table)
        {
            $table->integer('unit_id');
        });
        Schema::table('ingredients', function(Blueprint $table)
        {
            $table->integer('unit_id');
        });
        Schema::table('purchases', function(Blueprint $table)
        {
            $table->integer('unit_id');
        });
        Schema::table('recipeDetails', function(Blueprint $table)
        {
            $table->integer('unit_id');
        });
	}

}
