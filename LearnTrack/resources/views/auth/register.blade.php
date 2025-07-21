<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>新規登録</title>
    <style>
        :root {
            --bg-green: #a0b89c;
            --bg-light-gray: #d6d9c8;
            --text-brown: #6b5e3f;
            --button-bg: #6c8c5d;
            --button-hover: #57724a;
            --white: white;
        }
    </style>
</head>
<body class="bg-[var(--bg-green)] text-[var(--text-brown)] min-h-screen flex justify-center items-center">
    <div class="px-4 w-full max-w-lg">
        <div class="bg-[var(--bg-light-gray)] rounded-lg shadow-lg p-8">
            <div class="text-center mb-6">
                <h1 class="text-xl md:text-3xl font-bold">新規登録</h1>
            </div>
            <form action="{{ route('register') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="email" class="block font-medium">メールアドレス</label>
                    <div class="relative">
                        <img src="{{ asset('images/mail_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.svg') }}"
                            class="absolute top-1/2 left-3 transform -translate-y-[40%] w-6 h-6">
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            class="w-full mt-1 p-2 px-10 border border-[var(--text-brown)] rounded-md focus:outline-none focus:ring-2 focus:ring-[var(--text-brown)] hover:scale-103 hover:shadow-lg transition-all duration-200"
                        />
                    </div>
                    @error('email')
                        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block font-medium">パスワード</label>
                    <div class="relative">
                        <img src="{{ asset('images/lock_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.svg') }}"
                            class="absolute top-1/2 left-3 transform -translate-y-[40%] w-6 h-6">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            minlength="8"
                            class="w-full mt-1 p-2 px-10 border border-[var(--text-brown)] rounded-md focus:outline-none focus:ring-2 focus:ring-[var(--text-brown)] hover:scale-103 hover:shadow-lg transition-all duration-200"
                            required
                        />
                        <button type="button" id="toggle-password"
                            class="absolute top-1/2 right-3 transform -translate-y-[40%]">
                            <img id="eye-icon" src="{{ asset('images/eye-slash-regular.svg') }}" alt="eye-icon" class="w-6 h-6 cursor-pointer">
                        </button>
                    </div>
                    @error('password')
                        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block font-medium">パスワード確認</label>
                    <div class="relative">
                        <img src="{{ asset('images/lock_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.svg') }}"
                            class="absolute top-1/2 left-3 transform -translate-y-[40%] w-6 h-6">
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            minlength="8"
                            class="w-full mt-1 p-2 px-10 border border-[var(--text-brown)] rounded-md focus:outline-none focus:ring-2 focus:ring-[var(--text-brown)] hover:scale-103 hover:shadow-lg transition-all duration-200"
                            required
                        />
                    </div>
                    @error('password_confirmation')
                        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <button type="submit" class="w-full bg-[var(--button-bg)] text-[var(--white)] font-semibold mt-2 py-2 px-4 rounded-md hover:bg-[var(--button-hover)] transition-colors">
                        登録
                    </button>
                </div>
                <div class="mt-4 text-center text-sm">
                    <a href="{{ route('login') }}" class="hover:underline">アカウントをお持ちの方はこちら</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('toggle-password').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');

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
