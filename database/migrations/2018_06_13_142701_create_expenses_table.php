<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->longText('note')->nullable();

            $table->integer('expense_category_id')->unsigned()->nullable();
            $table->foreign('expense_category_id')->references('id')
                ->on('expense_categories')->onDelete('cascade');

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')
                ->on('users')->onDelete('cascade');

            $table->timestamp('expense_date')->nullable();
            $table->double('amount')->nullable();
            $table->string('payment_mode')->nullable();
            $table->string('payment_reference')->nullable();

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
        Schema::dropIfExists('expenses');
    }
}
