<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeavesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leaves', function (Blueprint $table) {
        
            $table->bigIncrements('leave_id');
            $table->string('employee_id');
            $table->string('supervisor_id');
            $table->string('approver_id')->nullable();
            $table->string('leave_type');
            $table->string('details')->nullable()->default('Not Applicable');
            $table->string('inclusive_dates');
            $table->integer('no_days');
            $table->string('start_date');
            $table->string('end_date');
            $table->string('commutation')->nullable()->default('Not Applicable');
            $table->string('status');
            $table->longText('supervisor_note')->nullable()->default('Waiting for Approval');
            $table->longText('note')->nullable()->default('Waiting for Approval');
            $table->tinyint('archive')->default(0);
            $table->string('date_recommended');
            $table->string('date_approved');
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
        Schema::dropIfExists('leaves');
    }
}
