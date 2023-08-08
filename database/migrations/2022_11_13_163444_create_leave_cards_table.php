<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaveCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leave_cards', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->decimal('total_vl', 12, 3)->default(0.00);
            $table->decimal('total_sl', 12, 3)->default(0.00);
            $table->string('hours_earned')->default('00:00:00');
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
        Schema::dropIfExists('leave_cards');
    }
}
