<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreaditNotesTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('credit_notes', function(Blueprint $table)
		{
			$table->increments('id');
            $table->string('cn_unique_id');
            $table->string('cn_no');
            $table->integer('number');
            $table->integer('outlet_id')->unsigned();
            $table->foreign('outlet_id')->references('id')->on('outlets');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('order_id')->unsigned();
            $table->foreign('order_id')->references('order_id')->on('orders');;
            $table->string('invoice_no');
            $table->string('reference')->nullable();
            $table->integer('state_id');
            $table->date('date_of_issue');
            $table->date('expiry_date')->nullable();
            $table->enum('status',['open', 'closed']);
            $table->string('reason')->nullable();
            $table->string('taxes')->nullable();
            $table->float('discount')->nullable();
            $table->string('discount_type')->nullable();
            $table->float('item_discount')->nullable();
            $table->float('round_off');
            $table->float('sub_total');
            $table->float('total');
            $table->float('credit_used')->nullable();
            $table->float('available_credit')->nullable();
            $table->boolean('is_itemwise_tax')->default(0);
            $table->boolean('is_itemwise_discount')->default(0);
            $table->boolean('is_discount_after_tax')->default(0);
            $table->string('customer_notes')->nullable();
            $table->string('term_conditions')->nullable();
            $table->integer('created_by')->unsigned();
            $table->foreign('created_by')->references('id')->on('owners');
            $table->integer('updated_by')->unsigned();
            $table->foreign('updated_by')->references('id')->on('owners');
            $table->integer('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();


            $table->index('cn_unique_id');
            $table->index('cn_no');
            $table->index('number');
            $table->index('outlet_id');
            $table->index('user_id');
            $table->index('order_id');
            $table->index('invoice_no');
            $table->index('reference');
            $table->index('state_id');
            $table->index('date_of_issue');
            $table->index('expiry_date');
            $table->index('status');
            $table->index('discount');
            $table->index('discount_type');
            $table->index('round_off');
            $table->index('sub_total');
            $table->index('total');
            $table->index('credit_used');
            $table->index('available_credit');
            $table->index('is_itemwise_tax');
            $table->index('is_itemwise_discount');
            $table->index('is_discount_after_tax');
            $table->index('created_by');
            $table->index('updated_by');
            $table->index('deleted_by');
            $table->index('created_at');
            $table->index('updated_at');
            $table->index('deleted_at');

		});

        Schema::create('credit_note_items', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('cn_id')->unsigned();
            $table->foreign('cn_id')->references('id')->on('credit_notes');
            $table->integer('hsn_code')->nullable();
            $table->integer('item_id')->unsigned();
            $table->foreign('item_id')->references('id')->on('menus');
            $table->string('item_name');
            $table->string('item_unique_id');
            $table->float('item_qty');
            $table->float('item_price');
            $table->float('item_total');
            $table->string('tax');
            $table->string('discount');
            $table->integer('category_id');
            $table->string('category_name');
            $table->integer('deleted_by')->nullable();
            $table->softDeletes();

            $table->index('cn_id');
            $table->index('hsn_code');
            $table->index('item_id');
            $table->index('item_name');
            $table->index('item_unique_id');
            $table->index('item_qty');
            $table->index('item_price');
            $table->index('item_total');
            $table->index('category_id');
            $table->index('category_name');
            $table->index('deleted_by');
            $table->index('deleted_at');


        });

        Schema::create('credit_note_order_mapper', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('cn_id')->unsigned();
            $table->foreign('cn_id')->references('id')->on('credit_notes');
            $table->integer('order_id')->unsigned();
            $table->foreign('order_id')->references('order_id')->on('orders');
            $table->float('credited_amount');
            $table->integer('created_by')->unsigned();
            $table->foreign('created_by')->references('id')->on('owners');
            $table->integer('updated_by')->unsigned();
            $table->foreign('updated_by')->references('id')->on('owners');
            $table->integer('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('cn_id');
            $table->index('order_id');
            $table->index('credited_amount');
            $table->index('created_by');
            $table->index('updated_by');
            $table->index('deleted_by');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::drop('credit_notes');
        Schema::drop('credit_note_items');
        Schema::drop('credit_note_order_mapper');
	}

}
