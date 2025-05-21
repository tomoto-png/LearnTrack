<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Plan extends Model
{
    protected $fillable = ['user_id','question_id','content','is_venture'];

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
