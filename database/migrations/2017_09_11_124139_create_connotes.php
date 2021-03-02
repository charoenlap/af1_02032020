<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConnotes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('connotes', function(Blueprint $table){

            $table->increments('id');
            $table->string('key', 20)->unique()->nullable();
            $table->integer('booking_id');
            $table->string('status', 30)->nullable();
            $table->string('shipper_name')->nullable();
            $table->string('shipper_company')->nullable();
            $table->string('shipper_address')->nullable();
            $table->string('shipper_phone')->nullable();
            $table->string('consignee_name')->nullable();
            $table->string('consignee_company')->nullable();
            $table->string('consignee_address')->nullable();
            $table->string('consignee_phone')->nullable();
            $table->enum('service', ['oneway', 'return'])->default('oneway');
            $table->boolean('cod')->default(0);
            $table->boolean('express')->default(0);
            $table->string('cod_value')->nullable();
            $table->text('details')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['id', 'key', 'booking_id', 'status', 'service']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('connotes');
    }
}
