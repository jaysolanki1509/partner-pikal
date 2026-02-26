<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeedbackQuestionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('feedback_questions', function(Blueprint $table)
		{
            $table->increments('id');
            $table->integer('outlet_id');
            $table->string('question');
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->integer('deleted_by')->nullable();
            $table->timestamps();
            $table->SoftDeletingTrait();

            $table->index('outlet_id');
            $table->index('created_by');
            $table->index('updated_by');
            $table->index('deleted_by');
            $table->index('created_at');
            $table->index('updated_at');
            $table->index('deleted_at');
		});

        Schema::create('customer_feedbacks', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('order_id')->nullable();
            $table->integer('outlet_id');
            $table->integer('question_id');
            $table->integer('value');
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->integer('deleted_by')->nullable();
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
		Schema::table('feedback_questions', function(Blueprint $table)
		{
			//
		});
	}

}
