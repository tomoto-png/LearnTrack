<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class resource extends Model
{
    protected $fillable = ["user_id","title","url","description"];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
