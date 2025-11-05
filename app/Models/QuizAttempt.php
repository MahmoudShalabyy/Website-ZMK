<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'quiz_id',
        'attempt_data',
        'score',
        'passed',
        'attempt_number',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'attempt_data' => 'array',
        'score' => 'decimal:2',
        'passed' => 'boolean',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    // ==================== Relations ====================

    // الطالب
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // الكويز
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    // ==================== Helper Methods ====================

    // الإجابات
    public function getAnswers()
    {
        return $this->attempt_data['answers'] ?? [];
    }

    // عدد الإجابات الصحيحة
    public function getCorrectCount()
    {
        return $this->attempt_data['correct_count'] ?? 0;
    }

    // عدد الإجابات الخاطئة
    public function getWrongCount()
    {
        return $this->attempt_data['wrong_count'] ?? 0;
    }

    // إجمالي الأسئلة
    public function getTotalQuestions()
    {
        return $this->attempt_data['total_questions'] ?? 0;
    }
}