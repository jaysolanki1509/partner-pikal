<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIciciUpiTransactionTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('icici_upi_transaction', function (Blueprint $table) {
            $table->bigIncrements('txnid');
            $table->integer('merchant_id');
            $table->string('merchant_name', 50);
            $table->integer('sub_merchant_id');
            $table->string('sub_merchant_name', 50);
            $table->integer('terminal_id');
            $table->string('bill_no', 50);
            $table->decimal('amount', 20, 2);
            $table->string('payer_va', 255);
            $table->string('note', 50)->nullable();
            $table->dateTime('collect_date');
            $table->smallInteger('status')->default(0); // 0 - pending, 1 - success, 2 - failure
            $table->timestamps();

            $table->index(array('collect_date'));
            $table->index(array('payer_va'));
            $table->index(array('sub_merchant_id'));
            $table->index(array('terminal_id'));
            $table->index(array('bill_no'));
        });

        Schema::create('icici_upi_transaction_logs', function (Blueprint $table) {
            $table->bigInteger('txnid');
            $table->string('response', 10);
            $table->integer('merchant_id');
            $table->integer('sub_merchant_id');
            $table->integer('terminal_id');
            $table->string('success', 10);
            $table->string('message', 255)->nullable();
            $table->string('bank_rrn', 50);
            $table->string('payer_name', 50);
            $table->string('payer_mobile', 50);
            $table->string('payer_va', 50);
            $table->decimal('payer_amount', 20, 2);
            $table->string('status', 15);
            $table->string('txn_init_date', 15);
            $table->string('txn_complete_date', 15);
            $table->timestamps();

            $table->index(array('txnid'));
            $table->index(array('payer_va'));
            $table->index(array('sub_merchant_id'));
            $table->index(array('terminal_id'));
        });

        Schema::create('icici_upi_transaction_response', function (Blueprint $table) {
            $table->bigInteger('txnid');
            $table->string('request', 20);
            $table->text('response');
            $table->timestamps();

            $table->index(array('txnid'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('icici_upi_transaction');
        Schema::drop('icici_upi_transaction_logs');
    }
}
