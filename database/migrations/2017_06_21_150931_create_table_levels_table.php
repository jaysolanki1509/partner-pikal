<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableLevelsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('table_levels', function(Blueprint $table)
		{
            $table->increments('id');
            $table->string('name');
            $table->integer('outlet_id');
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->integer('deleted_by')->nullable();
            $table->timestamps();
            $table->SoftDeletingTrait();

            $table->index('outlet_id');
            $table->index('created_by');
            $table->index('updated_by');
            $table->index('deleted_by');
            $table->index('created_at');
            $table->index('updated_at');
            $table->index('deleted_at');
		});

        Schema::table('tables', function(Blueprint $table)
        {
            $table->integer('table_level_id')->default(0)->after('outlet_id');
            $table->index('table_level_id');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('table_levels', function(Blueprint $table)
		{
			//
		});
	}

}
