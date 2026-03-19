<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTaxSlabInMenusTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('menus', function(Blueprint $table)
		{
            $table->string('tax_slab')->after('active')->nullable();
            $table->string('hsn_sac_code')->after('tax_slab')->nullable();
            $table->index('tax_slab');
            $table->index('hsn_sac_code');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('menus', function(Blueprint $table)
		{
            $table->dropColumn('tax_slab');
            $table->dropColumn('hsn_sac_code');
        });
	}

}
