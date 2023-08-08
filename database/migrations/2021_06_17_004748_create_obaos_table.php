<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateObaosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('obaos', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id');
            $table->string('status'); //OB or AO
            $table->string('inclusive_dates'); 
            $table->string('start_time')->nullable()->default('00:00:00');
            $table->string('end_time')->nullable()->default('00:00:00');
            $table->string('title'); 
            $table->string('details'); 
            $table->longText('note')->nullable();
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
        Schema::dropIfExists('obaos');
    }
}
