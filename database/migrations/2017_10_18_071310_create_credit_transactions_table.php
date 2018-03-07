<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreditTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit_transactions', function (Blueprint $table) {

            /**
             * Auto
             */

            $table->increments('id');
            $table->timestamps();

            /**
             * Main
             */

            $table->integer('amount');


            /**
             * Relationship
             */

            $table->integer('credit_transaction_type_id')->unsigned();
            $table->foreign('credit_transaction_type_id')->references('id')->on('credit_transaction_types');

            $table->foreign('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->integer('message_receipt_id')->unsigned()->nullable();
            $table->foreign('message_receipt_id')->references('id')->on('message_receipts');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('credit_transactions');
    }
}
