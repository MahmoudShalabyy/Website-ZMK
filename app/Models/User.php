<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // ==================== Relations ====================

    // المشتريات
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    // الكورسات المشتراة
    public function purchasedCourses()
    {
        return $this->belongsToMany(Course::class, 'purchases')
                    ->withPivot('amount', 'payment_status', 'purchased_at')
                    ->withTimestamps();
    }

    // محاولات الكويزات
    public function quizAttempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }

    // التقدم في الكورسات
    public function courseProgress()
    {
        return $this->hasMany(CourseProgress::class);
    }

    // التقدم في الفيديوهات
    public function videoProgress()
    {
        return $this->hasMany(VideoProgress::class);
    }

    // الشهادات
    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    // ==================== Helper Methods ====================

    // هل هو أدمن؟
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    // هل هو طالب؟
    public function isStudent()
    {
        return $this->role === 'student';
    }

    // هل اشترى كورس معين؟
    public function hasPurchased($courseId)
    {
        return $this->purchases()
                    ->where('course_id', $courseId)
                    ->where('payment_status', 'completed')
                    ->exists();
    }
}