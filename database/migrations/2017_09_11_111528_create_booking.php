<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBooking extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function(Blueprint $table){
            $table->increments('id');
            $table->string('key', 20)->unique();
            $table->enum('status', ['new', 'inprogress', 'complete', 'pending', 'cancel'])->default('new');
            $table->integer('customer_id');
            $table->string('customer_key', 30)->nullable();
            $table->string('customer_name')->nullable();
            $table->text('address')->nullable();
            $table->string('district', 100)->nullable();
            $table->string('province', 100)->nullable();
            $table->string('postcode', 10)->nullable();
            $table->string('person')->nullable();
            $table->boolean('cod');
            $table->boolean('express');
            $table->integer('car_id')->nullable();
            $table->datetime('get_datetime')->nullable();
            $table->string('msg_key', 15)->nullable();
            $table->string('msg_name')->nullable();
            $table->string('cs_key')->nullable();
            $table->string('cs_name')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['id', 'key', 'status', 'msg_key', 'customer_id', 'customer_key']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('bookings');
    }
}
