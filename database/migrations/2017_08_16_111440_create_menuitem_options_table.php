<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuitemOptionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
            Schema::create('menuitem_options',function(Blueprint $table)
            {
                $table->increments('id');
                $table->integer('parent_item_id');
                $table->integer('option_item_id');
                $table->float('option_item_price');
                $table->integer('created_by');
                $table->integer('updated_by');
                $table->timestamps();
                $table->SoftDeletingTrait();

                $table->index('parent_item_id');
                $table->index('option_item_id');
                $table->index('created_by');
                $table->index('updated_by');
            });
	
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('menuitem_options', function(Blueprint $table)
		{
			//
		});
	}

}
