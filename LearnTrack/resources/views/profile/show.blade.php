<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>マイページ</title>
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
<body class="bg-[var(--bg-green)] text-[var(--text-brown)] min-h-screen flex items-center justify-center">
    <div class="px-6 max-w-2xl lg:max-w-5xl  w-full">
        <div class="p-6 lg:p-8 bg-[var(--bg-light-gray)] rounded-xl shadow-md">
            <div class="flex items-center gap-2 mb-3">
                <a href="{{ url()->previous()}}">
                    <span class="font-bold py-8 text-2xl">&larr;</span>
                </a>
                <p class="text-lg font-medium select-none">{{ $user->name }}さんのマイページ</p>
            </div>
            <div class="relative p-2 z-10">
                <div class="flex flex-col sm:flex-row sm:space-x-4 items-center">
                    @if ($user->avatar)
                        <div class="w-24 h-24 rounded-full border-4 border-[var(--accent-color)] shadow overflow-hidden">
                            <img class="w-full h-full object-cover"
                                src="{{ $user->avatar }}"
                                alt="{{ $user->name }}のアバター">
                        </div>
                    @else
                        <div class="w-24 h-24 rounded-full bg-[var(--bg-green)] flex items-center justify-center border-4 border-[var(--bg-light-gray)] text-2xl text-[var(--white)]">
                            <img src="{{ asset('images/person_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.svg') }}" alt="画像アップロード" class="w-10 h-10 opacity-70">
                        </div>
                    @endif
                    <div class="mt-2">
                        <h2 class="text-xl sm:text-2xl font-semibold">{{ $user->name }}さん</h2>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 p-2 md:grid-cols-3 md:gap-8 md:p-4">
                    <!-- プロフィール -->
                    <div class="flex-1">
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
                        <p class="mb-2 flex justify-between">
                            <span class="font-semibold whitespace-nowrap">職業：</span>
                            <span class="break-words text-left max-h-6 overflow-y-auto">
                                {{ $user->occupation ?? '非公開' }}
                            </span>
                        </p>
                    </div>

                    <div class="flex-1">
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
                    <div class="flex-1">
                        <div class="flex items-center mb-4">
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
        </div>
    </div>
</body>
</html>
