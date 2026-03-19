<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMfgAndIpInPrintersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('printers', function(Blueprint $table)
		{
			$table->string('printer_ip')->after('printer_name')->nullable();;
			$table->string('printer_mfg')->after('mac_address')->nullable();;
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('printers', function(Blueprint $table)
		{
            $table->dropColumn('printer_ip');
            $table->dropColumn('printer_mfg');
		});
	}

}
