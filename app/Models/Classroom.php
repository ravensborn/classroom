<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Classroom extends Model
{
    protected $fillable = ['name'];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->where('role', 'teacher');
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->where('role', 'student');
    }

    public function videos(): HasMany
    {
        return $this->hasMany(Video::class)->latest();
    }
}
