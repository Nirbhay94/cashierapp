<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerInvoiceConfigurationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_invoice_configurations', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')
                ->on('users')->onDelete('cascade');

            $table->string('business_name')->nullable();
            $table->string('business_id')->nullable();
            $table->string('business_logo')->nullable();
            $table->string('business_phone')->nullable();
            $table->string('business_location')->nullable();
            $table->string('business_zip')->nullable();
            $table->string('business_city')->nullable();
            $table->string('business_country')->nullable();
            $table->string('business_legal_terms')->nullable();
            $table->string('currency_locale')->default('en_US');
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
        Schema::dropIfExists('customer_invoice_configurations');
    }
}
