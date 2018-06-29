<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_invoices', function (Blueprint $table) {
            $table->increments('id');

            $table->string('token');

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')
                ->on('users')->onDelete('cascade');

            $table->string('transaction_id')->nullable();

            $table->enum('status', ['paid', 'partial', 'unpaid'])
                ->default('unpaid');

            $table->integer('customer_id')->unsigned();
            $table->foreign('customer_id')->references('id')
                ->on('customers')->onDelete('cascade');

            $table->enum('allow_partial', ['yes', 'no'])->default('no');
            $table->string('payment_processor')->nullable();

            $table->longText('items');
            $table->double('discount')->nullable();
            $table->double('sub_total');
            $table->double('tax')->default(0);
            $table->double('total');
            $table->string('tax_ids')->nullable();
            $table->string('product_ids')->nullable();
            $table->string('note')->nullable();
            $table->double('amount_paid')->default(0);
            $table->string('repeat_data')->nullable();
            $table->timestamp('repeat_until')->nullable();
            $table->timestamp('last_repeat')->nullable();

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
        Schema::dropIfExists('customer_invoices');
    }
}
