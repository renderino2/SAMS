<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->time('scheduled_time_out')->nullable()->after('scheduled_time_in')->comment('Scheduled time out for the student assistant');
            $table->json('work_schedule')->nullable()->after('scheduled_time_out')->comment('Work schedule with multiple time slots for broken schedules');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['scheduled_time_out', 'work_schedule']);
        });
    }
};
