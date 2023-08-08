<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNetPaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('net_pays', function (Blueprint $table) {
            $table->id();
            $table->integer('payslip_id');
            $table->decimal('netpay7', 12, 2)->default(0.00);
            $table->decimal('netpay15', 12, 2)->default(0.00);
            $table->decimal('netpay22', 12, 2)->default(0.00);
            $table->decimal('netpay30', 12, 2)->default(0.00);
            $table->decimal('total_netpay', 12, 2)->default(0.00);
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
        Schema::dropIfExists('net_pays');
    }
}
