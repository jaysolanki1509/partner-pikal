<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPrinterTypeInPrinters extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('printers', function(Blueprint $table)
		{
			$table->string('printer_type')->default('network');
			$table->string('print_type')->nullable();
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
            $table->dropColumn('print_type');
            $table->dropColumn('printer_type');
		});
	}

}
