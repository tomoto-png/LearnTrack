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
        // デバッグ出力
        dd([
            'user_id' => $user->id,
            'question_user_id' => $question->user_id,
            'is_closed' => $question->is_closed,
            'can_create' => !$question->is_closed && $user->id !== $question->user_id,
        ]);

        if ($question->is_closed) {
            return false;
        }

        return $user->id !== $question->user_id;
    }
    public function setBest(User $user, Answer $answer)
    {
        $question = $answer->question;

        // 確認ポイント
        dd([
            'user_id' => $user->id,
            'question_user_id' => $question->user_id,
            'already_has_best' => $question->answers()->where('is_best', true)->exists(),
            'all_answers' => $question->answers()->get(['id', 'is_best']),
        ]);

        $alreadyHasBest = $question->answers()->where('is_best', true)->exists();
        return !$alreadyHasBest && $user->id === $question->user_id;
    }
}
