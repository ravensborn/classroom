<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Video extends Model
{
    protected $fillable = ['classroom_id', 'user_id', 'title', 'description', 'file_path'];

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(VideoAttendance::class);
    }

    public function isAttendanceOpen(): bool
    {
        return $this->created_at->addDays(3)->isFuture();
    }

    public function attendanceRemainingForHumans(): string
    {
        return $this->created_at->addDays(3)->diffForHumans();
    }
}
