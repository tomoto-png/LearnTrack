<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Answer;
use Carbon\Carbon;
use App\Models\CategoryGroup;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\QuestionRequest;
use Illuminate\Http\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class QuestionController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('keyword');
        $query = Question::with(['category', 'user']);

        if ($keyword) {
            $query->where('content', 'like', '%' . $keyword . '%');
        }

        switch ($request->input('sort','newest')) {
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'open':
                $query->where('is_closed', false)
                    ->orderBy('created_at', 'desc');
                break;
            case 'solved':
                $query->where('is_closed', true)
                    ->orderBy('created_at', 'desc');
                break;
            case 'fewest_answers':
                $query->withCount('answers')->orderBy('answers_count', 'asc');
                break;
            case 'most_answers':
                $query->withCount('answers')->orderBy('answers_count', 'desc');
                break;
            case 'least_reward':
                $query->orderBy('reward', 'asc');
                break;
            case 'most_reward':
                $query->orderBy('reward', 'desc');
                break;
        }

        $questionDatas = $query->paginate(5)->withQueryString();;

        return view('question.index', compact('questionDatas', 'keyword'));
    }

    public function show($id)
    {
        $questionData = Question::with(['answers' => function($query) {
            $query->where('is_best', false)->latest();
        }, 'answers.user', 'answers.answerReply.user','category'])
        ->find($id);

        if (!$questionData) {
            abort(404);
        }
        $questionOwnerId = $questionData->user_id;

        $bestAnswer = Answer::where('question_id', $id)
            ->where('is_best', true)
            ->with(['user', 'answerReply.user'])
            ->first();

        if ($questionData->is_closed === 1) {
            $remainingDays = 0;
        } else {
            $createdAt = Carbon::parse($questionData->created_at)->startOfDay(); // 作成日付を00:00に
            $now = Carbon::now()->startOfDay(); // 現在時刻を00:00に
            $daysElapsed = $createdAt->diffInDays($now); // 小数なしの差分日数
            $remainingDays = max(7 - $daysElapsed, 0);   // 残り日数（マイナスにならないように）
        }

        //関連カテゴリーを取得
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

        return view('question.show', compact('bestAnswer', 'questionData', 'questionOwnerId', 'relatedQuestions','hasSameCategoryQuestions', 'remainingDays'));
    }

    public function create()
    {
        $user = Auth::user();
        return view('question.create', compact('user'), ['mode' => 'input']);
    }

    public function store(QuestionRequest $request)
    {
        $user = Auth::user();
        $validated = $request->validated();
        if ($request->input('remove_image') === 'true') {//画像が削除された時の処理
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
        if ($mode === 'confirm') {
            $categoryGroups = CategoryGroup::with('categories')->get();
            return view('question.create', [
                'mode' => 'confirm',
                'input' => $validated,
                'categoryGroups' => $categoryGroups,
                'user' => $user
            ]);
        }
        if ($mode === 'edit') {
            return view('question.create', [
                'mode' => 'input',
                'input' => $validated,
                'user' => $user
            ]);
        }
        if ($mode === 'post') {
            try{
                DB::transaction(function () use ($user, $validated) {
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

                    $reward = $validated['reward'];

                    if ($user->count < $reward) {
                        return redirect()->back()->withErrors(['reward' => 'ポイントが不足しています']);
                    }

                    $user->update([
                        'count' => $user->count - $reward,
                    ]);

                    $question = [
                        'user_id' => $user->id,
                        'content' => $validated['content'],
                        'image_url' => $imageUrl,
                        'auto_repost_enabled' => isset($validated['auto_repost_enabled']),
                        'reward' => $reward
                    ];
                    if (isset($validated['category_id'])) {
                        $question['category_id'] = $validated['category_id'];
                    }

                    Question::create($question);
                });
            } catch (QueryException $e) {
                return redirect()->back()->withErrors(['error' => '質問の投稿が失敗しました']);
            }
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
