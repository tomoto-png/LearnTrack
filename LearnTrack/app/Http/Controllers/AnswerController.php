<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use App\Http\Requests\AnswerRequest;
use Illuminate\Support\Facades\Storage;
use App\Models\Answer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\File;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class AnswerController extends Controller
{
    use AuthorizesRequests;
    public function create($id) {
        $question = Question::findOrFail($id);
        $this->authorize('create', [Answer::class, $question]);
        return view('answer.create', compact('question'), ['mode' => 'input']);
    }
    public function store(AnswerRequest $request) {
        $question = Question::findOrFail($request->question_id);
        $this->authorize('create', [Answer::class, $question]);
        $input = $request->validated();
        $user = Auth::user();
        if ($request->input('remove_image') === 'true') {
            if (session('confirm_image_path')) {
                Storage::disk('public')->delete(session('confirm_image_path'));
                session()->forget('confirm_image_path');
            }
            $validated['image_url'] = null;
        } elseif ($request->hasFile('image_url')) {
            if (session('confirm_image_path')) {
                Storage::disk('public')->delete(session('confirm_image_path'));
            }

            $confirmPath = $request->file('image_url')->store('confirm', 'public');
            session(['confirm_image_path' => $confirmPath]);
            $input['image_url'] = $confirmPath;
        } elseif (session('confirm_image_path')) {
            $input['image_url'] = session('confirm_image_path');
        }

        $mode = $request->input('mode');
        $question = Question::findOrFail($input['question_id']);
        if ($mode === 'confirm') {
            return view('answer.create', ['mode' => 'confirm', 'questionInput' => $question, 'input' => $input, 'user' => $user]);
        }
        if ($mode === 'edit') {
            return view('answer.create', ['mode' => 'input', 'questionInput' => $question, 'input' => $input]);
        }
        if ($mode === 'post') {
            try {
                DB::transaction(function () use ($user, $request, $input) {
                    $imageUrl = null;
                    if (session('confirm_image_path')) {
                        $confirmPath = session('confirm_image_path');
                        $fullConfirmPath = storage_path('app/public/' . $confirmPath);
                        $s3Url = Storage::disk('s3')->putFile('uploads/answer_images', new File($fullConfirmPath));
                        $imageUrl = Storage::disk('s3')->url($s3Url);

                        //一時保存のデータを削除
                        Storage::disk('public')->delete(session('confirm_image_path'));
                        session()->forget('confirm_image_path');
                    }
                    Answer::create([
                        'user_id' => $user->id,
                        'question_id' => $input['question_id'],
                        'content' => $request->content,
                        'image_url' => $imageUrl,
                    ]);
                });
            } catch (QueryException $e) {
                return redirect()->back()->withErrors(['error' => '回答の投稿が失敗しました']);
            }
        }
        return redirect()->route('question.show',['id' => $input['question_id']]);
    }
    public function setBest(Answer $answer)
    {
        dd(Auth::user()->id, $answer->question->user_id);
        $this->authorize('setBest', $answer);
        try {
            DB::transaction(function () use ($answer) {
                $question = $answer->question;
                $answer->user->update([
                    'count' => $answer->user->count + $question->reward
                ]);

                $answer->update(['is_best' => true]);

                if (!$question->is_closed) {
                    $question->update(['is_closed' => true]);
                }
            });
        } catch (QueryException $e) {
            return redirect()->back()->withErrors(['error' => 'ベスアンサーの選択に失敗しました']);
        }

        return back();
    }
    public function cancel($id) {
        if (session('confirm_image_path')) {
            Storage::disk('public')->delete(session('confirm_image_path'));
            session()->forget('confirm_image_path');
        }
        return redirect()->route('question.show', ['id' => $id]);
    }
}
