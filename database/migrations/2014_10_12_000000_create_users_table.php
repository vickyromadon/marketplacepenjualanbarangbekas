<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone', 15)->default(null)->nullable()->unique();
            $table->integer('age')->default(null)->nullable();
            $table->enum('religion', ['Islam', 'Kristen Protestan', 'Katolik', 'Hindu', 'Buddha', 'Kong Hu Cu'])->default(null)->nullable();
            $table->enum('gender', ['Male', 'Female'])->default(null)->nullable();
            $table->date('birthdate')->default(null)->nullable();
            $table->string('birthplace')->default(null)->nullable();
            $table->enum('privilege', ['0', '1']);
            
            $table->integer('file_id')->unsigned()->default(null)->nullable();
            $table->foreign('file_id')->references('id')->on('files')
                ->onUpdate('cascade')->onDelete('set null');

            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
