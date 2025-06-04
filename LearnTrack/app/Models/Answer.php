<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class answer extends Model
{
    protected $fillable = ['user_id','question_id','content','image_url','is_best'];

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function answerReply(): HasMany
    {
        return $this->hasMany(AnswerReply::class);
    }
}
