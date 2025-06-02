<?php

namespace App\Policies;

use App\Models\Answer;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use App\Models\Question;

class AnswerPolicy
{
    public function create(User $user, Question $question)
    {
        // 質問の投稿者以外にのみ許可
        return $user->id !== $question->user_id;
    }
}
