<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFiledCtoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('filed_cto', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_id');
            $table->integer('supervisor_id');
            $table->integer('hr_id');
            $table->integer('sub_signatory_id')->nullable();
            $table->integer('signatory_id');
            $table->integer('credits_id')->nullable();
            $table->longText('leave_details')->nullable();
            $table->string('leave_type');
            $table->string('inclusive_dates');
            $table->string('no_days');
            $table->string('start_date');
            $table->string('end_date');
            $table->longText('status')->nullable();
            $table->longText('remarks')->nullable();
            $table->boolean('isarchived')->default(0);
            $table->boolean('isdisabled')->default(0);
            $table->string('hr_remarks')->default("waiting");
            $table->string('supervisor_remarks')->default("waiting");
            $table->string('sub_signatory_remarks')->nullable();
            $table->string('signatory_remarks')->default("waiting");
            $table->string('date_approved')->nullable();
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
        Schema::dropIfExists('filed_cto');
    }
}
