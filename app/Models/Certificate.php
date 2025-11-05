<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'certificate_number',
        'issued_at',
        'certificate_url',
    ];

    protected $casts = [
        'issued_at' => 'datetime',
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

    // توليد رقم الشهادة
    public static function generateCertificateNumber()
    {
        return 'CERT-' . date('Y') . '-' . strtoupper(Str::random(8));
    }

    // الحصول على رابط الشهادة
    public function getCertificateLink()
    {
        return $this->certificate_url 
            ? asset('storage/' . $this->certificate_url) 
            : null;
    }
}