<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTracking extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trackings', function(Blueprint $table){

            $table->increments('id');
            $table->string('key', 20);
            $table->enum('status', ['pending', 'new', 'inprogress', 'complete', 'fail', 'cancel'])->default('pending');
            $table->date('track_date');
            $table->time('track_time');
            $table->string('lat', 100)->nullable();
            $table->string('lng', 100)->nullable();
            $table->integer('msg_id')->nullable();
            $table->string('msg_name')->nullable();
            $table->timestamps();

            $table->index(['id', 'key', 'status']);
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
