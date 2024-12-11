<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.register');
    }
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('dashboard')->with('success', '登録が完了しました！ログインしてください。');
    }
    public function showLoginForm()
    {
        return view('auth.login');
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
        if (auth()->attempt($request->only('email', 'password'), $request->filled('remember'))) {
            return redirect()->route('dashboard');
        }
        return back()->withErrors(['email' => 'The provided credentials are incorrect.']);
    }
    public function logout(Request $request)
    {
        Auth::logout(); // ログアウト処理
        $request->session()->invalidate(); // セッションの無効化
        $request->session()->regenerateToken(); // セッションの再生成
    
        return redirect()->route('login'); // ログインページにリダイレクト
    }
}
