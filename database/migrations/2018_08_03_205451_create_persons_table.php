<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('persons', function (Blueprint $table) {
        $table->increments('id');
        $table->string('name', 100)->unique();
        $table->string('document_type', 20)->nullable();
        $table->string('document_num', 20)->nullable();
        $table->string('direction', 70)->nullable();
        $table->string('phone', 20)->nullable();
        $table->string('email', 50)->nullable();
        $table->boolean('active')->default(1);
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
        Schema::dropIfExists('persons');
    }
}
