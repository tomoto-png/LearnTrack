<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Answer;
use App\Models\AnswerReply;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Requests\AnswerReplyRequest;

class AnswerReplyController extends Controller
{
    use AuthorizesRequests;
    public function create($id)
    {
        $answer = Answer::findOrFail($id);
        $this->authorize('create', [AnswerReply::class, $answer]);
        $questionId = $answer->question->id;
        return view('replie.create', compact('answer', 'questionId'), ['mode' => 'input']);
    }
    public function store(AnswerReplyRequest $request)
    {
        $answer = Answer::findOrFail($request->input('answer_id'));
        $user = $request->user();
        $this->authorize('create', [AnswerReply::class, $answer]);
        $mode = $request->input('mode');
        $questionId = $answer->question->id;
        if ($mode === 'confirm') {
            return view('replie.create', ['mode' => 'confirm', 'replieInput' => $request, 'input' => $answer, 'user' => $user]);
        }
        if ($mode === 'edit') {
            return view('replie.create', ['mode' => 'input', 'replieInput' => $request, 'input' => $answer, 'questionId' => $questionId]);
        }
        if ($mode === 'post') {
            AnswerReply::create([
                'user_id' => $user->id,
                'answer_id' => $request->answer_id,
                'content' => $request->content,
            ]);
        }

        return redirect()->route('question.show', ['id' => $questionId]);
    }
}
