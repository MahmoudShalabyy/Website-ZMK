<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'video_id',
        'title',
        'description',
        'quiz_type',
        'settings',
        'order',
    ];

    protected $casts = [
        'settings' => 'array',
    ];

    // ==================== Relations ====================

    // الكورس
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // الفيديو (لو الكويز بعد فيديو معين)
    public function video()
    {
        return $this->belongsTo(Video::class);
    }

    // الأسئلة
    public function questions()
    {
        return $this->hasMany(Question::class)->orderBy('order');
    }

    // المحاولات
    public function attempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }

    // ==================== Helper Methods ====================

    // نسبة النجاح
    public function getPassingScore()
    {
        return $this->settings['passing_score'] ?? 70;
    }

    // عدد المحاولات المسموحة
    public function getMaxAttempts()
    {
        return $this->settings['max_attempts'] ?? null;
    }

    // الوقت المحدد
    public function getTimeLimit()
    {
        return $this->settings['time_limit'] ?? null;
    }

    // هل الكويز إلزامي؟
    public function isRequired()
    {
        return $this->settings['is_required'] ?? true;
    }

    // هل يسمح بالإعادة؟
    public function allowsRetake()
    {
        return $this->settings['allow_retake'] ?? true;
    }
}