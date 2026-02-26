<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankMasterTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('bank_master', function(Blueprint $table)
		{
            $table->increments('id');
            $table->string('owner_id');
            $table->string('acc_no');
            $table->string('bank_name');
            $table->string('acc_type');
            $table->string('bank_ifsc');
            $table->string('bank_address');
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->index('created_by');
            $table->index('updated_by');
            $table->timestamps();
            $table->SoftDeletingTrait();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::drop('bank_master');
	}

}
