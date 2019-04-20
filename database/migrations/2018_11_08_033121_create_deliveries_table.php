<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('quantity');
            $table->date('delivery_at')->default(null)->nullable();
            $table->date('arrive_at')->default(null)->nullable();
            $table->string('number_proof')->default(null)->nullable();
            $table->enum('status', ['pending', 'delivered', 'arrived'])->default('pending');

            $table->integer('user_id')->unsigned()->default(null)->nullable();
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');

            $table->integer('file_id')->unsigned()->default(null)->nullable();
            $table->foreign('file_id')->references('id')->on('files')
                ->onUpdate('cascade')->onDelete('set null');

            $table->integer('product_id')->unsigned()->default(null)->nullable();
            $table->foreign('product_id')->references('id')->on('products')
                ->onUpdate('cascade')->onDelete('set null');

            $table->integer('transaction_id')->unsigned()->default(null)->nullable();
            $table->foreign('transaction_id')->references('id')->on('transactions')
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
        Schema::dropIfExists('deliveries');
    }
}
