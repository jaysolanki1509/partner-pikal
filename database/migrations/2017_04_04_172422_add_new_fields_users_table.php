<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewFieldsUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users', function(Blueprint $table)
		{
			DB::statement("ALTER TABLE `users` CHANGE `gender` `gender` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;");
			DB::statement("ALTER TABLE `users` CHANGE `otp` `otp` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;");
			DB::statement("ALTER TABLE `users` CHANGE `status` `status` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;");
			DB::statement("ALTER TABLE `users` CHANGE `device_id` `device_id` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;");
			DB::statement("ALTER TABLE `users` CHANGE `image` `image` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;");
			DB::statement("ALTER TABLE `users` CHANGE `last_name` `last_name` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;");
			DB::statement("ALTER TABLE `users` CHANGE `last_name` `last_name` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;");
			DB::statement("ALTER TABLE `users` CHANGE `email` `email` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;");
			DB::statement("ALTER TABLE `users` CHANGE `state` `state` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;");
			DB::statement("ALTER TABLE `users` CHANGE `city` `city` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;");
			DB::statement("ALTER TABLE `users` CHANGE `country` `country` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;");
			DB::statement("ALTER TABLE `users` CHANGE `password` `password` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;");

			$table->text('address')->nullable()->after('country');
			$table->date('dob')->nullable()->after('gender');
			$table->date('doa')->nullable()->after('dob');
			$table->tinyInteger('married')->default(0)->after('doa');
			$table->string('spouse_name')->nullable()->after('married');
			$table->bigInteger('spouse_number')->nullable()->after('spouse_name');

			$table->index('email');
			$table->index('state');
			$table->index('city');
			$table->index('mobile_number');
			$table->index('gender');
			$table->index('status');
			$table->index('device_id');
			$table->index('created_at');
			$table->index('updated_at');
			$table->index('dob');
			$table->index('doa');
			$table->index('married');
			$table->index('spouse_number');

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('users', function(Blueprint $table)
		{
			//
		});
	}

}
