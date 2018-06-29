<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePosTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pos_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('details')->nullable();

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')
                ->on('users')->onDelete('cascade');

            $table->integer('customer_id')->unsigned();
            $table->foreign('customer_id')->references('id')
                ->on('customers')->onDelete('cascade');

            $table->integer('customer_invoice_id')->unsigned()->nullable();
            $table->foreign('customer_invoice_id')->references('id')
                ->on('customer_invoices')->onDelete('cascade');

            $table->longText('items')->nullable();
            $table->string('product_ids')->nullable();

            $table->double('discount')->nullable();
            $table->double('sub_total')->nullable();
            $table->double('total')->nullable();

            $table->double('tax')->nullable();
            $table->string('tax_ids')->nullable();

            $table->string('note')->nullable();
            $table->enum('status', ['hold', 'invoice', 'completed'])->default('hold');

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
        Schema::dropIfExists('pos_transactions');
    }
}
