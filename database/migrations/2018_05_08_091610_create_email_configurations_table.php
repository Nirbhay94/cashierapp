<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailConfigurationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_configurations', function (Blueprint $table) {
            $table->increments('id');

            $table->string('from_address')->nullable();
            $table->string('from_name')->nullable();

            $table->string('reply_to_address')->nullable();
            $table->string('reply_to_name')->nullable();

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')
                ->on('users')->onDelete('cascade');

            $table->longText('header')->nullable();
            $table->string('header_url')->nullable();

            $table->longText('subcopy')->nullable();
            $table->longText('footer')->nullable();

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
        Schema::dropIfExists('email_configurations');
    }
}
