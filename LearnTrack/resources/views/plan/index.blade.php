<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>学習計画一覧</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        :root {
            --bg-green: #b3cfad;
            --bg-light-gray: #e3e6d8;
            --text-brown: #9f9579;
            --accent-yellow: #d9ca79;
            --button-hover: #c5a02d;
        }
    </style>
</head>
<body class="bg-[var(--bg-green)] text-[var(--text-brown)] flex">

    <div id="sidebar" class="fixed inset-y-0 left-0 w-72 shadow-md bg-white z-20 hidden md:block">
        @include('components.sidebar')
    </div>

    <div id="mainContent" class="flex-1 p-4 sm:p-6 mt-4 md:ml-72 transition-all">
        <header class="flex sm:flex-row justify-between items-center space-y-4 sm:space-y-0 mb-8">
            <div class="flex items-center justify-between w-full sm:w-auto">
                <h1 class="text-xl sm:text-2xl font-semibold">学習計画一覧</h1>
                <button id="menuButton"
                    class="fixed top-6 right-6 bg-[var(--accent-yellow)] text-white p-3 rounded-lg shadow-lg hover:bg-yellow-500 transition-transform transform hover:scale-110 md:hidden z-[9999]">
                    <img id="menuIcon" src="{{ asset('images/menu_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.svg') }}" class="w-6 h-6">
                </button>
            </div>
        </header>
        <div class="bg-[var(--bg-light-gray)] p-6 sm:p-6 rounded-lg shadow-lg">
            <div class="flex sm:flex-row items-center w-full justify-between">
                <form action="{{ route('plan.index') }}" method="GET" class="flex items-center space-x-2 sm:w-auto">
                    <div class="relative sm:w-64 md:w-80 lg:w-96">
                        <input type="text" name="search" placeholder="計画名で検索" value="{{ request('search') }}"
                            class="px-10 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[var(--accent-yellow)] w-full writing-mode-horizontal-tb text-sm sm:text-base md:text-lg">
                        <img src="{{ asset('images/search_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.svg') }}"
                            class="w-5 h-5 absolute left-3 top-1/2 transform -translate-y-1/2 pointer-events-none text-gray-600">
                        @if(request('search'))
                        <button type="button" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-600"
                            onclick="document.querySelector('input[name=\'search\']').value=''; this.form.submit();">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                        @endif
                    </div>
                    <button type="submit" class="bg-[var(--accent-yellow)] text-white text-sm sm:text-base px-4 py-2 rounded-md hover:bg-[var(--button-hover)] transition-shadow shadow text-sm sm:text-base md:text-lg">
                        検索
                    </button>
                </form>

                <a href="{{ route('plan.create') }}"
                    class="bg-[var(--accent-yellow)] text-white text-sm sm:text-base px-4 py-2 rounded-lg font-semibold hover:bg-[var(--button-hover)] transition-shadow shadow sm:w-auto text-center w-32">
                    新規作成
                </a>
            </div>
            <div class="grid grid-cols-1 mt-6 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                @forelse ($plans as $plan)
                <div class="relative p-4 sm:p-5 rounded-xl shadow-lg
                    @if($plan['priority'] === 'high') bg-red-200 border-red-500
                    @elseif($plan['priority'] === 'medium') bg-yellow-200 border-yellow-500
                    @else bg-green-200 border-green-500 @endif
                    flex flex-col justify-between">

                    <div class="flex flex-wrap justify-between items-start sm:items-center space-y-2 sm:space-y-0">
                        <div class="w-full sm:w-auto min-w-0">
                            <h3 class="text-lg sm:text-xl font-bold text-gray-800 break-words truncate">{{ $plan['name'] }}</h3>
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
                                <span class="inline-block px-3 py-1 text-sm font-medium rounded-full {{ $plan['completed'] == 1 ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }} whitespace-nowrap">
                                    {{ $plan['completed'] ==  1 ? '完了' : '未完了' }}
                                </span>
                            </div>

                            <div class="w-full bg-gray-300 rounded-full h-2 mt-2">
                                <div class="bg-green-500 h-2 rounded-full transition-all duration-300 ease-in-out" style="width: {{ $plan['progress'] }}%;"></div>
                            </div>
                        </div>

                        <div class="w-full flex justify-end space-x-2 mt-2">

                            <a href="{{ route('plan.edit', ['id' => $plan['id']]) }}"
                               class="bg-[var(--accent-yellow)] text-white px-4 py-2 rounded-md font-semibold hover:bg-[var(--button-hover)] transition-all duration-300 transform hover:scale-105 text-sm sm:text-base w-auto flex justify-center items-center space-x-2">
                                <img src="{{ asset('images/edit_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.svg') }}" class="w-5 h-5">
                                <span>編集</span>
                            </a>

                            <form action="{{ route('plan.destroy', ['id' => $plan->id]) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');" class="w-auto">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-md font-semibold hover:bg-red-600 transition-all duration-300 transform hover:scale-105 text-sm sm:text-base w-auto flex justify-center items-center space-x-2">
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

            <div class="mt-6 flex justify-center">
                <nav class="inline-flex items-center space-x-2">
                    @if ($plans->onFirstPage())
                        <span class="px-3 py-2 text-base font-medium text-gray-400 bg-gray-200 border border-gray-300 rounded-full cursor-not-allowed">
                            ←
                        </span>
                    @else
                        <a href="{{ $plans->previousPageUrl() }}" class="px-3 py-2 text-base font-medium text-white bg-[var(--accent-yellow)] border border-[var(--accent-yellow)] rounded-full hover:bg-[var(--button-hover)] transition-transform transform hover:scale-105">
                            ←
                        </a>
                    @endif

                    @php
                        $currentPage = $plans->currentPage();
                        $lastPage = $plans->lastPage();
                        $start = max($currentPage - 2, 1);
                        $end = min($currentPage + 2, $lastPage);
                    @endphp

                    @if ($start > 1)
                        <a href="{{ $plans->url(1) }}" class="px-3 py-2 text-base font-medium text-[var(--text-brown)] bg-gray-200 border border-gray-300 rounded-full hover:bg-[var(--button-hover)] hover:text-white transition-transform transform hover:scale-105">
                            1
                        </a>
                        @if ($start > 2)
                            <span class="px-2 text-base text-gray-500">...</span>
                        @endif
                    @endif

                    @for ($i = $start; $i <= $end; $i++)
                        @if ($i == $currentPage)
                            <span class="px-3 py-2 text-base font-semibold text-white bg-[var(--bg-green)] border border-gray-300 rounded-full shadow-inner">
                                {{ $i }}
                            </span>
                        @else
                            <a href="{{ $plans->url($i) }}" class="px-3 py-2 text-base font-medium text-[var(--text-brown)] bg-gray-200 border border-gray-300 rounded-full hover:bg-[var(--button-hover)] hover:text-white transition-transform transform hover:scale-105">
                                {{ $i }}
                            </a>
                        @endif
                    @endfor

                    @if ($end < $lastPage)
                        @if ($end < $lastPage - 1)
                            <span class="px-2 text-base text-gray-500">...</span>
                        @endif
                        <a href="{{ $plans->url($lastPage) }}" class="px-3 py-2 text-base font-medium text-[var(--text-brown)] bg-gray-200 border border-gray-300 rounded-full hover:bg-[var(--button-hover)] hover:text-white transition-transform transform hover:scale-105">
                            {{ $lastPage }}
                        </a>
                    @endif

                    @if ($plans->hasMorePages())
                        <a href="{{ $plans->nextPageUrl() }}" class="px-3 py-2 text-base font-medium text-white bg-[var(--accent-yellow)] border border-[var(--accent-yellow)] rounded-full hover:bg-[var(--button-hover)] transition-transform transform hover:scale-105">
                            →
                        </a>
                    @else
                        <span class="px-3 py-2 text-base font-medium text-gray-400 bg-gray-200 border border-gray-300 rounded-full cursor-not-allowed">
                            →
                        </span>
                    @endif
                </nav>
            </div>
        </div>
    </div>

    <script>
        document.getElementById("menuButton").addEventListener("click", function() {
            const sidebar = document.getElementById("sidebar");
            sidebar.classList.toggle("hidden");
        });
    </script>

</body>
</html>
