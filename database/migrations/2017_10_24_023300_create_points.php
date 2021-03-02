<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePoints extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('points', function(Blueprint $table){

            $table->increments('id');
            $table->string('key', 50);
            $table->integer('customer_id');
            $table->string('customer_key', 30);
            $table->enum('type', ['shipper', 'consignee'])->default('consignee');
            $table->string('name')->nullable();
            $table->string('address')->nullable();
            $table->string('district')->nullable();
            $table->string('province')->nullable();
            $table->string('postcode', 8)->nullable();
            $table->string('person')->nullable();
            $table->string('mobile')->nullable();
            $table->string('office_tel')->nullable();
            $table->string('fax')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['id', 'key', 'customer_id', 'customer_key']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
