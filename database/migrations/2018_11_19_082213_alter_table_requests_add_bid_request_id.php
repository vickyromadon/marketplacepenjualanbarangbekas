<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableRequestsAddBidRequestId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('requests', function (Blueprint $table) {
            $table->integer('bid_request_id')->unsigned()->default(null)->nullable();
            $table->foreign('bid_request_id')->references('id')->on('bid_requests')
                ->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('requests', function (Blueprint $table) {
            $table->dropForeign('requests_bid_request_id_foreign');
            $table->dropColumn('bid_request_id');
        });
    }
}
