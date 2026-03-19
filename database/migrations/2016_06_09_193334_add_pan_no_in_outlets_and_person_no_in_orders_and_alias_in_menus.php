<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPanNoInOutletsAndPersonNoInOrdersAndAliasInMenus extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
    public function up()
    {
        Schema::table('orders', function(Blueprint $table)
        {
            $table->integer("person_no")->nullable()->after('table_no');
        });
        Schema::table('outlets', function(Blueprint $table)
        {
            $table->string('pan_no')->nullable()->after('tin_number');
        });
        Schema::table('menus', function(Blueprint $table)
        {
            $table->string('alias')->nullable()->after('item');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function(Blueprint $table)
        {
            $table->dropColumn("person_no");
        });
        Schema::table('outlets', function(Blueprint $table)
        {
            $table->dropColumn('pan_no');
        });
        Schema::table('menus', function(Blueprint $table)
        {
            $table->dropColumn('alias');
        });
    }

}
