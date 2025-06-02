<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\CategoryGroup;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\QuestionRequest;
use Illuminate\Http\File;

class QuestionController extends Controller
{
    public function index()
    {
        $questionDatas = Question::with('category')->get();
        return view('question.index', compact('questionDatas'));
    }
    public function show($id)
    {
        $questionData = Question::with(['answers' => function($query) {
            $query->orderBy('created_at', 'desc');
        }, 'answers.user', 'answers.answerReply.user','category'])
        ->find($id);

        $questionOwnerId = $questionData->user_id;

        $relatedQuestions = Question::where('category_id', $questionData->category_id)
            ->where('id', '!=', $questionData->id)
            ->latest()
            ->take(5)
            ->get();

        $hasSameCategoryQuestions = $relatedQuestions->isNotEmpty();

        if (!$hasSameCategoryQuestions) {
            $relatedQuestions = Question::where('id', '!=', $questionData->id)
            ->latest()
            ->take(5)
            ->get();
        }

        return view('question.show', compact('questionData', 'questionOwnerId', 'relatedQuestions','hasSameCategoryQuestions'));
    }
    public function create()
    {
        $user = Auth::user();
        return view('question.create', compact('user'), ['mode' => 'input']);
    }
    public function store(QuestionRequest $request)
    {
        $user = $request->user();
        $validated = $request->validated();
        if ($request->input('remove_image') === 'true') {
            if (session('temp_image_path')) {
                Storage::disk('public')->delete(session('temp_image_path'));//フォルダ内に保存された画像を消去
                session()->forget('temp_image_path');//セッション内の画像を消去する
            }
            $validated['image_url'] = null;
        } elseif ($request->hasFile('image_url')) {
            if (session('temp_image_path')) {
                Storage::disk('public')->delete(session('temp_image_path'));
            }

            $tempPath = $request->file('image_url')->store('temp', 'public');
            session(['temp_image_path' => $tempPath]); //セッションに確認用保存
            $validated['image_url'] = $tempPath; //フォルダ内に保存した画像を確認用データに再代入
        } elseif (session('temp_image_path')) {
            // 再投稿時など画像を変更しなければセッションを再利用
            $validated['image_url'] = session('temp_image_path');
        }
        $mode = $request->input('mode');
        Log::debug($validated);
        if ($mode === 'confirm') {
            $categoryGroups = CategoryGroup::with('categories')->get();
            return view('question.create', [
                'mode' => 'confirm',
                'input' => $validated,
                'categoryGroups' => $categoryGroups,
                'user' => $user
            ]);
        } elseif ($mode === 'edit') {
            return view('question.create', [
                'mode' => 'input',
                'input' => $validated,
                'user' => $user
            ]);
        } elseif ($mode === 'post') {
            $tempPath = session('temp_image_path');
            if ($tempPath) {
                $fullTempPath = storage_path('app/public/' . $tempPath);
                $s3Url = Storage::disk('s3')->putFile('uploads/question_images', new File($fullTempPath));
                $imageUrl = Storage::disk('s3')->url($s3Url);

                //一時保存のデータを削除
                Storage::disk('public')->delete($tempPath);
                session()->forget('temp_image_path');
            } else {
                $imageUrl = null;
            }

            Question::create([
                'user_id' => $user->id,
                'category_id' => $request->category_id,
                'content' => $request->content,
                'image_url' => $imageUrl,
                'auto_repost_enabled' => $request->has('auto_repost_enabled'),
                'reward' => $request->reward
            ]);
        }
        return redirect()->route('question.index');
    }
    public function cancel() {
        if (session('temp_image_path')) {
            Storage::disk('public')->delete(session('temp_image_path'));
            session()->forget('temp_image_path');
        }
        return redirect()->route('question.index');
    }
}
