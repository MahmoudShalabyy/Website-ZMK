<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'question_text',
        'question_data',
        'order',
    ];

    protected $casts = [
        'question_data' => 'array',
    ];

    // ==================== Relations ====================

    // الكويز
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    // ==================== Helper Methods ====================

    // الاختيارات
    public function getOptions()
    {
        return $this->question_data['options'] ?? [];
    }

    // الإجابة الصحيحة
    public function getCorrectAnswer()
    {
        return $this->question_data['correct_answer'] ?? null;
    }

    // الدرجة
    public function getPoints()
    {
        return $this->question_data['points'] ?? 10;
    }

    // هل الإجابة صحيحة؟
    public function isCorrectAnswer($answerId)
    {
        return $this->getCorrectAnswer() == $answerId;
    }
}