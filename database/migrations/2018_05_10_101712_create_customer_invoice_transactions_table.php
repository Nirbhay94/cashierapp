<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerInvoiceTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_invoice_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('details')->nullable();

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')
                ->on('users')->onDelete('cascade');

            $table->integer('customer_id')->unsigned();
            $table->foreign('customer_id')->references('id')
                ->on('customers')->onDelete('cascade');

            $table->integer('customer_invoice_id')->unsigned();
            $table->foreign('customer_invoice_id')->references('id')
                ->on('customer_invoices')->onDelete('cascade');

            $table->double('amount')->nullable();
            $table->string('processor')->nullable();

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
        Schema::dropIfExists('customer_invoice_transactions');
    }
}
