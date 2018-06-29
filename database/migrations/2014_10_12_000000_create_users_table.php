<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('password');
            $table->string('token');
            $table->enum('auto_renewal', ['yes', 'no'])->default('yes');
            $table->ipAddress('signup_ip_address')->nullable();
            $table->ipAddress('confirmation_ip_address')->nullable();
            $table->ipAddress('social_signup_ip_address')->nullable();
            $table->ipAddress('admin_signup_ip_address')->nullable();
            $table->ipAddress('updated_ip_address')->nullable();
            $table->ipAddress('last_login_ip_address')->nullable();
            $table->ipAddress('deleted_ip_address')->nullable();
            $table->longText('deleted_reason')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
