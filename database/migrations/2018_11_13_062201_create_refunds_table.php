<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRefundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('refunds', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('price');
            $table->enum('status', ['pending', 'finish'])->default('pending')->nullable();
            $table->enum('type', ['sell', 'request', 'auction'])->default(null)->nullable();

            $table->integer('user_id')->unsigned()->default(null)->nullable();
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');

            $table->integer('delivery_id')->unsigned()->default(null)->nullable();
            $table->foreign('delivery_id')->references('id')->on('deliveries')
                ->onUpdate('cascade')->onDelete('set null');

            $table->integer('bank_id')->unsigned()->default(null)->nullable();
            $table->foreign('bank_id')->references('id')->on('banks')
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
        Schema::dropIfExists('refunds');
    }
}
