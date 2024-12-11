<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#b3cfad] min-h-screen flex items-center justify-center">

    <!-- Login Container -->
    <div class="bg-[#e3e6d8] w-full max-w-md rounded-lg shadow-lg p-6">
        <div class="text-center mb-6">
            <h1 class="text-[#9f9579] text-3xl font-bold">ログイン</h1>
        </div>
        
        <!-- Error Messages -->
        @if ($errors->any())
            <div class="text-red-500 text-center mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Login Form -->
        <form action="{{ route('login') }}" method="POST" class="space-y-4">
            @csrf
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
        
            <!-- Submit Button -->
            <div>
                <button type="submit"
                    class="w-full bg-[#d9ca79] text-white font-semibold py-2 px-4 rounded-md hover:bg-[#c5b56b] transition-colors">
                    ログイン
                </button>
            </div>
        </form>

        <!-- Links -->
        <div class="mt-4 text-center text-sm flex justify-between">
            <a href="{{ route('register') }}" class="text-[#9f9579] hover:underline">アカウントを作成</a>
            <a href="#" class="text-[#9f9579] hover:underline">パスワードを忘れた？</a>
        </div>
    </div>

    <script>
        // パスワード表示/非表示のトグル
        $('#toggle-password').click(function() {
            var passwordField = $('#password');
            var icon = $('#eye-icon');
            var type = passwordField.attr('type') === 'password' ? 'text' : 'password';

            // パスワードの表示状態を変更
            passwordField.attr('type', type);

            // アイコンの切り替え
            if (type === 'password') {
                icon.attr('src', '{{ asset('images/eye-slash-regular.svg') }}');
            } else {
                icon.attr('src', '{{ asset('images/eye-regular.svg') }}');
            }
        });
    </script>

</body>
</html>