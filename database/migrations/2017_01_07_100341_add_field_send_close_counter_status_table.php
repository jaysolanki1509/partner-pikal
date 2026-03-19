<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldSendCloseCounterStatusTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('send_close_counter_status', function(Blueprint $table)
		{
			$table->float('total_cash')->nullable()->after('amount');
			$table->float('total_online')->nullable()->after('total_cash');
			$table->float('total_cheque')->nullable()->after('total_online');

			$table->index('sms_count');
			$table->index('is_send');
			$table->index('created_at');
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
