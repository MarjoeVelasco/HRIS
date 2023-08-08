<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaveCreditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leave_credits', function (Blueprint $table) {
            $table->id();
            $table->decimal('total_vl', 12, 3)->default(0.00);
            $table->decimal('total_sl', 12, 3)->default(0.00);
            $table->decimal('less_vl', 12, 3)->default(0.00);
            $table->decimal('less_sl', 12, 3)->default(0.00);
            $table->decimal('balance_vl', 12, 3)->default(0.00);
            $table->decimal('balance_sl', 12, 3)->default(0.00);
            $table->string('date_certification');
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
        Schema::dropIfExists('leave_credits');
    }
}
