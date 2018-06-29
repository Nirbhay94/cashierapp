<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_reports', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')
                ->on('users')->onDelete('cascade');

            $table->double('total')->default(0);
            $table->integer('purchases')->default(0);
            $table->integer('profit')->default(0);
            $table->double('tax')->default(0);

            $table->timestamp('date');

            $table->integer('pos_transaction_id')->unsigned()->nullable();
            $table->foreign('pos_transaction_id')->references('id')
                ->on('pos_transactions')->onDelete('cascade');

            $table->integer('customer_id')->unsigned()->nullable();
            $table->foreign('customer_id')->references('id')
                ->on('customers')->onDelete('cascade');

            $table->integer('customer_invoice_id')->unsigned()->nullable();
            $table->foreign('customer_invoice_id')->references('id')
                ->on('customer_invoices')->onDelete('cascade');

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
        Schema::dropIfExists('sale_reports');
    }
}
