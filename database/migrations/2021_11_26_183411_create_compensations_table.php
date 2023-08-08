<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompensationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compensations', function (Blueprint $table) {
            $table->id();
            $table->integer('payslip_id');
            $table->decimal('basic_pay', 12, 2);
            $table->decimal('representation', 12, 2)->default(0.00);
            $table->decimal('transportation', 12, 2)->default(0.00);
            $table->decimal('rep_trans_sum', 12, 2)->default(0.00);
            $table->decimal('gross_pay', 12, 2)->default(0.00);
            $table->decimal('pera', 12, 2)->default(0.00);
            $table->decimal('salary_differential', 12, 2)->default(0.00);
            $table->decimal('myb_adjustments', 12, 2)->default(0.00);
            $table->decimal('lates_undertime', 12, 2)->default(0.00);
            $table->decimal('pera_under_diff', 12, 2)->default(0.00);
            $table->decimal('gross_income', 12, 2)->default(0.00);	
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
        Schema::dropIfExists('compensations');
    }
}
