<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProvidersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('providers', function (Blueprint $table) {
      // No es autoincrement xq se le asigna el ID de la tabla persons, es una generalizacion
      $table->integer('id')->unsigned();
      $table->string('contact', 50)->nullable();
      $table->string('contact_phone', 50)->nullable();

      $table->foreign('id')->references('id')->on('persons')->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
      Schema::dropIfExists('providers');
  }
}
