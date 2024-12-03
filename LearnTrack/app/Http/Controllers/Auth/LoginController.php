<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    //フォーム
    public function showLoginForm()
    {
        return view('auth.login');
    }
    public function login(Request $request)
    {
        // バリデーション
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // ユーザーの認証
        if (Auth::attempt(['email' => $validated['email'], 'password' => $validated['password']])) {
            // 認証に成功した場合、リダイレクト
            return redirect()->intended('/dashboard');
        } else {
            // 認証に失敗した場合、エラーメッセージ
            return back()->withErrors(['email' => 'ログイン情報が間違っています'])->withInput();
        }
    }

    /**
     * ログアウト処理
     */
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/');
    }
}
