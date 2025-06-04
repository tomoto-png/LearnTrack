<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Plan extends Model
{
    protected $fillable = ["user_id","name","description","target_hours","priority","progress","completed"];

    public function studySessions(): HasMany
    {
        return $this->hasMany(StudySession::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
