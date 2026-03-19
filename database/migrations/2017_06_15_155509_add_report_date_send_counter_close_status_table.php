<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReportDateSendCounterCloseStatusTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('send_close_counter_status', function(Blueprint $table)
		{
			$table->date('report_date')->nullable()->after('close_date');
            $table->index('report_date');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('send_close_counter_status', function(Blueprint $table)
		{
			//
		});
	}

}
