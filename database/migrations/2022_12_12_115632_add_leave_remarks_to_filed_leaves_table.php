<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLeaveRemarksToFiledLeavesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('filed_leaves', function (Blueprint $table) {
            $table->text('leave_remarks')->default('n/a')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('filed_leaves', function (Blueprint $table) {
            $table->dropColumn('leave_remarks');
        });
    }
}
