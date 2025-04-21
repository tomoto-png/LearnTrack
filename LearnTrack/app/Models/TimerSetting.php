<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TimerSetting extends Model
{
    protected $fillable = ['user_id', 'study_time', 'break_time', 'auto_switch','sound_effect'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
