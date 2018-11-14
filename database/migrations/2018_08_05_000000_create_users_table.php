<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            // relacion con tabla persons
            $table->integer('id')->unsigned();
            $table->foreign('id')->references('id')->on('persons')->onDelete('cascade');

            $table->string('user_name', 100)->unique();
            $table->string('password', 100);
            $table->boolean('active')->default(1);
            // Relacion con tabla roles
            $table->integer('rol_id')->unsigned();
            $table->foreign('rol_id')->references('id')->on('roles');

            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
