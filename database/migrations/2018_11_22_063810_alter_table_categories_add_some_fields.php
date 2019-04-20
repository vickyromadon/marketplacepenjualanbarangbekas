<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableCategoriesAddSomeFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->bigInteger('price_buy')->default(0)->nullable();
            $table->bigInteger('price_sell')->default(0)->nullable();
            $table->integer('quantity')->default(0)->nullable();
            $table->integer('min_buy')->default(0)->nullable();
            $table->integer('min_sell')->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('price_buy');
            $table->dropColumn('price_sell');
            $table->dropColumn('quantity');
            $table->dropColumn('min_sell');
            $table->dropColumn('min_buy');
        });
    }
}
