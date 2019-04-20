<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBidRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bid_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('price');
            $table->integer('processing_time');
            $table->enum('status', ['pending', 'approve', 'reject'])->default('pending');

            $table->integer('request_id')->unsigned()->default(null)->nullable();
            $table->foreign('request_id')->references('id')->on('requests')
                ->onUpdate('cascade')->onDelete('set null');

            $table->integer('user_id')->unsigned()->default(null)->nullable();
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');

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
        Schema::dropIfExists('bid_requests');
    }
}
