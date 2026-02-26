<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddZohoConfigOutletsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('outlets', function(Blueprint $table)
		{
            $table->boolean('zoho_config')->default(0);
			$table->text('payment_option_identifier')->nullable();
            $table->string('zoho_username')->nullable();
            $table->string('zoho_password')->nullable();
            $table->index('zoho_config');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('outlets', function(Blueprint $table)
		{
			//
		});
	}

}
