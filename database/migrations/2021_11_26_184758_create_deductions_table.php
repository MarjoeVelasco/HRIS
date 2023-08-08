<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeductionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deductions', function (Blueprint $table) {
            $table->id();
            $table->integer('payslip_id');
            $table->decimal('gsis_insurance', 12, 2)->default(0.00);
            $table->decimal('gsis_policy_loan', 12, 2)->default(0.00);
            $table->decimal('tax', 12, 2)->default(0.00);
            $table->decimal('philhealth_contri', 12, 2)->default(0.00);
            $table->decimal('philhealth_diff', 12, 2)->default(0.00);
            $table->decimal('gsis_conso', 12, 2)->default(0.00);
            $table->decimal('gsis_emergency', 12, 2)->default(0.00);
            $table->decimal('gsis_computer', 12, 2)->default(0.00);
            $table->decimal('gsis_ins_diff', 12, 2)->default(0.00);
            $table->decimal('pagibig_contri', 12, 2)->default(0.00);
            $table->decimal('pagibig_mp', 12, 2)->default(0.00);
            $table->decimal('pagibig_cal', 12, 2)->default(0.00);
            $table->decimal('pagibig_mp2', 12, 2)->default(0.00);
            $table->decimal('gsis_educ', 12, 2)->default(0.00);
            $table->decimal('gfal', 12, 2)->default(0.00);
            $table->decimal('union_dues', 12, 2)->default(0.00);
            $table->decimal('paluwagan_shares', 12, 2)->default(0.00);
            $table->decimal('ilsea_loan', 12, 2)->default(0.00);
            $table->decimal('paluwagan_loan', 12, 2)->default(0.00);
            $table->decimal('total_deduction', 12, 2)->default(0.00);
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
        Schema::dropIfExists('deductions');
    }
}
