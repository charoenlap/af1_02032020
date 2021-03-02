<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function(Blueprint $table){

            $table->increments('id');
            $table->string('key', 50);
            $table->string('email')->nullable();
            $table->string('password')->nullable();
            $table->string('name')->nullable();
            $table->string('address')->nullable();
            $table->string('district')->nullable();
            $table->string('province')->nullable();
            $table->string('postcode', 8)->nullable();
            $table->string('person')->nullable();
            $table->string('mobile')->nullable();
            $table->string('office_tel')->nullable();
            $table->string('fax')->nullable();
            $table->string('level')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['id', 'key']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('customers');
    }
}
