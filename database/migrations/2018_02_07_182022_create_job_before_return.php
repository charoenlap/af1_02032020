<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobBeforeReturn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_sends', function(Blueprint $table){

            $table->increments('id');
            $table->integer('connote_id');
            $table->string('key', 20);
            $table->enum('status', ['new', 'inprogress', 'complete', 'fail', 'cancel'])->default('new');
            $table->string('msg_key', 10)->nullable();
            $table->string('msg_name')->nullable();
            $table->string('sup_key', 10)->nullable();
            $table->string('sup_name')->nullable();
            $table->string('consignee')->nullable();
            $table->text('address')->nullable();
            $table->string('district', 100)->nullable();
            $table->string('province', 100)->nullable();
            $table->string('postcode', 10)->nullable();
            $table->text('photo')->nullable();
            $table->string('receiver_name')->nullable();
            $table->datetime('received_at')->nullable();
            $table->string('topup')->nullable();
            $table->text('notes')->nullable();
            $table->string('lat', 100)->nullable();
            $table->string('lng', 100)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['id', 'connote_id', 'key', 'status']);
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
