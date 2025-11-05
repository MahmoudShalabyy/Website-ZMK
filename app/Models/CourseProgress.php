<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseProgress extends Model
{
    use HasFactory;

    protected $table = 'course_progress';

    protected $fillable = [
        'user_id',
        'course_id',
        'videos_completed',
        'total_videos',
        'quizzes_passed',
        'total_quizzes',
        'completion_percentage',
        'is_completed',
        'completed_at',
    ];

    protected $casts = [
        'completion_percentage' => 'decimal:2',
        'is_completed' => 'boolean',
        'completed_at' => 'datetime',
    ];

    // ==================== Relations ====================

    // الطالب
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // الكورس
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // ==================== Helper Methods ====================

    // تحديث نسبة الإنجاز
    public function updateCompletionPercentage()
    {
        $videoPercentage = $this->total_videos > 0 
            ? ($this->videos_completed / $this->total_videos) * 50 
            : 0;
            
        $quizPercentage = $this->total_quizzes > 0 
            ? ($this->quizzes_passed / $this->total_quizzes) * 50 
            : 0;

        $this->completion_percentage = $videoPercentage + $quizPercentage;
        $this->save();
    }

    // هل اكتمل الكورس؟
    public function checkIfCompleted()
    {
        $videosCompleted = $this->videos_completed >= $this->total_videos;
        $quizzesCompleted = $this->quizzes_passed >= $this->total_quizzes;

        if ($videosCompleted && $quizzesCompleted && !$this->is_completed) {
            $this->is_completed = true;
            $this->completed_at = now();
            $this->save();
        }
    }
}