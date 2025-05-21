<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Like extends Model
{
    protected $fillable = ["user_id","question_id"];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function quesiton(): BelongsTo
    {
        return $this->belongsTo(Quesiton::class);
    }
}
