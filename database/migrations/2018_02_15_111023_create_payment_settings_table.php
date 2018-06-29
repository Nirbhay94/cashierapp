<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('amount_init')->default(20);
            $table->integer('amount_inc')->default(10);
            $table->integer('amount_max')->default(100);
            $table->string('business_name')->nullable();
            $table->string('business_id')->nullable();
            $table->string('business_logo')->nullable();
            $table->string('business_phone')->nullable();
            $table->string('business_location')->nullable();
            $table->string('business_zip')->nullable();
            $table->string('business_city')->nullable();
            $table->string('business_country')->nullable();
            $table->string('business_legal_terms')->nullable();
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
        Schema::dropIfExists('payment_settings');
    }
}
