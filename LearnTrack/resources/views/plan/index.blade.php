<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>学習計画一覧</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
<body class="bg-[var(--bg-green)] text-[var(--text-brown)] min-h-screen flex justify-center">
    <aside id="sidebar"
        class="fixed top-0 left-0 w-72 h-screen bg-[var(--bg-light-gray)] shadow-lg p-6 z-50
            transform -translate-x-full transition-transform duration-300 ease-in-out
            lg:translate-x-0">
        @include('components.sidebar')
    </aside>

    <div id="mainContent" class="flex-1 p-4 mt-4 sm:p-6 sm:mt-6 lg:ml-72">
        <header class="flex sm:flex-row justify-between items-center space-y-4 sm:space-y-0 mb-8">
            <div class="flex items-center justify-between w-full sm:w-auto">
                <h1 class="text-xl sm:text-2xl font-semibold">学習計画一覧</h1>
                <button id="menuButton"
                    class="fixed top-7 right-6 sm:top-10 sm:right-8 bg-[var(--accent-color)] text-[var(--white)] p-2 rounded-lg shadow-lg hover:bg-[var(--button-hover)] transition-transform transform hover:scale-110 lg:hidden z-50">
                    <img id="menuIcon" src="{{ asset('images/menu_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.svg') }}" class="w-6 h-6">
                </button>
            </div>
        </header>

        <div class="bg-[var(--bg-light-gray)] p-8 rounded-lg shadow-lg">
            <div class="flex flex-col sm:flex-row sm:items-center w-full sm:justify-between gap-2 sm:gap-0">
                <form action="{{ route('plan.index') }}" method="GET">
                    <div class="flex items-center rounded-lg border-2 border-gray-300 overflow-hidden">
                        <div class="relative w-full md:w-64 lg:w-80">
                            <input type="text" name="search" placeholder="計画名で検索" value="{{ request('search') }}"
                                class="px-2 md:px-4 h-8 md:h-9 text-sm sm:text-base focus:outline-none w-full">
                            @if(request('search'))
                                <button type="button" class="absolute right-2 top-1/2 transform -translate-y-1/2 text-howaitp"
                                    onclick="document.querySelector('input[name=\'search\']').value=''; this.form.submit();">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            @endif
                        </div>
                        <div class="h-8 md:h-9 w-[2px] bg-gray-300"></div>
                        <select name="sort" onchange="this.form.submit()" class="w-20 sm:w-24 md:w-36 px-1 md:px-2 h-8 md:h-9 text-xs sm:text-sm focus:outline-none">
                            <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>新しい順</option>
                            <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>古い順</option>
                            <option value="priority_high" {{ request('sort') === 'priority_high' ? 'selected' : '' }}>優先度が高い順</option>
                            <option value="priority_low" {{ request('sort') === 'priority_low' ? 'selected' : '' }}>優先度が低い順</option>
                        </select>
                        <button
                            type="submit"
                            class="flex justify-center items-center min-w-[60px] bg-[var(--button-bg)] px-1 sm:px-2 h-8 md:h-9 hover:bg-[var(--button-hover)] transition-shadow shadow"
                        >
                            <img src="{{ asset('images/search_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.svg') }}" alt="" class="h-5 w-5">
                        </button>
                    </div>
                </form>

                <a href="{{ route('plan.create') }}"
                    class="flex items-center justify-center bg-[var(--button-bg)] text-[var(--white)] px-3 md:px-4 h-8 md:h-9 text-sm sm:text-base rounded-md hover:bg-[var(--button-hover)] transition-shadow whitespace-nowrap">
                    新規作成
                </a>
            </div>
            <div class="grid grid-cols-1 mt-6 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                @forelse ($plans as $plan)
                @php
                    $priorityColor = match ($plan['priority']) {
                        'high' => 'bg-red-200 border-red-500',
                        'medium' => 'bg-yellow-200 border-yellow-500',
                        default => 'bg-green-200 border-green-500',
                    };
                @endphp
                <div class="relative p-4 sm:p-5 rounded-xl shadow-lg {{ $priorityColor }} flex flex-col justify-between">

                    <div class="flex flex-wrap justify-between items-start sm:items-center space-y-2 sm:space-y-0">
                        <div class="w-full sm:w-auto min-w-0">
                            <h3 class="text-lg sm:text-xl text-gray-600 font-bold break-words truncate">{{ $plan['name'] }}</h3>
                        </div>
                        <p class="text-sm sm:text-lg text-gray-500 sm:mt-0 mt-2 sm:text-right w-full sm:w-auto">
                            目標時間: {{ $plan['target_hours'] }}時間
                        </p>
                    </div>

                    <p class="text-sm sm:text-base text-gray-600 mb-6 sm:mt-2 max-h-28 overflow-y-auto">
                        詳細: {{ $plan['description'] ?? '詳細はありません' }}
                    </p>
                    <div class="flex flex-col items-start p-4 rounded-lg mt-4">

                        <div class="w-full mb-4">
                            <div class="flex items-center space-x-2 sm:space-x-3">
                                <p class="text-xs sm:text-sm text-gray-700 font-semibold">進捗:
                                    <span class="text-xs sm:text-sm font-medium text-gray-800">{{ $plan['progress'] }}%</span>
                                </p>
                                <span class="inline-block px-3 py-1 text-sm font-medium rounded-full {{ $plan['completed'] == 1 ? 'bg-green-500 text-[var(--white)]' : 'bg-red-500 text-[var(--white)]' }} whitespace-nowrap">
                                    {{ $plan['completed'] ==  1 ? '完了' : '未完了' }}
                                </span>
                            </div>

                            <div class="w-full bg-gray-300 rounded-full h-2 mt-2">
                                <div class="bg-green-500 h-2 rounded-full transition-all duration-300 ease-in-out" style="width: {{ $plan['progress'] }}%;"></div>
                            </div>
                        </div>

                        <div class="w-full flex justify-end space-x-2 mt-2">

                            <a href="{{ route('plan.edit', ['id' => $plan['id']]) }}"
                               class="bg-[var(--button-bg)] text-[var(--white)] px-4 py-2 rounded-md font-semibold hover:bg-[var(--button-hover)] transition-all duration-300 transform hover:scale-105 text-sm sm:text-base w-auto flex justify-center items-center space-x-2">
                                <img src="{{ asset('images/edit_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.svg') }}" class="w-5 h-5">
                                <span>編集</span>
                            </a>

                            <form action="{{ route('plan.destroy', ['id' => $plan->id]) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');" class="w-auto">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 text-[var(--white)] px-4 py-2 rounded-md font-semibold hover:bg-red-600 transition-all duration-300 transform hover:scale-105 text-sm sm:text-base w-auto flex justify-center items-center space-x-2">
                                    <img src="{{ asset('images/delete_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.svg') }}" class="w-5 h-5">
                                    <span>削除</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                    <p class="text-lg text-center text-gray-600">学習計画はまだありません。</p>
                @endforelse
            </div>
            <x-pagination.custom :paginator="$plans" />
        </div>
    </div>
    <script>
        document.getElementById("menuButton").addEventListener("click", () => {
            const sidebar = document.getElementById("sidebar");
            sidebar.classList.toggle("-translate-x-full");
        });
    </script>
</body>
</html>
