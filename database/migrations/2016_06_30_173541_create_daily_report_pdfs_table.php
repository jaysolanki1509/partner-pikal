<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDailyReportPdfsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('daily_report_pdf_mapper', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('outlet_id');
            $table->string('name');
            $table->string('path');
            $table->timestamps();
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('daily_report_pdf_mapper');
	}

}
