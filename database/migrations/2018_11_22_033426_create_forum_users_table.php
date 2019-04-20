<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateForumUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forum_users', function (Blueprint $table) {
            $table->increments('id');
            $table->text('description')->default(null)->nullable();

            $table->integer('user_id')->unsigned()->default(null)->nullable();
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');

            $table->integer('forum_id')->unsigned()->default(null)->nullable();
            $table->foreign('forum_id')->references('id')->on('forums')
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
        Schema::dropIfExists('forum_users');
    }
}
