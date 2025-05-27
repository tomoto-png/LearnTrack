<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Answer;

class AnswerReplyPolicy
{
    /**
     * Create a new policy instance.
     */
    public function create(User $user, Answer $answer)
    {
        return $user->id === $answer->user_id || $user->id === $answer->question->user_id;
    }
}
