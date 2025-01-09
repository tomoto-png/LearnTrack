<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TimerSetting extends Model
{
    protected $fillable = ['user_id','work_duration','break_duration','is_pomodoro'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
