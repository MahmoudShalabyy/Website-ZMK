<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'amount',
        'currency',
        'payment_status',
        'payment_method',
        'transaction_id',
        'payment_gateway',
        'purchased_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'purchased_at' => 'datetime',
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

    // هل الدفع مكتمل؟
    public function isCompleted()
    {
        return $this->payment_status === 'completed';
    }

    // هل الدفع فاشل؟
    public function isFailed()
    {
        return $this->payment_status === 'failed';
    }

    // هل في انتظار الدفع؟
    public function isPending()
    {
        return $this->payment_status === 'pending';
    }
}