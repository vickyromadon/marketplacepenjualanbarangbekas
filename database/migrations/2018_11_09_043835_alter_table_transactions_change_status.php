<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableTransactionsChangeStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            \DB::statement( "ALTER TABLE transactions MODIFY status ENUM('not_payment', 'approve', 'reject', 'pending', 'cancel', 'finish') DEFAULT 'not_payment'");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            \DB::statement( "ALTER TABLE transactions MODIFY status ENUM('not_payment', 'approve', 'reject', 'pending', 'cancel') DEFAULT 'not_payment'");
        });
    }
}
