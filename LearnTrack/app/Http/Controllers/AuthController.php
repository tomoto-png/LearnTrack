<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed'
        ],[
            'email.required' => 'メールアドレスを入力してください！',
            'email.email' => '正しいメールアドレスの形式で入力してください!',
            'email.unique' => 'このメールアドレスは既に登録されています！',
            'password.required' => 'パスワードを入力してください。',
            'password.min' => 'パスワードは8文字以上である必要があります。',
            'password.confirmed' => 'パスワードが一致しません。'
        ]);

        do {
            $name = 'user_' . Str::random(8);
        } while (User::where('name', $name)->exists());//(条件が true);の時に再度生成する

        $user = User::create([
            'name' => $name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        Auth::login($user);
        return redirect()->route('profile.index');
    }
    public function showLoginForm()
    {
        return view('auth.login');
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ],[
            'email.required' => 'メールアドレスを入力してください！',
            'email.email' => '正しいメールアドレスの形式で入力してください！',
            'password.required' => 'パスワードを入力してください！',
            'password.min' => 'パスワードは8文字以上で入力してください！'
        ]);
        if (Auth::attempt($request->only('email','password'), $request->filled('remember'))) {
            return redirect()->route('profile.index');
        } else {
            return back()->withErrors(['login_error' => 'メールアドレスまたはパスワードが間違っています。']);
        }
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
