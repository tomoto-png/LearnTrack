<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>質問ひろば</title>
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
    <div class="fixed inset-y-0 left-0 z-50 hidden lg:block">
        @include('components.sidebar')
    </div>
    <div id="mainContent" class="flex-1 p-4 mt-4 sm:p-6 sm:mt-6 lg:ml-72">
        <header class="flex sm:flex-row justify-between items-center space-y-4 sm:space-y-0 mb-8">
            <div class="flex items-center justify-between w-full sm:w-auto">
                <h1 class="text-xl sm:text-2xl font-semibold">質問ひろば</h1>
                <button id="menuButton"
                    class="fixed top-7 right-6 sm:top-10 sm:right-8 bg-[var(--accent-color)] text-[var(--white)] p-2 rounded-lg shadow-lg hover:bg-[var(--button-hover)] transition-transform transform hover:scale-110 lg:hidden z-50">
                    <img id="menuIcon" src="{{ asset('images/menu_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.svg') }}" class="w-6 h-6">
                </button>
            </div>
        </header>
        <div class="bg-[var(--bg-light-gray)] p-8 rounded-lg">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <form action="{{ route('question.index') }}" method="GET" class="flex-grow flex gap-4">
                        <div class="flex rounded-md overflow-hidden">
                            <input type="text" name="keyword" placeholder="キーワードを入力" value="{{ request('keyword') }}"
                                class="border px-2 md:px-4 h-8 md:h-9 w-40 sm:w-52 md:w-64 lg:w-80"  />
                            <button type="submit" class="bg-[var(--button-bg)] text-[var(--white)] px-3 h-8 md:h-9 hover:bg-[var(--button-hover)]">
                                <img src="{{ asset('images/search_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.svg') }}" alt="" class="h-7 w-7">
                            </button>
                        </div>
                        <select name="sort" onchange="this.form.submit()" class="rounded-md px-3 py-2">
                            <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>質問日時の新しい順</option>
                            <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>質問日時の古い順</option>
                            <option value="open" {{ request('sort') === 'open' ? 'selected' : '' }}>回答受付中</option>
                            <option value="solved" {{ request('sort') === 'solved' ? 'selected' : '' }}>解決済み</option>
                            <option value="fewest_answers" {{ request('sort') === 'fewest_answers' ? 'selected' : '' }}>回答数の少ない順</option>
                            <option value="most_answers" {{ request('sort') === 'most_answers' ? 'selected' : '' }}>回答数の多い順</option>
                            <option value="least_reward" {{ request('sort') === 'least_reward' ? 'selected' : '' }}>お礼の少ない順</option>
                            <option value="most_reward" {{ request('sort') === 'most_reward' ? 'selected' : '' }}>お礼の多い順</option>
                        </select>
                    </form>
                    <a href="{{ route('search.category') }}" class="text-lg hover:border-b-2 border-[var(--text-brown)] pb-1">カテゴリー</a>
                </div>
                <a href="{{ route('question.create') }}"
                    class="flex items-center justify-center bg-[var(--button-bg)] text-[var(--white)] px-3 md:px-4 h-8 md:h-9 text-sm sm:text-base rounded-md hover:bg-[var(--button-hover)] transition-shadow shadow">
                    質問する
                </a>
            </div>
            <div class="mt-6">
                <!-- 質問一覧 -->
                @forelse ($questionDatas as $data)
                    <a href="{{ route('question.show', $data->id) }}"
                       class="block bg-white rounded-lg shadow-sm p-4 mb-4 hover:shadow-md transition">
                        <p class="text-xs text-gray-500">{{ $data->user->name }} さん</p>
                        <p class="mt-1 text-sm sm:text-base text-[var(--text-main)]">{{ Str::limit($data->content, 155, '...') }}</p>
                        <div class="flex items-center text-xs sm:text-sm text-gray-500 gap-3 mt-2">
                            <span class="px-2 py-0.5 bg-gray-100 rounded">{{ $data->category->name }}</span>
                            <span>{{ $data->updated_at->format('Y/m/d H:i') }}</span>
                        </div>
                    </a>
                @empty
                    <p class="text-center text-gray-500 mt-8">該当する質問は見つかりませんでした。</p>
                @endforelse
            </div>
            <x-pagination.custom :paginator="$questionDatas" />
        </div>
    </div>
</body>
</html>
