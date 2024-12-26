<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'アプリ名')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --bg-green: #b3cfad;
            --bg-light-gray: #e3e6d8;
            --text-brown: #9f9579;
            --accent-yellow: #d9ca79;
        }
    </style>
</head>
<body class="bg-[var(--bg-green)] text-[var(--text-brown)] min-h-screen flex">
    <!-- サイドバー -->
    <aside class="w-64 bg-[var(--bg-light-gray)] shadow-lg h-screen p-6">
        <nav class="space-y-6">
            <a href="/dashboard" class="block text-lg font-medium hover:text-[var(--accent-yellow)]">
                ダッシュボード
            </a>
            <a href="/mypage" class="block text-lg font-medium hover:text-[var(--accent-yellow)]">
                マイページ
            </a>
            <a href="/plans" class="block text-lg font-medium hover:text-[var(--accent-yellow)]">
                学習計画
            </a>
            <a href="/timer" class="block text-lg font-medium hover:text-[var(--accent-yellow)]">
                タイマー
            </a>
            <a href="/posts" class="block text-lg font-medium hover:text-[var(--accent-yellow)]">
                投稿
            </a>
        </nav>
    </aside>

    <!-- メインコンテンツ -->
    <div class="flex-1 p-6">
        @yield('content') <!-- 各ページごとのコンテンツを挿入 -->
    </div>
</body>
</html>
