<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoProgress extends Model
{
    use HasFactory;

    protected $table = 'video_progress';

    protected $fillable = [
        'user_id',
        'video_id',
        'watched_duration',
        'total_duration',
        'is_completed',
        'last_watched_at',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'last_watched_at' => 'datetime',
    ];

    // ==================== Relations ====================

    // الطالب
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // الفيديو
    public function video()
    {
        return $this->belongsTo(Video::class);
    }

    // ==================== Helper Methods ====================

    // نسبة المشاهدة
    public function getWatchPercentage()
    {
        if ($this->total_duration <= 0) {
            return 0;
        }
        
        return ($this->watched_duration / $this->total_duration) * 100;
    }

    // تحديث حالة الإكمال
    public function checkIfCompleted()
    {
        // نعتبر الفيديو مكتمل لو شاف 90% أو أكثر
        if ($this->getWatchPercentage() >= 90 && !$this->is_completed) {
            $this->is_completed = true;
            $this->save();
        }
    }
}