<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


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
            'bio' => 'nullable|string|max:500',
            'avatar' => 'nullable|image|max:2048',
        ],[
            'name.required' => '名前を入力してください。',
            'name.string' => '名前は文字列で入力してください。',
            'bio.string' => '自己紹介文は文字列で入力してください。',
            'bio.max' => '自己紹介文は500文字以内で入力してください。',
            'avatar.image' => '画像形式でアップロードしてください。',
            'avatar.max' => '画像は2MB以内でアップロードしてください。',
        ]);

        if ($request->hasFile('avatar')) {
            $s3Path = $request->file('avatar')->store('uploads/avatars', 's3');
            $avatar = Storage::disk('s3')->url($s3Path);
        }
        $user->update([
            'name' => $request->name,
            'bio' => $request->bio,
            'avatar' => $avatar,
        ]);

        return redirect()->route('profile.index');
    }
}
