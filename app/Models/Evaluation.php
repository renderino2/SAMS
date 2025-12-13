<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Evaluation extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'evaluator_id',
        'student_assistant_id',
        'student_name',
        'nature_of_work',
        'office',
        'rate1',
        'rate2',
        'rate3',
        'rate4',
        'rate5',
        'rate6',
        'rate7',
        'rate8',
        'rate9',
        'rate10',
        'total_score',
        'average_score',
        'overall_rating',
        'comments',
        'evaluation_date',
        'rated_by',
        'head_of_office',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'evaluation_date' => 'date',
        'average_score' => 'decimal:1',
    ];

    /**
     * Get the evaluator (office head) that created this evaluation.
     */
    public function evaluator()
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }

    /**
     * Get the student assistant being evaluated.
     */
    public function studentAssistant()
    {
        return $this->belongsTo(User::class, 'student_assistant_id');
    }
}
