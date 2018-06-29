<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaypalCredentialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paypal_credentials', function (Blueprint $table) {
            $table->increments('id');

            $table->boolean('enable')->default(0);

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')
                ->on('users')->onDelete('cascade');

            $table->string('currency')->nullable();
            $table->enum('mode', ['sandbox', 'production'])->default('sandbox');
            $table->string('client_id')->nullable();
            $table->string('client_secret')->nullable();

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
        Schema::dropIfExists('paypal_credentials');
    }
}
