<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'price',
        'image',
        'duration',
        'level',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // ==================== Relations ====================

    // الفيديوهات
    public function videos()
    {
        return $this->hasMany(Video::class)->orderBy('order');
    }

    // الكويزات
    public function quizzes()
    {
        return $this->hasMany(Quiz::class)->orderBy('order');
    }

    // المشتريات
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    // الطلبة اللي اشتروا الكورس
    public function students()
    {
        return $this->belongsToMany(User::class, 'purchases')
                    ->withPivot('amount', 'payment_status', 'purchased_at')
                    ->withTimestamps();
    }

    // التقدم
    public function progress()
    {
        return $this->hasMany(CourseProgress::class);
    }

    // الشهادات
    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    // ==================== Helper Methods ====================

    // عدد الطلبة المشتركين
    public function studentsCount()
    {
        return $this->purchases()->where('payment_status', 'completed')->count();
    }

    // إجمالي المبيعات
    public function totalRevenue()
    {
        return $this->purchases()
                    ->where('payment_status', 'completed')
                    ->sum('amount');
    }
}