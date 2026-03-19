<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentmasterTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paymentmaster', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('txn_id')->nullable();
            $table->string('payment_id')->nullable();
            $table->string('provider_name')->nullable();
            $table->float('txn_amount');
            $table->string('bank_txn_id')->nullable();
            $table->string('txn_type')->nullable();
            $table->string('currency')->nullable();
            $table->string('gateway_name')->nullable();
            $table->string('response_code')->nullable();
            $table->string('response_msg')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('merchant_id')->nullable();
            $table->string('payment_mode')->nullable();
            $table->float('refund_amount');
            $table->date('txn_date');
            $table->string('txn_status')->nullable();
            $table->boolean('check_valid');

            $table->index('txn_id');
            $table->index('payment_id');
            $table->index('provider_name');
            $table->index('txn_amount');
            $table->index('bank_txn_id');
            $table->index('txn_type');
            $table->index('currency');
            $table->index('gateway_name');
            $table->index('response_code');
            $table->index('response_msg');
            $table->index('bank_name');
            $table->index('merchant_id');
            $table->index('payment_mode');
            $table->index('refund_amount');
            $table->index('txn_date');
            $table->index('txn_status');
            $table->index('check_valid');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('paymentmaster');
    }

}
