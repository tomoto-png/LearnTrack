<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('profile.index', compact('user'));
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
            'name' => 'required|string|max:255',
            'bio' => 'required|string|max:500',
            'avatar' => 'nullable|image|max:2048',
        ],[
            'name.required' => '名前を入力してください。',
            'name.string' => '名前は文字列で入力してください。',
            'bio.required' => '自己紹介文を入力してください。',
            'bio.string' => '自己紹介文は文字列で入力してください。',
            'bio.max' => '自己紹介文は500文字以内で入力してください。',
            'avatar.image' => '画像形式でアップロードしてください。',
            'avatar.max' => '画像は2MB以内でアップロードしてください。',
        ]);

        $user->update([
            'name' => $request->input('name'),
            'bio' => $request->input('bio'),
        ]);

        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath;
            $user->save();
        }

        return redirect()->route('profile.index');
    }
}
