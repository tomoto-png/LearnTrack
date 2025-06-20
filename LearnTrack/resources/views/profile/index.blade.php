<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>マイページ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        :root {
            --bg-green: #a0b89c;
            --bg-light-gray: #d6d9c8;
            --text-brown: #6b5e3f;
            --button-bg: #6c8c5d;
            --button-hover: #57724a;
            --accent-color: #3f5c38;
            --white: white;
        }
    </style>
</head>
<body class="bg-[var(--bg-green)] text-[var(--text-brown)] min-h-screen flex">

    <aside id="sidebar"
        class="fixed top-0 left-0 w-72 h-screen bg-[var(--bg-light-gray)] shadow-lg p-6 z-50
            transform -translate-x-full transition-transform duration-300 ease-in-out
            lg:translate-x-0">
        @include('components.sidebar')
    </aside>

    <div id="mainContent" class="flex-1 p-4 mt-4 sm:p-6 sm:mt-6 lg:ml-72">
        <header class="flex sm:flex-row justify-between items-center space-y-4 sm:space-y-0 mb-8">
            <div class="flex items-center justify-between w-full sm:w-auto">
                <h1 class="text-xl sm:text-2xl font-semibold">マイページ</h1>
                <button id="menuButton"
                    class="fixed top-7 right-6 sm:top-10 sm:right-8 bg-[var(--accent-color)] text-[var(--white)] p-2 rounded-lg shadow-lg hover:bg-[var(--button-hover)] transition-transform transform hover:scale-110 lg:hidden z-50">
                    <img id="menuIcon" src="{{ asset('images/menu_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.svg') }}" class="w-6 h-6">
                </button>
            </div>
        </header>

        <div class="bg-[var(--bg-light-gray)] p-8 rounded-lg shadow-lg">
            <div class="flex justify-between items-center">
                <div class="flex flex-col md:flex-row md:space-x-4 items-center">
                    @if ($user->avatar)
                        <div class="w-20 h-20 rounded-full border-2 border-[var(--accent-color)] shadow overflow-hidden md:w-28 md:h-28">
                            <img class="w-full h-full object-cover"
                                src="{{ $user->avatar }}"
                                alt="{{ $user->name }}のアバター">
                        </div>
                    @else
                        <div class="w-20 h-20 rounded-full bg-[var(--bg-green)] border-2 border-[var(--accent-color)] flex items-center justify-center text-2xl text-[var(--white)] md:w-28 md:h-28">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif

                    <h2 class="text-xl md:text-3xl font-semibold mt-1 md:mt-0">{{ $user->name }}さん</h2>
                </div>
                <div>
                    <a href="{{ route('profile.edit') }}"
                        class="inline-flex items-center gap-2 bg-[var(--button-bg)] text-[var(--white)] px-2 py-1 sm:px-4 sm:py-2 rounded-lg font-medium hover:bg-[var(--button-hover)] transition-all duration-300">
                        <img src="{{ asset('images/edit_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.svg') }}"
                            alt="編集アイコン"
                            class="w-5 h-5">
                        <span class="text-base">編集</span>
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 mt-4 p-2 md:grid-cols-3 md:gap-12 md:p-4">
                <!-- プロフィール -->
                <div class="flex-1">
                    <div class="flex items-center mb-2 sm:mb-4">
                        <h3 class="text-lg font-semibold">プロフィール</h3>
                        <div class="flex-grow border-t border-[var(--text-brown)] ml-4"></div>
                    </div>
                    <p class="mb-2 flex justify-between">
                    <span class="font-semibold">性別：</span>
                    <span>
                        @switch($user->gender)
                            @case('female')
                                女性
                                @break
                            @case('male')
                                男性
                                @break
                            @default
                                非公開
                        @endswitch
                    </span>
                    </p>
                    <p class="mb-2 flex justify-between">
                    <span class="font-semibold">年齢：</span>
                    <span>
                        @switch($user->age)
                            @case('under_10')
                                10歳未満
                                @break
                            @case('10s')
                                10代
                                @break
                            @case('20s')
                                20代
                                @break
                            @case('30_and_over')
                                30歳以上
                                @break
                            @default
                                非公開
                        @endswitch
                    </span>
                    </p>
                    <p class="mb-2 flex justify-between">
                        <span class="font-semibold whitespace-nowrap">職業：</span>
                        <span class="break-words text-left max-h-6 overflow-y-auto">
                            {{ $user->occupation ?? '非公開' }}
                        </span>
                    </p>
                </div>

                <div class="flex-1">
                    <div class="flex items-center mb-2 sm:mb-4">
                        <h3 class="text-lg font-semibold">活動状況</h3>
                        <div class="flex-grow border-t border-[var(--text-brown)] ml-4"></div>
                    </div>
                    <p class="mb-2 flex justify-between">
                        <span class="font-semibold">トマト数：</span>
                        <span>{{ $userWithCounts->count ?? 0 }}個</span>
                    </p>
                    <p class="mb-2 flex justify-between">
                        <span class="font-semibold">質問回数：</span>
                        <span>{{ $userWithCounts->questions_count?? 0 }}回</span>
                    </p>
                    <p class="mb-0 flex justify-between">
                        <span class="font-semibold">回答回数：</span>
                        <span>{{ $userWithCounts->answers_count ?? 0 }}回</span>
                    </p>
                </div>
                <!-- 自己紹介 -->
                <div class="flex-1">
                    <div class="flex items-center mb-2 sm:mb-4">
                        <h3 class="text-lg font-semibold">自己紹介</h3>
                        <div class="flex-grow border-t border-[var(--text-brown)] ml-4"></div>
                    </div>
                    <div class="max-h-24 overflow-y-auto break-words">
                        <p class="text-sm sm:text-base text-[var(--text-main)]">
                            {{ $user->bio ?? '自己紹介はまだ設定されていません。' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6 bg-[var(--bg-light-gray)] rounded-lg overflow-hidden shadow-lg p-8">
            <div class="flex justify-center sm:justify-start gap-4 text-lg">
                <a href="{{ route('profile.index', ['filter' => 'question']) }}"
                   class="pb-1 border-b-2 transition duration-200 {{ $filter == 'question' ? 'border-[var(--accent-color)] text-[var(--accent-color)] font-semibold' : 'border-transparent' }}">
                    質問一覧
                </a>
                <a href="{{ route('profile.index', ['filter' => 'answer']) }}"
                   class="pb-1 border-b-2 transition duration-200 {{ $filter == 'answer' ? 'border-[var(--accent-color)] text-[var(--accent-color)] font-semibold' : 'border-transparent' }}">
                    回答一覧
                </a>
            </div>
            <div class="mt-4">
                <div id="filter-buttons"
                    class="flex flex-wrap gap-2 sm:flex-nowrap">
                    <button data-status=""
                            class="filter-button py-1 px-3 rounded-full border border-[var(--text-brown)] bg-[var(--button-bg)] text-[var(--white)]">
                        全て
                    </button>
                    <button data-status="open"
                            class="filter-button py-1 px-3 rounded-full border border-[var(--text-brown)]">
                        回答受付中
                    </button>
                    <button data-status="closed"
                            class="filter-button py-1 px-3 rounded-full border border-[var(--text-brown)]">
                        解決済み
                    </button>
                    @if ($filter === 'question')
                        <button data-status="no_best"
                                class="filter-button py-1 px-3 rounded-full border border-[var(--text-brown)]">
                            ベストアンサー未選択
                        </button>
                    @else
                        <button data-status="best"
                                class="filter-button py-1 px-3 rounded-full border border-[var(--text-brown)]">
                            ベストアンサー
                        </button>
                    @endif
                </div>
            </div>
            <div class="mt-6 sm:mt-8" id="data-list">
                @include('components.profile_items', ['datas' => $datas, 'filter' => $filter])
            </div>
            <x-pagination.custom :paginator="$datas" />
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const buttons = document.querySelectorAll('.filter-button');
            const filter = "{{ $filter }}";

            buttons.forEach(button => {
                button.addEventListener('click', function () {
                    const status = this.dataset.status;

                    // ボタンの見た目を切り替え
                    buttons.forEach(btn => btn.classList.remove('bg-[var(--button-bg)]', 'text-[var(--white)]'));
                    this.classList.add('bg-[var(--button-bg)]', 'text-[var(--white)]');

                    $.ajax({
                        url: 'profile',
                        method: 'GET',
                        data: {
                            filter: filter,
                            status: status
                        },
                        success: function(response) {
                            document.querySelector('#data-list').innerHTML = response.html;
                        },
                        error: function(xhr, status, error) {
                            if (xhr.status === 419 || xhr.status === 401) {
                                alert('セッションが切れました。再度ログインしてください。');
                                window.location.href = '/login';
                            } else {
                                console.error('送信エラー:', error);
                            }
                        }
                    });
                });
            });
        });
        document.getElementById("menuButton").addEventListener("click", function() {
            const sidebar = document.getElementById("sidebar");
            sidebar.classList.toggle("-translate-x-full");
        });
    </script>
</body>
</html>
