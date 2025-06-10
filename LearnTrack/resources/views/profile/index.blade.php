<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>マイページ</title>
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
<body class="bg-[var(--bg-green)] text-[var(--text-brown)] min-h-screen flex">

    <div id="sidebar" class="fixed inset-y-0 left-0 z-50 hidden lg:block">
        @include('components.sidebar')
    </div>

    <div id="mainContent" class="flex-1 p-4 mt-4 sm:p-6 sm:mt-6 lg:ml-72">
        <header class="flex sm:flex-row justify-between items-center space-y-4 sm:space-y-0 mb-8">
            <!-- タイトルとハンバーガーメニュー -->
            <div class="flex items-center justify-between w-full sm:w-auto">
                <h1 class="text-xl sm:text-2xl font-semibold">マイページ</h1>
                <button id="menuButton"
                    class="fixed top-7 right-6 sm:top-10 sm:right-8 bg-[var(--accent-color)] text-[var(--white)] p-2 rounded-lg shadow-lg hover:bg-[var(--button-hover)] transition-transform transform hover:scale-110 lg:hidden z-50">
                    <img id="menuIcon" src="{{ asset('images/menu_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.svg') }}" class="w-6 h-6">
                </button>
            </div>
        </header>

        <div class="relative bg-[var(--bg-light-gray)] rounded-lg overflow-hidden shadow-lg">
            <div class="absolute h-20 inset-0"
                    style="background: linear-gradient(to bottom, #f0f8ff 0%, #d0e8f0 100%);
                        z-index: 0;">
            </div>
            <div class="relative p-6 z-10">
                <div class="flex flex-col sm:flex-row sm:space-x-4">
                    @if ($user->avatar)
                        <div class="w-24 h-24 rounded-full border-4 border-[var(--accent-color)] shadow overflow-hidden sm:w-32 sm:h-32">
                            <img class="w-full h-full object-cover"
                                src="{{ $user->avatar }}"
                                alt="{{ $user->name }}のアバター">
                        </div>
                    @else
                        <div class="w-24 h-24 rounded-full bg-[var(--bg-green)] flex items-center justify-center border-4 border-[var(--bg-light-gray)] text-2xl text-[var(--white)] sm:w-32 sm:h-32">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif

                    <div class="sm:mt-16">
                        <h2 class="text-2xl sm:text-3xl font-semibold">{{ $user->name }}さん</h2>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row sm:space-x-12 mt-6">
                    <!-- プロフィール -->
                    <div class="flex-1 p-6">
                      <div class="flex items-center mb-4">
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
                      <p class="mb-0 flex justify-between">
                        <span class="font-semibold">職業：</span>
                        <span>{{ $user->occupation ?? '非公開' }}</span>
                      </p>
                    </div>

                    <div class="flex-1 p-6 mt-2 sm:mt-0">
                        <div class="flex items-center mb-4">
                            <h3 class="text-lg font-semibold">活動状況</h3>
                            <div class="flex-grow border-t border-[var(--text-brown)] ml-4"></div>
                        </div>
                        <p class="mb-2 flex justify-between">
                            <span class="font-semibold">お持ちのトマト：</span>
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
                    <div class="flex-1 p-6 mt-2 sm:mt-0">
                        <div class="flex items-center mb-4">
                            <h3 class="text-lg font-semibold">自己紹介</h3>
                            <div class="flex-grow border-t border-[var(--text-brown)] ml-4"></div>
                        </div>
                        <div>
                            <p class="text-sm sm:text-base text-[var(--text-main)]">
                                {{ $user->bio ?? '自己紹介はまだ設定されていません。' }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="mt-6 text-right">
                    <a href="{{ route('profile.edit') }}"
                    class="inline-block bg-[var(--button-bg)] text-[var(--white)] px-4 py-2 rounded-lg font-semibold hover:bg-[var(--button-hover)] transition">
                        マイページ編集
                    </a>
                </div>
            </div>
        </div>

        <div class="mt-6 bg-[var(--bg-light-gray)] rounded-lg overflow-hidden shadow-lg p-8">
            <div class="flex gap-4 text-lg">
                <a href="{{ route('profile.index', ['filter' => 'question']) }}"
                   class="pb-1 border-b-2 transition duration-200 {{ $filter == 'question' ? 'border-[var(--accent-color)] text-[var(--accent-color)] font-semibold' : 'border-transparent' }}">
                    質問
                </a>
                <a href="{{ route('profile.index', ['filter' => 'answer']) }}"
                   class="pb-1 border-b-2 transition duration-200 {{ $filter == 'answer' ? 'border-[var(--accent-color)] text-[var(--accent-color)] font-semibold' : 'border-transparent' }}">
                    回答
                </a>
            </div>
            <div class="mt-4">
                @if ($filter === 'question')
                    <div class="space-x-2">
                        <a href="{{ route('profile.index', ['filter' => $filter, 'status' => '']) }}" class="py-2 px-4 rounded-full border border-[var(--accent-color)] {{ ($filter == $filter && $status == '') ? 'text-[var(--white)] bg-[var(--button-bg)]' : '' }}">
                            全て
                        </a>
                        <a href="{{ route('profile.index', ['filter' => $filter, 'status' => 'open']) }}" class="py-2 px-4 rounded-full border border-[var(--accent-color)] {{ ($filter == $filter && $status == 'open') ? 'text-[var(--white)] bg-[var(--button-bg)]' : '' }}">
                            回答受付中
                        </a>
                        <a href="{{ route('profile.index', ['filter' => $filter, 'status' => 'closed']) }} " class="py-2 px-4 rounded-full border border-[var(--accent-color)] {{ ($filter == $filter && $status == 'closed') ? 'text-[var(--white)] bg-[var(--button-bg)]' : '' }}">
                            解決済み
                        </a>
                        <a href="{{ route('profile.index', ['filter' => $filter, 'status' => 'no_best']) }}" class="py-2 px-4 rounded-full border border-[var(--accent-color)] {{ ($filter == $filter && $status == 'no_best') ? 'text-[var(--white)] bg-[var(--button-bg)]' : '' }}">
                            ベストアンサー未選択
                        </a>
                    </div>
                @else
                    <div class="space-x-2">
                        <a href="{{ route('profile.index', ['filter' => $filter, 'status' => '']) }}" class="py-2 px-4 rounded-full border border-[var(--accent-color)] {{ ($filter == $filter && $status == '') ? 'text-[var(--white)] bg-[var(--button-bg)]' : '' }}">
                            全て
                        </a>
                        <a href="{{ route('profile.index', ['filter' => $filter, 'status' => 'open']) }}" class="py-2 px-4 rounded-full border border-[var(--accent-color)] {{ ($filter == $filter && $status == 'open') ? 'text-[var(--white)] bg-[var(--button-bg)]' : '' }}">
                            回答受付中
                        </a>
                        <a href="{{ route('profile.index', ['filter' => $filter, 'status' => 'closed']) }}" class="py-2 px-4 rounded-full border border-[var(--accent-color)] {{ ($filter == $filter && $status == 'closed') ? 'text-[var(--white)] bg-[var(--button-bg)]' : '' }}">
                            解決済み
                        </a>
                        <a href="{{ route('profile.index', ['filter' => $filter, 'status' => 'best']) }}" class="py-2 px-4 rounded-full border border-[var(--accent-color)] {{ ($filter == $filter && $status == 'best') ? 'text-[var(--white)] bg-[var(--button-bg)]' : '' }}">
                            ベストアンサー
                        </a>
                    </div>
                @endif
            </div>
            <div class="mt-8">
                @forelse($datas as $data)
                    @if ($filter === 'question')
                        <a href="{{ route('question.show', $data->id) }}"
                            class="block bg-white rounded-lg shadow-sm p-4 mb-4 hover:shadow-md transition">
                            <p class="mt-1 text-sm sm:text-base text-[var(--text-main)]">{{ Str::limit($data->content, 155, '...') }}</p>
                            <div class="flex items-center text-xs sm:text-sm text-gray-500 gap-3 mt-2">
                                <span class="px-2 py-0.5 bg-gray-100 rounded">{{ $data->category->name }}</span>
                                <span>{{ $data->updated_at->format('Y/m/d H:i') }}</span>
                            </div>
                        </a>
                    @else
                        <a href="{{ route('question.show', $data->question->id) }}"
                            class="block bg-white rounded-lg shadow-sm p-4 mb-4 hover:shadow-md transition">
                            <p class="text-sm text-gray-500">{{ $data->question->user->name }} さん</p>
                            <p class="mt-1 text-sm sm:text-base text-[var(--text-main)]">{{ Str::limit($data->question->content, 155, '...') }}</p>
                            <div class="pl-2">
                                <div class="pl-1 border-l-2 border-[var(--text-brown)]">
                                    <p class="mt-1 text-sm sm:text-base text-[var(--text-main)]">{{ Str::limit($data->content, 155, '...') }}</p>
                                    <span>{{ $data->updated_at->format('Y/m/d H:i') }}</span>
                                </div>
                            </div>
                        </a>
                    @endif
                @empty
                    <p>該当する質問はありません。</p>
                @endforelse
            </div>
            <x-pagination.custom :paginator="$datas" />
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
