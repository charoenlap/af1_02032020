<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployees extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function(Blueprint $table){

            $table->increments('id');
            $table->string('emp_key', 10);
            $table->string('nickname', 30);
            $table->string('password')->nullable();
            $table->integer('position_id')->nullable();
            $table->integer('branch_id')->nullable();
            $table->string('title')->nullable();
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('id_card', 13)->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->date('start_date')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['id', 'emp_key']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('employees');
    }
}
