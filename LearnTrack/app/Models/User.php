<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'bio',
        'avatar',
        'gender',
        'age',
        'occupation',
        'count'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function plans(): HasMany
    {
        return $this->hasMany(Plan::class);
    }

    public function studySessions(): HasMany
    {
        return $this->hasMany(StudySession::class);
    }

    public function timerSettings(): HasMany
    {
        return $this->hasMany(TimerSetting::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }
    public function timerSetting(): HasOne
    {
        return $this->hasOne(TimerSetting::class);
    }
    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }
    public function answerReplys(): HasMany
    {
        return $this->hasMany(AnswerReply::class);
    }
}
