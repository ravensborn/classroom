<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VideoComment extends Model
{
    protected $fillable = ['video_id', 'user_id', 'body'];

    public function video(): BelongsTo
    {
        return $this->belongsTo(Video::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
