<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToAttendances extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->datetime('time_in')->nullable();
            $table->datetime('time_out')->nullable();
            $table->string('undertime')->nullable()->default('00:00:00');
            $table->string('overtime')->nullable()->default('00:00:00');
            $table->string('hours_worked')->nullable()->default('00:00:00');
            $table->string('late')->nullable()->default('00:00:00');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attendances', function (Blueprint $table) {
            //
        });
    }
}
