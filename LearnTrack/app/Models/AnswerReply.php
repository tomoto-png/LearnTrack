<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnswerReply extends Model
{
    protected $fillable = ['user_id','answer_id','content'];

    public function answer(): BelongsTo
    {
        return $this->belongsTo(Answer::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
