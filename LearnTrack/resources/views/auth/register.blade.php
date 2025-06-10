<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新規登録</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#b3cfad] min-h-screen flex items-center justify-center">
    <div class="bg-[#e3e6d8] w-full max-w-lg rounded-lg shadow-lg p-8">
        <div class="text-center mb-6">
            <h1 class="text-[#9f9579] text-3xl font-bold">新規登録</h1>
        </div>
        <form action="{{ route('register') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="email" class="block text-[#9f9579] font-medium">メールアドレス</label>
                <div class="relative">
                    <img src="{{ asset('images/mail_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.svg') }}"
                        class="absolute top-1/2 left-3 transform -translate-y-[40%] w-6 h-6">
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                        class="w-full mt-1 p-2 px-10 border border-{{ $errors->has('email') ? 'border-red-500' : 'border-[#9f9579]'}} rounded-md focus:outline-none focus:ring-2 focus:ring-[#d9ca79] hover:scale-103 hover:shadow-lg transition-all duration-200">
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
                    <input type="password" id="password" name="password"
                        class="w-full mt-1 p-2 px-10 border border-{{ $errors->has('password') ? 'border-red-500' : 'border-[#9f9579]'}} rounded-md focus:outline-none focus:ring-2 focus:ring-[#d9ca79] hover:scale-103 hover:shadow-lg transition-all duration-200">
                    <button type="button" id="toggle-password"
                        class="absolute top-1/2 right-3 transform -translate-y-[40%]">
                        <img id="eye-icon" src="{{ asset('images/eye-slash-regular.svg') }}" alt="eye-icon" class="w-6 h-6 cursor-pointer">
                    </button>
                </div>
                @error('password')
                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Confirm Password Field -->
            <div>
                <label for="password_confirmation" class="block text-[#9f9579] font-medium">パスワード確認</label>
                <div class="relative">
                    <img src="{{ asset('images/lock_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.svg') }}"
                        class="absolute top-1/2 left-3 transform -translate-y-[40%] w-6 h-6">
                    <input type="password" id="password_confirmation" name="password_confirmation"
                        class="w-full mt-1 p-2 px-10 border border-{{ $errors->has('password_confirmation') ? 'border-red-500' : 'border-[#9f9579]'}} rounded-md focus:outline-none focus:ring-2 focus:ring-[#d9ca79] hover:scale-103 hover:shadow-lg transition-all duration-200">
                </div>
                @error('password_confirmation')
                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div>
                <button type="submit" class="w-full bg-[#d9ca79] text-white font-semibold mt-3 py-2 px-4 rounded-md hover:bg-[#c5b56b] transition-colors">
                    登録
                </button>
            </div>
            <div class="mt-4 text-center text-sm">
                <a href="{{ route('login') }}" class="text-[#9f9579] hover:underline">アカウントをお持ちの方はこちら</a>
            </div>
        </form>
    </div>

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
