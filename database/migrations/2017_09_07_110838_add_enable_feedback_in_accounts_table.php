<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEnableFeedbackInAccountsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('accounts', function(Blueprint $table)
		{
			$table->boolean("enable_feedback")->after("enable_cancellation_report")->default(0);

            $table->index("active");
            $table->index("enable_cancellation_report");
            $table->index("enable_feedback");
            $table->index("allow_order_delete");
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('accounts', function(Blueprint $table)
		{
			$table->dropColumn("enable_feedback");
		});
	}

}
