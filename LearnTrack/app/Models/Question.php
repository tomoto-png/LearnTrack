<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    protected $fillable = ["user_id","content","image_url","reward", "auto_repost_enabled"];

    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }
    public function answer(): HasMany
    {
        return $this->hasMany(Answer::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

