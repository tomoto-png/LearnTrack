<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Answer;
use App\Models\AnswerReply;
use Illuminate\Support\Facades\Auth;
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
        $this->authorize('create', [AnswerReply::class, $answer]);
        $validated = $request->validated();
        $user = Auth::user();
        $mode = $request->input('mode');
        $questionId = $answer->question->id;
        if ($mode === 'confirm') {
            return view('replie.create', ['mode' => 'confirm', 'replieInput' => $validated, 'input' => $answer, 'user' => $user]);
        }
        if ($mode === 'edit') {
            return view('replie.create', ['mode' => 'input', 'replieInput' => $validated, 'input' => $answer, 'questionId' => $questionId]);
        }
        if ($mode === 'post') {
            AnswerReply::create([
                'user_id' => $user->id,
                'answer_id' => $validated['answer_id'],
                'content' => $request['content'],
            ]);
        }

        return redirect()->route('question.show', ['id' => $questionId]);
    }
}
