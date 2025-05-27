<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use App\Http\Requests\AnswerRequest;
use Illuminate\Support\Facades\Storage;
use App\Models\Answer;
use Illuminate\Support\Facades\Auth;

class AnswerController extends Controller
{
    public function create($id) {
        $question = Question::findOrFail($id);
        return view('answer.create', compact('question'), ['mode' => 'input']);
    }
    public function store(AnswerRequest $request) {
        $input = $request->validated();

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
            return view('answer.create', ['mode' => 'confirm', 'questionInput' => $question, 'input' => $input]);
        }
        if ($mode === 'edit') {
            return view('answer.create', ['mode' => 'input', 'questionInput' => $question, 'input' => $input]);
        }
        if ($mode === 'post') {
            $imagePath = null;
            if (session('confirm_image_path')) {
                $tempPath = session('confirm_image_path');
                $newPath = 'images/' . basename($tempPath);
                Storage::disk('public')->move($tempPath, $newPath);
                $imagePath = $newPath;

                //一時保存のデータを削除
                Storage::disk('public')->delete(session('confirm_image_path'));
                session()->forget('confirm_image_path');
            }

            Answer::create([
                'user_id' => Auth::id(),
                'question_id' => $input['question_id'],
                'content' => $request->content,
                'image_url' => $imagePath,
            ]);
        }
        return redirect()->route('question.show',['id' => $input['question_id']]);
    }
}
