<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogConnote extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_connotes', function(Blueprint $table) {

            $table->increments('id');
            $table->integer('connote_id');
            $table->string('status', 30)->nullable();
            $table->string('action_by')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['id', 'connote_id', 'status']);
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
