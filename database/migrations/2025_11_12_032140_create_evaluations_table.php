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
        Schema::dropIfExists('evaluations');
        
        Schema::create('evaluations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('evaluator_id')->nullable(); // Office Head who created the evaluation
            $table->unsignedInteger('student_assistant_id')->nullable(); // Student Assistant being evaluated
            $table->string('student_name');
            $table->string('nature_of_work');
            $table->string('office');
            $table->integer('rate1')->default(0); // Punctuality and regular attendance
            $table->integer('rate2')->default(0); // Conscious use of time during working hours
            $table->integer('rate3')->default(0); // Ability to understand and follow directions
            $table->integer('rate4')->default(0); // Sufficient competency and skill
            $table->integer('rate5')->default(0); // Promptness in performing assigned work
            $table->integer('rate6')->default(0); // Attention to details
            $table->integer('rate7')->default(0); // Initiative
            $table->integer('rate8')->default(0); // Health Condition
            $table->integer('rate9')->default(0); // Spirit and Attitude
            $table->integer('rate10')->default(0); // Professional discretion
            $table->integer('total_score')->default(0);
            $table->decimal('average_score', 3, 1)->default(0);
            $table->string('overall_rating')->nullable(); // Excellent, Good, Fair, Poor
            $table->text('comments')->nullable();
            $table->date('evaluation_date');
            $table->string('rated_by');
            $table->string('head_of_office');
            $table->string('status')->default('Pending'); // Pending, Reviewed, Archived
            $table->timestamps();
            $table->softDeletes();

            // Foreign keys
            $table->foreign('evaluator_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('student_assistant_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('evaluations');
    }
};
