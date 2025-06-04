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
        if ($question->is_closed) {
            return false;
        }
        // 質問の投稿者以外にのみ許可
        return $user->id !== $question->user_id;
    }

    public function setBest(User $user, Answer $answer)
    {
        $question = $answer->question;
        $alreadyHasBest = $question->answers()->where('is_best', true)->exists();//exists()1件でも存在すれば true
        return !$alreadyHasBest && $user->id === $answer->question->user_id;
    }
}
