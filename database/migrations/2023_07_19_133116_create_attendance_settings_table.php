<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class CreateAttendanceSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendance_settings', function (Blueprint $table) {
            $table->id();
            $table->string('system_setting_name');
            $table->string('system_setting_value');
            $table->longText('notes')->nullable();
            $table->timestamps();
        });

         // Insert some stuff
        DB::table('attendance_settings')->insert(
            array(['system_setting_name' => 'time in','system_setting_value' => 'hyrid', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now() ],
                  ['system_setting_name' => 'time out','system_setting_value' => 'hyrid', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()])
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendance_settings');
    }
}
