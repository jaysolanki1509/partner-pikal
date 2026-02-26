<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemOptionGroups extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		/*Schema::create('item_option_groups', function(Blueprint $table)
		{
			$table->increments('id');
            $table->integer('outlet_id')->unsigned();
            $table->foreign('outlet_id')->references('id')->on('outlets');
            $table->string('name');
            $table->float('max');
            $table->integer('created_by')->unsigned();
            $table->foreign('created_by')->references('id')->on('owners');
            $table->integer('updated_by')->unsigned();
            $table->foreign('updated_by')->references('id')->on('owners');
            $table->integer('deleted_by')->nullable()->unsigned();
            $table->foreign('deleted_by')->references('id')->on('owners');
            $table->timestamps();
            $table->SoftDeletingTrait();

            $table->index('name');
            $table->index('max');
            $table->index('created_at');
            $table->index('updated_at');
            $table->index('deleted_at');

        });

        Schema::create('item_option_group_mapper', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('item_id')->unsigned();;
            $table->foreign('item_id')->references('id')->on('menus');
            $table->integer('item_option_group_id')->unsigned();
            $table->foreign('item_option_group_id')->references('id')->on('item_option_groups');
            $table->integer('created_by')->unsigned();
            $table->foreign('created_by')->references('id')->on('owners');
            $table->integer('updated_by')->unsigned();
            $table->foreign('updated_by')->references('id')->on('owners');
            $table->timestamps();

            $table->index('created_at');
            $table->index('updated_at');
        });

        Schema::create('item_group_options', function(Blueprint $table)
        {
            $table->increments('id');

            $table->integer('item_option_group_id')->unsigned();
            $table->foreign('item_option_group_id')->references('id')->on('item_option_groups');
            $table->integer('option_item_id')->unsigned();
            $table->foreign('option_item_id')->references('id')->on('menus');
            $table->float('option_item_price');
            $table->boolean('default_option')->default(0);
            $table->integer('created_by')->unsigned();
            $table->foreign('created_by')->references('id')->on('owners');
            $table->integer('updated_by')->unsigned();
            $table->foreign('updated_by')->references('id')->on('owners');
            $table->timestamps();

            $table->index('default_option');
            $table->index('created_at');
            $table->index('updated_at');

        });*/
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('item_option_groups', function(Blueprint $table)
		{
			//
		});
	}

}
