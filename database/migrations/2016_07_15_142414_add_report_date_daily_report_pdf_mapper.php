<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReportDateDailyReportPdfMapper extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('daily_report_pdf_mapper', function(Blueprint $table)
		{
			$table->date('report_date')->after('path');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('daily_report_pdf_mapper', function(Blueprint $table)
		{
			//
		});
	}

}
