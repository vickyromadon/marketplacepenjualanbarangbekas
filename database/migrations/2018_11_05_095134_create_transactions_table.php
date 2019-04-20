<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->text('arr_product');
            $table->text('arr_price');
            $table->text('arr_quantity');
            $table->bigInteger('total_price');
            $table->string('name')->default(null)->nullable();
            $table->string('phone', 15)->default(null)->nullable();
            $table->text('address')->default(null)->nullable();
            $table->date('transfer_date')->nullable();
            $table->enum('status', ['not_payment', 'approve', 'reject', 'pending', 'cancel'])->default('not_payment');
            $table->text('note')->default(null)->nullable();

            $table->integer('user_id')->unsigned()->default(null)->nullable();
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');

            $table->integer('file_id')->unsigned()->default(null)->nullable();
            $table->foreign('file_id')->references('id')->on('files')
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
        Schema::dropIfExists('transactions');
    }
}
