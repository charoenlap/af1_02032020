<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogApi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_api', function(Blueprint $table)
        {
            $table->engine = 'MyISAM';

            $table->increments('id');
            $table->string('ip_address')->nullable();
            $table->string('key')->nullable();
            $table->text('value')->nullable();
            $table->date('action_date')->nullable();
            $table->time('action_time')->nullable();
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
        Schema::drop('log_api');
    }
}
