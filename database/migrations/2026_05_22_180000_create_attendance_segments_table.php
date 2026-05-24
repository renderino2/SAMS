<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::dropIfExists('attendance_segments');

        Schema::create('attendance_segments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('attendance_id');
            $table->tinyInteger('segment_order')->unsigned()->default(1);
            $table->string('segment_type', 20)->default('scheduled');
            $table->time('expected_time_in')->nullable();
            $table->time('expected_time_out')->nullable();
            $table->time('time_in')->nullable();
            $table->string('time_in_photo')->nullable();
            $table->time('time_out')->nullable();
            $table->string('time_out_photo')->nullable();
            $table->integer('total_minutes')->nullable();
            $table->string('status', 20)->default('Pending');
            $table->text('remarks')->nullable();
            $table->unsignedInteger('covering_for')->nullable();
            $table->unsignedBigInteger('covering_request_id')->nullable();
            $table->timestamps();

            $table->foreign('attendance_id')->references('id')->on('attendances')->onDelete('cascade');
            $table->foreign('covering_for')->references('id')->on('users')->onDelete('set null');
            $table->index(['attendance_id', 'segment_order']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('attendance_segments');
    }
};
