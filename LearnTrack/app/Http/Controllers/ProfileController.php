<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Question;
use App\Models\Answer;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $filter = $request->query('filter', 'question');
        $status = $request->input('status');
        $userWithCounts = User::withCount(['questions', 'answers'])
            ->find($user->id);
        if ($filter === 'question') {
            $questionQuery = Question::where('user_id', $user->id)
                ->with('category');
            switch ($status){
                case 'open':
                    $questionQuery->where('is_closed', false);
                    break;
                case 'closed':
                    $questionQuery->where('is_closed', true);
                    break;
                case 'no_best':
                    $questionQuery->whereDoesntHave('answers', function ($query) {
                        $query->where('is_best', true);
                    });
                    break;
                default:
                    break;
            }
            $questionQuery->orderBy('created_at', 'desc');
            $datas = $questionQuery->paginate(4);
        } elseif ($filter === 'answer') {
            $answerQuery = Answer::where('user_id', $user->id)
                ->with('question');

            switch ($status) {
                case 'open':
                    $answerQuery->whereHas('question', function ($query) {
                        $query->where('is_closed', false);
                    });
                    break;
                case 'closed':
                    $answerQuery->whereHas('question', function ($query) {
                        $query->where('is_closed', true);
                    });
                    break;
                case 'best':
                    $answerQuery->where('is_best', true);
                    break;
                default:
                    break;
            }
            $answerQuery->orderBy('created_at', 'desc');
            $datas = $answerQuery->paginate(4);
        }

        if ($request->ajax()) {
            $html = view('components.profile_items', [
                'datas' => $datas,
                'filter' => $filter
            ])->render();

            return response()->json([
                'html' => $html,
            ]);
        }

        return view('profile.index', compact('user', 'userWithCounts', 'datas', 'filter'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:20',
            'avatar' => 'nullable|image|max:2048',
            'bio' => 'nullable|string|max:200',
            'gender' => 'nullable|string',
            'age' => 'nullable|string',
            'occupation' => 'nullable|string|max:20',
        ],[
            'name.required' => '名前を入力してください。',
            'name.string' => '名前は文字列で入力してください。',
            'bio.string' => '自己紹介文は文字列で入力してください。',
            'bio.max' => '自己紹介文は200文字以内で入力してください。',
            'avatar.image' => '画像形式でアップロードしてください。',
            'avatar.max' => '画像は2MB以内でアップロードしてください。',
            'gender.string' => '性別は文字列で入力してください！',
            'age.string' => '年齢は文字列で入力してください！',
            'occupation.string' => '職業は文字列で入力してください！',
            'occupation.max' => '職業は20文字以内で入力してください！'
        ]);
        try {
            DB::transaction(function () use ($request, $user) {
                if ($request->hasFile('avatar')) {
                    if ($user->avatar) {
                        // S3上のパスを取得するため、URLからパスを抜き出す
                        $oldPath = str_replace(Storage::disk('s3')->url(''), '', $user->avatar);//接頭辞を削除したファイルパスを残す、str_replaceは文字列の一部置き換え
                        Storage::disk('s3')->delete($oldPath);
                    }
                    $s3Path = $request->file('avatar')->store('uploads/avatars', 's3');
                    $avatar = Storage::disk('s3')->url($s3Path);
                } else {
                    $avatar = $user->avatar;
                }
                $user->update([
                    'name' => $request->name,
                    'bio' => $request->bio,
                    'avatar' => $avatar,
                    'gender' => $request->gender,
                    'age' => $request->age,
                    'occupation' => $request->occupation,
                ]);
            });
        } catch (QueryException $e) {
            return redirect()->back()->withErrors(['error' => '回答の投稿が失敗しました']);
        }

        return redirect()->route('profile.index');
    }

    public function show($id) {
        $user = User::findOrFail($id);
        return view('profile.show', compact('user'));
    }
}
