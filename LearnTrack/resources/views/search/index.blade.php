<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>検索</title>
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
<body class="bg-[var(--bg-green)] text-[var(--text-brown)]">
    <aside id="sidebar"
        class="fixed top-0 left-0 w-72 h-screen bg-[var(--bg-light-gray)] shadow-lg p-6 z-50
            transform -translate-x-full transition-transform duration-300 ease-in-out
            lg:translate-x-0">
        @include('components.sidebar')
    </aside>
    <div id="mainContent" class="flex-1 p-4 mt-4 sm:p-6 sm:mt-6 lg:ml-72">
        <header class="flex sm:flex-row justify-between items-center space-y-4 sm:space-y-0 mb-8">
            <div class="flex items-center justify-between w-full sm:w-auto">
                <h1 class="text-xl sm:text-2xl font-semibold">カテゴリー</h1>
                <button id="menuButton"
                    class="fixed top-7 right-6 sm:top-10 sm:right-8 bg-[var(--accent-color)] text-[var(--white)] p-2 rounded-lg shadow-lg hover:bg-[var(--button-hover)] transition-transform transform hover:scale-110 lg:hidden z-50">
                    <img id="menuIcon" src="{{ asset('images/menu_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.svg') }}" class="w-6 h-6">
                </button>
            </div>
        </header>
        <div class="bg-[var(--bg-light-gray)] p-8 rounded-lg">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 w-full">
                <div class="flex flex-col md:flex-row items-center gap-3 w-full md:w-auto">
                    <form action="{{ route('search.index') }}" method="GET" class="flex flex-col sm:flex-row gap-2 w-full md:w-auto">
                        <div class="flex border rounded-md overflow-hidden ">
                            <input type="text" name="keyword" placeholder="キーワードを入力" value="{{ request('keyword') }}"
                                class="px-2 md:px-4 h-9 w-full sm:w-64 lg:w-80 focus:outline-none" />
                            <button type="submit"
                                class="bg-[var(--button-bg)] text-[var(--white)] px-3 h-9 hover:bg-[var(--button-hover)]">
                                <img src="{{ asset('images/search_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.svg') }}" alt=""
                                    class="h-6 w-6">
                            </button>
                        </div>
                        <div class="flex items-center justify-between gap-4">
                            <select name="sort" onchange="this.form.submit()"
                                class="h-9 border rounded-md px-2 text-sm focus:outline-none w-auto">
                                <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>質問日時の新しい順</option>
                                <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>質問日時の古い順</option>
                                <option value="open" {{ request('sort') === 'open' ? 'selected' : '' }}>回答受付中</option>
                                <option value="solved" {{ request('sort') === 'solved' ? 'selected' : '' }}>解決済み</option>
                                <option value="fewest_answers" {{ request('sort') === 'fewest_answers' ? 'selected' : '' }}>回答数の少ない順</option>
                                <option value="most_answers" {{ request('sort') === 'most_answers' ? 'selected' : '' }}>回答数の多い順</option>
                                <option value="least_reward" {{ request('sort') === 'least_reward' ? 'selected' : '' }}>お礼の少ない順</option>
                                <option value="most_reward" {{ request('sort') === 'most_reward' ? 'selected' : '' }}>お礼の多い順</option>
                            </select>
                            <a href="{{ route('search.category') }}"
                                class="text-base md:text-lg hover:border-b-2 border-[var(--text-brown)] pb-1 whitespace-nowrap">
                                カテゴリー
                            </a>
                        </div>
                        @if(request('group'))
                            <input type="hidden" name="group" value="{{ request('group') }}">
                        @endif
                        @if(request('category'))
                            <input type="hidden" name="category" value="{{ request('category') }}">
                        @endif
                    </form>
                </div>

                {{-- 質問するボタン --}}
                <div class="w-full md:w-auto">
                    <a href="{{ route('question.create') }}"
                        class="flex items-center justify-center bg-[var(--button-bg)] text-[var(--white)] px-4 h-9 text-sm sm:text-base rounded-md hover:bg-[var(--button-hover)] transition-shadow shadow w-full md:w-auto whitespace-nowrap">
                        質問する
                    </a>
                </div>
            </div>
            <div class="mt-6">
                @if ($questions->isNotEmpty())
                    <div class="mb-3">
                        @if ($category)
                            <div class="flex">
                                <a href="{{ route('search.index', ['group' => $group->id]) }}" class="text-lg font-medium hover:underline">
                                    {{ $group->name }}
                                </a>
                                <span class="mx-1">/</span>
                                <p class="text-lg font-medium">{{ $category->name }}</p>
                            </div>
                        @else
                            <p class="text-lg font-medium">{{ $group->name }}</p>
                        @endif
                        </div>
                    @foreach ($questions as $question)
                        <a href="{{ route('question.show', $question->id) }}"
                            class="block bg-white rounded-lg shadow-sm p-4 mb-4 hover:shadow-md transition">
                            <p class="text-xs text-gray-500">{{ $question->user->name }} さん</p>
                            <p class="mt-1 text-sm sm:text-base text-[var(--text-main)]">{{ Str::limit($question->content, 155, '...') }}</p>
                            <div class="flex items-center text-xs sm:text-sm text-gray-500 gap-3 mt-2">
                                <span class="px-2 py-0.5 bg-gray-100 rounded">{{ $question->category->name }}</span>
                                <span>{{ $question->updated_at->format('Y/m/d H:i') }}</span>
                            </div>
                        </a>
                    @endforeach
                @else
                    <div class="text-center">
                        <h1>該当する質問は見つかりませんでした。</h1>
                    </div>
                @endif
                <x-pagination.custom :paginator="$questions" />
            </div>
        </div>
    </div>
    <script>
        document.getElementById("menuButton").addEventListener("click", function() {
            const sidebar = document.getElementById("sidebar");
            sidebar.classList.toggle("-translate-x-full");
        });
    </script>
</body>
</html>
