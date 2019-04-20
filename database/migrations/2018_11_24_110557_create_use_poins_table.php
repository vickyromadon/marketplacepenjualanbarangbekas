<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsePoinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('use_poins', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('user_id')->unsigned()->default(null)->nullable();
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');

            $table->integer('product_id')->unsigned()->default(null)->nullable();
            $table->foreign('product_id')->references('id')->on('products')
                ->onUpdate('cascade')->onDelete('set null');

            $table->string('expired_date');
            $table->enum('status', ['publish', 'expired'])->default('publish')->nullable();

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
        Schema::dropIfExists('use_poins');
    }
}
