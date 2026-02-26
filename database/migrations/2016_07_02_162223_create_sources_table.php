<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSourcesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sources', function(Blueprint $table)
		{
			$table->increments('id');
            $table->integer('outlet_id');
            $table->string('source_name');
            $table->float('source_percent');
            $table->integer('created_by')->unsigned();
            $table->integer('updated_by')->unsigned();
            $table->foreign('created_by')->references('id')->on('owners')->nullable();
            $table->foreign('updated_by')->references('id')->on('owners')->nullable();
			$table->timestamps();
            $table->softDeletes();
            $table->index('outlet_id');
            $table->index('source_name');
            $table->index('created_by');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('sources');
	}

}
