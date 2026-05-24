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
        Schema::create('evaluation_criteria_settings', function (Blueprint $table) {
            $table->id();
            $table->string('criterion_key', 50)->unique()->comment('e.g., rate1, rate2, etc.');
            $table->text('question_text')->comment('The question text for this criterion');
            $table->integer('order')->default(0)->comment('Display order');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Insert default criteria questions
        $defaultCriteria = [
            ['criterion_key' => 'rate1', 'question_text' => 'Punctuality and regular attendance', 'order' => 1],
            ['criterion_key' => 'rate2', 'question_text' => 'Conscious use of time during working hours', 'order' => 2],
            ['criterion_key' => 'rate3', 'question_text' => 'Ability to understand and follow directions', 'order' => 3],
            ['criterion_key' => 'rate4', 'question_text' => 'Sufficient competency and skill', 'order' => 4],
            ['criterion_key' => 'rate5', 'question_text' => 'Promptness in performing assigned work', 'order' => 5],
            ['criterion_key' => 'rate6', 'question_text' => 'Attention to details (accuracy, neatness, etc.)', 'order' => 6],
            ['criterion_key' => 'rate7', 'question_text' => 'Initiative (doing things without waiting for orders)', 'order' => 7],
            ['criterion_key' => 'rate8', 'question_text' => 'Health Condition (balance work and studies)', 'order' => 8],
            ['criterion_key' => 'rate9', 'question_text' => 'Spirit and Attitude', 'order' => 9],
            ['criterion_key' => 'rate10', 'question_text' => 'Professional discretion', 'order' => 10],
        ];

        foreach ($defaultCriteria as $criterion) {
            \DB::table('evaluation_criteria_settings')->insert(array_merge($criterion, [
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('evaluation_criteria_settings');
    }
};
