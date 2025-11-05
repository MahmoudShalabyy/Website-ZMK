<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'description',
        'video_url',
        'duration',
        'order',
        'is_free',
    ];

    protected $casts = [
        'is_free' => 'boolean',
    ];

    // ==================== Relations ====================

    // الكورس
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // الكويزات (لو فيه كويز بعد الفيديو)
    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

    // التقدم
    public function progress()
    {
        return $this->hasMany(VideoProgress::class);
    }

    // ==================== Helper Methods ====================

    // تحويل المدة من ثواني لصيغة قابلة للقراءة
    public function getFormattedDurationAttribute()
    {
        $minutes = floor($this->duration / 60);
        $seconds = $this->duration % 60;
        return sprintf('%02d:%02d', $minutes, $seconds);
    }
}