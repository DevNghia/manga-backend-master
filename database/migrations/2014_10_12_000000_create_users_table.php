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
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('avatar')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('provider')->default('email')->nullable();
            $table->string('provider_id')->nullable();
            $table->integer('email_verified_at')->nullable();
            $table->string('password');
            $table->tinyInteger('is_active')->nullable()->default(true);
            $table->integer('last_login')->nullable();
            $table->integer('activated_at')->nullable();
            $table->rememberToken();
            $table->integer('created_at');
            $table->integer('updated_at');
            $table->integer('deleted_at')->nullable();
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
