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


    <aside class="w-[300px] bg-[var(--bg-light-gray)] shadow-lg h-screen p-6 flex flex-col">

        <!-- ロゴ -->
        <div class="mb-12 mt-5 flex items-center space-x-2">
            <img src="{{ asset('images/tomototomato.png') }}" class="w-[25px] h-[28px] object-cover">
            <img src="{{ asset('images/logo.png') }}" class="w-[68px] h-[29px] object-contain">
        </div>

        <!-- ナビゲーションメニュー -->
        <nav class="space-y-5">
            <div class="flex items-center space-x-4">
                <img src="{{ asset('images/account_circle_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.svg') }}" class="w-6 h-6">
                <a href="#" class="text-lg font-medium hover:text-[var(--accent-yellow)]">マイページ</a>
            </div>

            <div class="flex items-center space-x-4">
                <img src="{{ asset('images/import_contacts_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.svg') }}" class="w-6 h-6">
                <a href="#" class="text-lg font-medium hover:text-[var(--accent-yellow)]">学習計画</a>
            </div>

            <div class="flex items-center space-x-4">
                <img src="{{ asset('images/signal_cellular_alt_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.svg') }}" class="w-6 h-6">
                <a href="#" class="text-lg font-medium hover:text-[var(--accent-yellow)]">学習データ</a>
            </div>

            <div class="flex items-center space-x-4">
                <img src="{{ asset('images/timer_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.svg') }}" class="w-6 h-6">
                <a href="#" class="text-lg font-medium hover:text-[var(--accent-yellow)]">タイマー</a>
            </div>

            <div class="flex items-center space-x-4">
                <img src="{{ asset('images/send_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.svg') }}" class="w-6 h-6">
                <a href="#" class="text-lg font-medium hover:text-[var(--accent-yellow)]">共有</a>
            </div>
        </nav>
    </aside>

    <div class="mb-12 mt-5 flex-1 p-6">
        <header class="mb-10">
            <h1 class="text-2xl font-semibold">マイページ</h1>
        </header>

        <section class="bg-[var(--bg-light-gray)] p-6 rounded-lg shadow-lg">
            <div class="flex items-center space-x-6">
                @if ($user->avatar)
                    <div class="w-24 h-24 rounded-full border-4 border-[var(--accent-yellow)] shadow overflow-hidden">
                        <img class="w-full h-full object-cover"
                            src="{{ asset('storage/' . $user->avatar) }}"
                            alt="{{ $user->name }}のアバター">
                    </div>
                @else
                    <div class="w-24 h-24 rounded-full bg-[var(--bg-green)] flex items-center justify-center text-2xl text-white shadow">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif

                <div>
                    <h2 class="text-2xl font-semibold text-[var(--text-brown)]">{{ $user->name }}</h2>
                </div>
            </div>

            <div class="mt-6 bg-white p-4 rounded-lg shadow">
                <h3 class="text-lg font-semibold text-gray-800">自己紹介</h3>
                <p class="text-sm mt-2 text-gray-600">
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
    </div>

</body>
</html>
