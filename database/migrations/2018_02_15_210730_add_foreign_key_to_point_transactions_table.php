<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyToPointTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('point_transactions', function (Blueprint $table){
            $table->foreign('invoice_id')
                ->references('id')->on('invoices')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('point_transactions', function (Blueprint $table){
            $table->dropForeign('point_transactions_invoice_id_foreign');
        });
    }
}
