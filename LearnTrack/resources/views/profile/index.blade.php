<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>マイページ</title>
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

    <div id="sidebar" class="fixed inset-y-0 left-0 w-72 shadow-md bg-white z-20 hidden md:block">
        @include('components.sidebar')
    </div>

    <div id="mainContent" class="flex-1 p-4 sm:p-6 mt-4 md:ml-72 transition-all">
        <header class="flex sm:flex-row justify-between items-center space-y-4 sm:space-y-0 mb-8">
            <!-- タイトルとハンバーガーメニュー -->
            <div class="flex items-center justify-between w-full sm:w-auto">
                <h1 class="text-xl sm:text-2xl font-semibold">マイページ</h1>
                <button id="menuButton"
                    class="fixed top-5 right-5 bg-[var(--accent-yellow)] text-white p-3 rounded-lg shadow-lg hover:bg-yellow-500 transition-transform transform hover:scale-110 md:hidden z-[9999]">
                    <img id="menuIcon" src="{{ asset('images/menu_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.svg') }}" class="w-6 h-6">
                </button>
            </div>
        </header>

        <section class="bg-[var(--bg-light-gray)] p-6 rounded-lg shadow-lg">
            <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-6">
                @if ($user->avatar)
                    <div class="w-24 h-24 rounded-full border-4 border-[var(--accent-yellow)] shadow overflow-hidden sm:w-32 sm:h-32">
                        <img class="w-full h-full object-cover"
                            src="{{ asset('storage/' . $user->avatar) }}"
                            alt="{{ $user->name }}のアバター">
                    </div>
                @else
                    <div class="w-24 h-24 rounded-full bg-[var(--bg-green)] flex items-center justify-center text-2xl text-white shadow sm:w-32 sm:h-32">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif

                <div class="mt-4 sm:mt-0">
                    <h2 class="text-2xl sm:text-3xl font-semibold text-[var(--text-brown)]">{{ $user->name }}</h2>
                </div>
            </div>

            <div class="mt-6 bg-white p-4 rounded-lg shadow">
                <h3 class="text-lg sm:text-xl font-semibold text-gray-800">自己紹介</h3>
                <p class="text-sm sm:text-base mt-2 text-gray-600">
                    {{ $user->bio ?? '自己紹介はまだ設定されていません。' }}
                </p>
            </div>
        </section>

        <div class="mt-6 text-right">
            <a href="{{ route('profile.edit') }}"
               class="inline-block bg-[var(--accent-yellow)] text-white px-4 py-2 rounded-lg font-semibold hover:bg-yellow-500 transition">
                プロフィール編集
            </a>
        </div>
        <form action="{{ route('logout') }}" method="POST" class="inline ml-3 w-full sm:w-auto">
            @csrf
            <button type="submit" class="bg-red-400 text-lg text-white px-4 py-2 rounded-lg hover:bg-red-600 w-full sm:w-auto">
                ログアウト
            </button>
        </form>
    </div>

    <script>
        document.getElementById("menuButton").addEventListener("click", function() {
            const sidebar = document.getElementById("sidebar");
            sidebar.classList.toggle("hidden");
        });
    </script>
</body>
</html>
