<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCerifiedAtStatusNotesCategoryIdInExpenseTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('expense', function(Blueprint $table)
		{
			$table->dateTime('verified_at')->nullable();
            $table->enum('status', ['entered','verified','paid','canceled'])->after('description');
            $table->string('notes')->after('status')->nullable();
            $table->string('guid')->nullable()->after('id');
			$table->integer('category_id')->after('guid');

			$table->index('verified_at');
			$table->index('status');
			$table->index('category_id');
			$table->index('guid');
		});

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('expense', function(Blueprint $table)
		{
            $table->dropColumn('verified_at');
            $table->dropColumn('status');
            $table->dropColumn('notes');
            $table->dropColumn('payment_option_id');
            $table->dropColumn('guid');
		});
	}

}
