<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Like extends Model
{
    protected $fillable = ["user_id","post_user_id"];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function postUser(): BelongsTo
    {
        return $this->belongsTo(PostUser::class);
    }
}
