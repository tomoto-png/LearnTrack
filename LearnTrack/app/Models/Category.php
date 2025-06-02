<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = ["category_group_id'","name"];
    public function group(): BelongsTo
    {
        return $this->belongsTo(CategoryGroup::class, 'category_group_id');
    }
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }
}
