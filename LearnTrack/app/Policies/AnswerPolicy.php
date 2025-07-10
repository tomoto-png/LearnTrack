<?php

namespace App\Policies;

use App\Models\Answer;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use App\Models\Question;
use Illuminate\Support\Facades\Log;
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

        \Log::info('[Policy] setBest check', [
            'auth_user' => $user->id,
            'question_user_id' => optional($question)->user_id,
            'already_has_best' => optional($question)->answers()->where('is_best', true)->exists(),
            'question_exists' => !is_null($question),
        ]);

        if (!$question) {
            return false;
        }

        $alreadyHasBest = $question->answers()->where('is_best', true)->exists();
        return !$alreadyHasBest && $user->id === $question->user_id;
    }
}
