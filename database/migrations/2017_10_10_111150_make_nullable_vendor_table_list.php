<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeNullableVendorTableList extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('vendors', function(Blueprint $table)
		{
            DB::statement('ALTER TABLE `vendors` CHANGE `type` `type` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL ;');
            DB::statement('ALTER TABLE `vendors` CHANGE `contact_person` `contact_person` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL ;');
            DB::statement('ALTER TABLE `vendors` CHANGE `contact_number` `contact_number` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL ;');
            DB::statement('ALTER TABLE `vendors` CHANGE `pincode` `pincode` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL ;');

            $table->index('name');
            $table->index('type');
            $table->index('contact_number');
            $table->index('vendor_gst');
            $table->index('created_by');
            $table->index('updated_by');
            $table->index('country_id');
            $table->index('state_id');
            $table->index('city_id');
            $table->index('pincode');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('vendors', function(Blueprint $table)
		{
			//
		});
	}

}
