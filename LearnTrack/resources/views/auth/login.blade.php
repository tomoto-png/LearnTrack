<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <title>ログイン</title>
</head>
<body class="bg-[#b3cfad] min-h-screen flex items-center justify-center">
    <div class="bg-[#e3e6d8] w-full max-w-md rounded-lg shadow-lg p-6">
        <div class="text-center mb-6">
            <h1 class="text-[#9f9579] text-3xl font-bold">ログイン</h1>
        </div>
        @if ($errors->has('login_error'))
        <div class="text-red-500 text-center mb-4">
            <ul>
                <li>{{ $errors->first('login_error') }}</li>
            </ul>
        </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="email" class="block text-[#9f9579] font-medium">メールアドレス</label>
                <div class="relative">
                    <img src="{{ asset('images/mail_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.svg') }}"
                        class="absolute top-1/2 left-3 transform -translate-y-[40%] w-6 h-6">
                    <input type="text" id="email" name="email" value="{{ old("email") }}"
                    class="w-full mt-1 p-2 px-10 border border {{ $errors->has('email') ? 'border-red-500' : 'border-[#9f9579]'}} rounded-md focus:outline-none focus:ring-2 focus:ring-[#d9ca79] hover:scale-103 hover:shadow-lg transition-all duration-200"/>
                </div>
                @error('email')
                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-[#9f9579] font-medium">パスワード</label>
                <div class="relative">
                    <img src="{{ asset('images/lock_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.svg') }}"
                        class="absolute top-1/2 left-3 transform -translate-y-[40%] w-6 h-6">
                    <input type="password" id="password" name="password" value="{{ old('password') }}"
                    class="w-full mt-1 p-2 px-10 border border {{ $errors->has('password') ? 'border-red-500' : 'border-[#9f9579]'}} rounded-md focus:outline-none focus:ring-2 focus:ring-[#d9ca79] hover:scale-103 hover:shadow-lg transition-all duration-200">
                    <button type="button" id="toggle-password"
                        class="absolute top-1/2 right-3 transform -translate-y-1/3">
                        <!-- アイコン表示用 -->
                        <img id="eye-icon" src="{{ asset('images/eye-slash-regular.svg') }}" alt="eye-icon" class="w-6 h-6 cursor-pointer">
                    </button>
                </div>
                @error('password')
                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <button type="submit"
                class="w-full bg-[#d9ca79] text-white font-semibold py-2 px-4 rounded-md hover:bg-[#c5b56b] transition-colors">
                    ログイン
                </button>
            </div>
            <div class="mt-4 text-center text-sm flex justify-between">
                <a href="{{ route('register') }}" class="text-[#9f9579] hover:underline">アカウントを作成</a>
                <a href="#" class="text-[#9f9579] hover:underline">パスワードを忘れた？</a>
            </div>
        </form>
    </dev>

    <script>
        document.getElementById('toggle-password').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');

            // パスワードの表示/非表示を切り替え
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.src = '{{ asset("images/eye-regular.svg") }}'; // アイコンを切り替え
            } else {
                passwordInput.type = 'password';
                eyeIcon.src = '{{ asset("images/eye-slash-regular.svg") }}'; // アイコンを元に戻す
            }
        });

    </script>
</body>
</html>
