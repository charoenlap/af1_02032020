<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePositions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('positions', function(Blueprint $table){

            $table->increments('id');
            $table->string('key', 30);
            $table->string('label');
            $table->text('except')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('key');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('positions');
    }
}
