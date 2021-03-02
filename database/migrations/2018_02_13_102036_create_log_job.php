<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogJob extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_jobs', function(Blueprint $table) {

            $table->increments('id');
            $table->integer('job_id');
            $table->string('status', 30)->nullable();
            $table->string('action_by')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['id', 'job_id', 'status']);
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
