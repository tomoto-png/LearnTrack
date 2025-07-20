<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>403 Forbidden</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
    <div class="px-6 max-w-xl w-full">
        <div class="p-6 lg:p-8 bg-[var(--bg-light-gray)] rounded-xl shadow-md text-center space-y-6">
            <div>
                <h1 class="text-2xl font-bold">419</h1>
                <h1 class="text-2xl font-bold">Page Expired</h1>
            </div>
            <div>
                <p class="text-xl">申し訳ありません！</p>
                <p class="text-xl">再度ログインして操作をお試しください。</p>
            </div>
            <div>
                <a href="{{ route('login') }}" class="px-4 py-2 rounded-lg bg-[var(--button-bg)] text-[var(--white)] font-semibold hover:bg-[var(--button-hover)] transition">ログイン</a>
            </div>
        </div>
    </div>
</body>
</html>
