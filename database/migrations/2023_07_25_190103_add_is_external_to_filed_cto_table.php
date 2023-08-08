<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsExternalToFiledCtoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('filed_cto', function (Blueprint $table) {
            $table->boolean('is_external')->default(false);
            $table->string('external_name')->nullable();
            $table->string('external_designation')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('filed_cto', function (Blueprint $table) {
            //
        });
    }
}
