<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->increments('id');

            $table->string('images')->nullable();
            $table->string('name');
            $table->string('company');
            $table->string('email')->nullable();
            $table->string('phone_number')->nullable();

            $table->string('gst_number')->nullable();

            $table->longText('location')->nullable();
            $table->string('city')->nullable();
            $table->string('zip')->nullable();
            $table->string('country')->nullable();

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')
                ->on('users')->onDelete('cascade');

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
        Schema::dropIfExists('suppliers');
    }
}
