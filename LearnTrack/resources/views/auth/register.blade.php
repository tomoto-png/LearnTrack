<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新規登録</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#b3cfad] min-h-screen flex items-center justify-center">
    <!-- Registration Form Container -->
    <div class="bg-[#e3e6d8] w-full max-w-lg rounded-lg shadow-lg p-8">
        <div class="text-center mb-6">
            <h1 class="text-[#9f9579] text-3xl font-bold"></h1>
        </div>
        <form action="{{ url('register') }}" method="POST" class="space-y-4">
            @csrf
            <!-- Name Field -->
            <div>
                <label for="name" class="block text-[#9f9579] font-medium">名前</label>
                <input type="text" id="name" name="name" required
                    class="w-full mt-2 p-2 border border-[#9f9579] rounded-md focus:outline-none focus:ring-2 focus:ring-[#d9ca79]">
            </div>

            <!-- Email Field -->
            <div>
                <label for="email" class="block text-[#9f9579] font-medium">メールアドレス</label>
                <input type="email" id="email" name="email" required
                    class="w-full mt-2 p-2 border border-[#9f9579] rounded-md focus:outline-none focus:ring-2 focus:ring-[#d9ca79]">
            </div>

            <!-- Password Field -->
            <div class="relative">
                <label for="password" class="block text-[#9f9579] font-medium">パスワード</label>
                <input type="password" id="password" name="password" required
                    class="w-full mt-2 p-2 border border-[#9f9579] rounded-md focus:outline-none focus:ring-2 focus:ring-[#d9ca79]">
                <button type="button" id="toggle-password" class="absolute top-[72%] right-3 transform -translate-y-1/2 text-[#9f9579]">
                    <!-- アイコン表示用 -->
                    <img id="eye-icon" src="{{ asset('images/eye-slash-regular.svg') }}" alt="eye-icon" class="w-6 h-6 cursor-pointer">
                </button>
            </div>            

            <!-- Confirm Password Field -->
            <div>
                <label for="password_confirmation" class="block text-[#9f9579] font-medium">パスワード確認</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required
                    class="w-full mt-2 p-2 border border-[#9f9579] rounded-md focus:outline-none focus:ring-2 focus:ring-[#d9ca79]">
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit"
                    class="w-full bg-[#d9ca79] text-white font-semibold py-2 px-4 rounded-md hover:bg-[#c5b56b] transition-colors">
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