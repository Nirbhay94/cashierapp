<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('barcode_type')->nullable();
            $table->string('barcode')->nullable();
            $table->string('weight')->nullable();
            $table->string('brand')->nullable();
            $table->double('cost')->default(0);
            $table->integer('sales')->default(0);
            $table->double('price')->default(0);
            $table->string('taxes')->nullable();
            $table->longText('images')->nullable();
            $table->longText('description');

            $table->integer('product_category_id')->unsigned()->nullable();
            $table->foreign('product_category_id')->references('id')
                ->on('product_categories')->onDelete('cascade');

            $table->integer('product_unit_id')->unsigned()->nullable();
            $table->foreign('product_unit_id')->references('id')
                ->on('product_units')->onDelete('set null');

            $table->integer('quantity')->default(0);

            $table->enum('track', ['yes', 'no'])->default('no');

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
        Schema::dropIfExists('products');
    }
}
