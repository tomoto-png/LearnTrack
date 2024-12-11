<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PostUser extends Model
{
    protected $fillable = ["user_id","content","image_url"];

    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

