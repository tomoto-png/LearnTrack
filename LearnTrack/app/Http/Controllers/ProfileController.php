<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Question;
use App\Models\Answer;


class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $filter = $request->query('filter', 'question');
        $status = $request->query('status');
        $userWithCounts = User::withCount(['questions', 'answers'])
            ->find($user->id);
        if ($filter === 'question') {
            $questionQuery = Question::where('user_id', $user->id);

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

            $datas = $answerQuery->paginate(4);
        }

        return view('profile.index', compact('user', 'userWithCounts', 'datas', 'filter', 'status'));
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
            'bio' => 'nullable|string|max:500',
            'gender' => 'nullable|string',
            'age' => 'nullable|string',
            'occupation' => 'nullable|string|max:20',
        ],[
            'name.required' => '名前を入力してください。',
            'name.string' => '名前は文字列で入力してください。',
            'bio.string' => '自己紹介文は文字列で入力してください。',
            'bio.max' => '自己紹介文は500文字以内で入力してください。',
            'avatar.image' => '画像形式でアップロードしてください。',
            'avatar.max' => '画像は2MB以内でアップロードしてください。',
            'gender.string' => '性別は文字列で入力してください！',
            'age.string' => '年齢は文字列で入力してください！',
            'occupation.string' => '職業は文字列で入力してください！',
            'occupation.max' => '職業は20文字以内で入力してください！'
        ]);

        if ($request->hasFile('avatar')) {
            $s3Path = $request->file('avatar')->store('uploads/avatars', 's3');
            $avatar = Storage::disk('s3')->url($s3Path);
        } else {
            $avatar = null;
        }
        $user->update([
            'name' => $request->name,
            'bio' => $request->bio,
            'avatar' => $avatar,
            'gender' => $request->gender,
            'age' => $request->age,
            'occupation' => $request->occupation,
        ]);

        return redirect()->route('profile.index');
    }
}
