<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>show</title>
    <style>
        :root {
            --bg-green: #a0b89c;
            --bg-light-gray: #d6d9c8;
            --text-brown: #6b5e3f;
            --text-main: #44483d;
            --button-bg: #6c8c5d;
            --button-hover: #57724a;
            --accent-color: #3f5c38;
            --white: white;
        }
    </style>
</head>
<body class="bg-[var(--bg-green)] text-[var(--text-brown)]">
    <div class="px-6 max-w-xl lg:max-w-4xl mt-12 w-full mx-auto sm:0">
        {{-- 質問 --}}
        <div class="py-5 px-8 bg-[var(--bg-light-gray)] rounded-md">
            <div class="flex items-center text-base font-semibold gap-3 mb-3 border-b border-[var(--text-brown)] pb-3">
                <a href="{{ route('question.index') }}">
                    <span class="font-bold py-8 text-2xl">&larr;</span>
                </a>
                <img src="{{ asset('images/alarm_24dp_E3E3E3_FILL0_wght400_GRAD0_opsz24.svg') }}" alt="">
                @if ($remainingDays > 0)
                    <p>回答受付終了まであと{{ $remainingDays }}日</p>
                @elseif ($remainingDays === 0)
                    <p>回答受付が終了しました</p>
                @endif
            </div>
            <div class="space-y-4">
                <a href="{{ route('profile.show', $questionData->user->id) }}" class="flex items-center space-x-2">
                    @if ($questionData->user->avatar)
                        <div class="w-11 h-11 rounded-full border border-[var(--accent-color)] shadow overflow-hidden">
                            <img class="w-full h-full object-cover"
                                src="{{ $questionData->user->avatar }}"
                                alt="{{ $questionData->user->name }}のアバター">
                        </div>
                    @else
                        <div class="w-11 h-11 rounded-full bg-[var(--bg-green)] flex items-center justify-center text-base text-[var(--white)] shadow">
                            <img src="{{ asset('images/person_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.svg') }}" alt="画像アップロード" class="w-5 h-5 opacity-70">
                        </div>
                    @endif
                    <div>
                        <p>{{ $questionData->user->name }}さん</p>
                        <p class="text-sm">{{ $questionData->created_at->format('Y/m/d H:i') }}</p>
                    </div>
                </a>

                {{-- nl2brは/nがある時に改行する、コード内にURLがあるとpタグからaタグに変換する --}}
                <div class="text-base text-[var(--text-main)] leading-relaxed">
                    {!! nl2br(preg_replace('/(https?:\/\/[^\s]+)/i', '<a href="$1" target="_blank" rel="noopener noreferrer" class="text-blue-500 hover:text-blue-700 hover:underline">$1</a>', e($questionData->content))) !!}
                </div>

                @if (!@empty($questionData->image_url))
                    <div class="flex justify-center my-6">
                        <img src="{{ $questionData->image_url }}" alt="" class="w-64 h-auto rounded shadow-md border" id="thumbnail">
                    </div>
                @endif

                <div class="flex items-center gap-2">
                    <p class="text-sm">
                        {{ $questionData->category->group->name }} / {{ $questionData->category->name }}
                    </p>
                    @if ($questionData->reward > 0)
                        <div class="flex items-center space-x-1">
                            <img src="{{ asset('images/icons8-トマト-48.png')}}" alt="" class="h-5 w-5">
                            <p class="text-base">{{ $questionData->reward }}</p>
                        </div>
                    @endif
                </div>
            </div>
            @can('create', [App\Models\Answer::class, $questionData])
                <div class="border-t border-[var(--texy-brown)] pt-3 mt-3">
                    <a href="{{ route('answer.create', ['id' => $questionData->id]) }}" class="flex justify-center items-center bg-[var(--button-bg)] font-medium text-[var(--white)] py-2 shadow rounded-md">回答する</a>
                </div>
            @endcan
        </div>

        <div class="rounded-md mt-6 bg-[var(--bg-light-gray)] py-5 px-8">
            {{-- ベストアンサー --}}
            @if (!@empty($bestAnswer))
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <h2 class="text-lg font-semibold">ベストアンサー</h2>
                            <img src="{{ asset('images/workspace_premium_24dp_E3E3E3_FILL0_wght400_GRAD0_opsz24.svg') }}" class="w-7 h-7" alt="Best Answer Icon">
                        </div>
                    </div>
                    <div class="space-y-4 border-b-2 border-[var(--text-brown)] pb-4">
                        <a href="{{ route('profile.show', $bestAnswer->user->id)}}" class="flex items-center space-x-2">
                            @if ($bestAnswer->user->avatar)
                                <div class="w-11 h-11 rounded-full border border-[var(--accent-color)] shadow overflow-hidden">
                                    <img class="w-full h-full object-cover"
                                        src="{{ $bestAnswer->user->avatar }}"
                                        alt="{{ $bestAnswer->user->name }}のアバター">
                                </div>
                            @else
                                <div class="w-11 h-11 rounded-full bg-[var(--bg-green)] flex items-center justify-center text-base text-[var(--white)] shadow">
                                    <img src="{{ asset('images/person_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.svg') }}" alt="画像アップロード" class="w-5 h-5 opacity-70">
                                </div>
                            @endif
                            <div>
                                <p class="text-base">{{ $bestAnswer->user->name }}さん</p>
                                <p class="text-sm">{{ $bestAnswer->created_at->format('Y/m/d H:i') }}</p>
                            </div>
                        </a>
                        <div class="text-base text-[var(--text-main)] leading-relaxed">
                            {!! nl2br(preg_replace('/(https?:\/\/[^\s]+)/i', '<a href="$1" target="_blank" rel="noopener noreferrer" class="text-blue-500 hover:text-blue-700 hover:underline">$1</a>', e($bestAnswer->content))) !!}
                        </div>
                        @php
                            $replies = $bestAnswer->answerReply;
                            $firstReply = $replies->first();//最初の一件だけ
                            $moreReplies = $replies->slice(1);//最初の一件以外の返信を
                            $moreReplyCount = $moreReplies->count();
                        @endphp
                        <div class="pl-4">
                            @if ($firstReply)
                                <a href="{{ route('profile.show',$firstReply->user->id)}}" class="flex items-center space-x-2">
                                    @if ($firstReply->user->avatar)
                                        <div class="w-8 h-8 rounded-full border border-[var(--accent-color)] shadow overflow-hidden">
                                            <img class="w-full h-full object-cover"
                                                src="{{ $firstReply->user->avatar }}"
                                                alt="{{ $firstReply->user->name }}のアバター">
                                        </div>
                                    @else
                                        <div class="w-8 h-8 rounded-full bg-[var(--bg-green)] flex items-center justify-center text-sm text-[var(--white)] shadow">
                                            <img src="{{ asset('images/person_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.svg') }}" alt="画像アップロード" class="w-4 h-4 opacity-70">
                                        </div>
                                    @endif
                                    <div>
                                        <p>
                                            {{ $firstReply->user->name }}さん
                                            @if ($firstReply->user->id === $questionOwnerId)
                                                <span class="text-xs bg-[var(--accent-color)] text-[var(--white)] px-2 py-0.5 rounded">質問者</span>
                                            @endif
                                        </p>
                                    </div>
                                </a>
                                <div class="p-3">
                                    <div class="border-l-4 border-gray-400 pl-4 space-y-2">
                                        <p class="text-sm">{{ $firstReply->created_at->format('Y/m/d H:i') }}</p>
                                        <div class="text-base text-[var(--text-main)] leading-relaxed">
                                            {!! nl2br(preg_replace('/(https?:\/\/[^\s]+)/i', '<a href="$1" target="_blank" rel="noopener noreferrer" class="text-blue-500 hover:text-blue-700 hover:underline">$1</a>', e($firstReply->content))) !!}
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div id="bestAnswer-{{ $bestAnswer->id }}" class="hidden">
                                @foreach ($moreReplies as $reply)
                                    <a href="{{ route('profile.show', $reply->user->id)}}" class="flex items-center space-x-2">
                                        @if ($reply->user->avatar)
                                            <div class="w-8 h-8 rounded-full border border-[var(--accent-color)] shadow overflow-hidden">
                                                <img class="w-full h-full object-cover"
                                                    src="{{ $reply->user->avatar }}"
                                                    alt="{{ $reply->user->name }}のアバター">
                                            </div>
                                        @else
                                            <div class="w-8 h-8 rounded-full bg-[var(--bg-green)] flex items-center justify-center text-sm text-[var(--white)] shadow">
                                                <img src="{{ asset('images/person_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.svg') }}" alt="画像アップロード" class="w-4 h-4 opacity-70">
                                            </div>
                                        @endif
                                        <div>
                                            <p>
                                                {{ $reply->user->name }}さん
                                                @if ($reply->user->id === $questionOwnerId)
                                                    <span class="text-xs bg-[var(--accent-color)] text-[var(--white)] px-2 py-0.5 rounded">質問者</span>
                                                @endif
                                            </p>
                                        </div>
                                    </a>
                                    <div class="p-3">
                                        <div class="border-l-4 border-gray-400 pl-4 space-y-2">
                                            <p class="text-sm">{{ $reply->created_at->format('Y/m/d H:i') }}</p>
                                            <div class="text-base text-[var(--text-main)] leading-relaxed">
                                                {!! nl2br(preg_replace('/(https?:\/\/[^\s]+)/i', '<a href="$1" target="_blank" rel="noopener noreferrer" class="text-blue-500 hover:text-blue-700 hover:underline">$1</a>', e($reply->content))) !!}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            {{-- 展開ボタン --}}
                            @if($moreReplies->count() > 0)
                                <button class="toggle-replies-btn flex justify-center items-center w-full bg-[var(--button-bg)] font-medium text-[var(--white)] py-2 shadow rounded-md" data-target="bestAnswer-{{ $bestAnswer->id }}" data-label="さらに返信を表示（{{ $moreReplyCount }}件)">さらに返信を表示（{{ $moreReplyCount }}件）</button>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            {{-- 回答一覧 --}}
            <div class="bg-[var(--bg-light-gray)]">
                @if ($questionData->answers->isNotEmpty())
                    @if (!@empty($bestAnswer))
                        <h2 class="text-lg font-semibold my-4">その他の回答（{{ $questionData->answers->count() }}件）</h2>
                    @else
                        <h2 class="text-lg font-semibold my-4">回答一覧（{{ $questionData->answers->count() }}件）</h2>
                    @endif
                    <div class="space-y-4">
                        @foreach ($questionData->answers as $answer)
                            <div class="space-y-3 border-b border-[var(--text-brown)] pb-4">
                                <a href="{{ route('profile.show', $answer->user->id)}}" class="flex items-center space-x-2">
                                    @if ($answer->user->avatar)
                                        <div class="w-11 h-11 rounded-full border border-[var(--accent-color)] shadow overflow-hidden">
                                            <img class="w-full h-full object-cover"
                                                src="{{ $answer->user->avatar }}"
                                                alt="{{ $answer->user->name }}のアバター">
                                        </div>
                                    @else
                                        <div class="w-11 h-11 rounded-full bg-[var(--bg-green)] flex items-center justify-center text-base text-[var(--white)] shadow">
                                            <img src="{{ asset('images/person_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.svg') }}" alt="画像アップロード" class="w-5 h-5 opacity-70">
                                        </div>
                                    @endif
                                    <div>
                                        <p>{{ $answer->user->name }}さん</p>
                                        <p class="text-sm">{{ $answer->created_at->format('Y/m/d H:i') }}</p>
                                    </div>
                                </a>
                                <div class="text-base text-[var(--text-main)] leading-relaxed">
                                    {!! nl2br(preg_replace('/(https?:\/\/[^\s]+)/i', '<a href="$1" target="_blank" rel="noopener noreferrer" class="text-blue-500 hover:text-blue-700 hover:underline">$1</a>', e($answer->content))) !!}
                                </div>
                                @if (!@empty($answer->image_url))
                                    <div class="flex justify-center my-6">
                                        <img src="{{ $answer->image_url }}" alt="" class="w-64 h-auto rounded shadow-md border">
                                    </div>
                                @endif
                                <div class="flex gap-2">
                                    @can('create', [App\Models\AnswerReply::class, $answer])
                                        <div class="bg-[var(--button-bg)] flex items-center py-1 px-2 text-sm text-[var(--white)] rounded-md hover:bg-[var(--button-hover)]">
                                            <a href="{{ route('replie.create', $answer->id) }}">返信する</a>
                                        </div>
                                    @endcan
                                    {{-- @can('setBest', $answer) --}}
                                    <button onclick="openBestModal()"
                                            class="flex items-center gap-1 bg-yellow-500 text-white text-sm px-2 py-1 rounded-md hover:bg-yellow-600 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 -960 960 960" fill="#ffffff"><path d="m387-412 35-114-92-74h114l36-112 36 112h114l-93 74 35 114-92-71-93 71ZM240-40v-309q-38-42-59-96t-21-115q0-134 93-227t227-93q134 0 227 93t93 227q0 61-21 115t-59 96v309l-240-80-240 80Zm240-280q100 0 170-70t70-170q0-100-70-170t-170-70q-100 0-170 70t-70 170q0 100 70 170t170 70ZM320-159l160-41 160 41v-124q-35 20-75.5 31.5T480-240q-44 0-84.5-11.5T320-283v124Zm160-62Z"/></svg>
                                        ベストアンサー
                                    </button>
                                    {{-- @endcan --}}
                                </div>

                                {{-- 回答の返信一覧 --}}
                                @php
                                    $replies = $answer->answerReply;
                                    $firstReply = $replies->first();//最初の一件だけ
                                    $moreReplies = $replies->slice(1);//最初の一件以外の返信を
                                    $moreReplyCount = $moreReplies->count();
                                @endphp
                                <div class="pl-4">
                                    {{-- 最初の1件だけ表示 --}}
                                    @if($firstReply)
                                        <a href="{{ route('profile.show', $firstReply->user->id)}}" class="flex items-center space-x-2">
                                            @if ($firstReply->user->avatar)
                                                <div class="w-8 h-8 rounded-full border border-[var(--accent-color)] shadow overflow-hidden">
                                                    <img class="w-full h-full object-cover"
                                                        src="{{ $firstReply->user->avatar }}"
                                                        alt="{{ $firstReply->user->name }}のアバター">
                                                </div>
                                            @else
                                                <div class="w-8 h-8 rounded-full bg-[var(--bg-green)] flex items-center justify-center text-sm text-[var(--white)] shadow">
                                                    <img src="{{ asset('images/person_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.svg') }}" alt="画像アップロード" class="w-4 h-4 opacity-70">
                                                </div>
                                            @endif
                                            <div>
                                                <p>
                                                    {{ $firstReply->user->name }}さん
                                                    @if ($firstReply->user->id === $questionOwnerId)
                                                        <span class="text-xs bg-[var(--accent-color)] text-[var(--white)] px-2 py-0.5 rounded">質問者</span>
                                                    @endif
                                                </p>
                                            </div>
                                        </a>
                                        <div class="p-3">
                                            <div class="border-l-4 border-gray-400 pl-4 space-y-2">
                                                <p class="text-sm">{{ $firstReply->created_at->format('Y/m/d H:i') }}</p>
                                                <div class="text-base text-[var(--text-main)] leading-relaxed">
                                                    {!! nl2br(preg_replace('/(https?:\/\/[^\s]+)/i', '<a href="$1" target="_blank" rel="noopener noreferrer" class="text-blue-500 hover:text-blue-700 hover:underline">$1</a>', e($firstReply->content))) !!}
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    {{-- 他の返信は非表示に --}}
                                    <div id="moreReplies-{{ $answer->id }}" class="hidden">
                                        @foreach($moreReplies as $reply)
                                            <a href="{{ route('profile.show', $reply->user->id) }}" class="flex items-center space-x-2">
                                                @if ($reply->user->avatar)
                                                    <div class="w-8 h-8 rounded-full border border-[var(--accent-color)] shadow overflow-hidden">
                                                        <img class="w-full h-full object-cover"
                                                            src="{{ $reply->user->avatar }}"
                                                            alt="{{ $reply->user->name }}のアバター">
                                                    </div>
                                                @else
                                                    <div class="w-8 h-8 rounded-full bg-[var(--bg-green)] flex items-center justify-center text-sm text-[var(--white)] shadow">
                                                        <img src="{{ asset('images/person_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.svg') }}" alt="画像アップロード" class="w-4 h-4 opacity-70">
                                                    </div>
                                                @endif
                                                <div>
                                                    <p>
                                                        {{ $reply->user->name }}さん
                                                        @if ($reply->user->id === $questionOwnerId)
                                                            <span class="text-xs bg-[var(--accent-color)] text-[var(--white)] px-2 py-0.5 rounded">質問者</span>
                                                        @endif
                                                    </p>
                                                </div>
                                            </a>
                                            <div class="p-3">
                                                <div class="border-l-4 border-gray-400 pl-4 space-y-2">
                                                    <p class="text-sm">{{ $reply->created_at->format('Y/m/d H:i') }}</p>
                                                    <div class="text-base text-[var(--text-main)] leading-relaxed">
                                                        {!! nl2br(preg_replace('/(https?:\/\/[^\s]+)/i', '<a href="$1" target="_blank" rel="noopener noreferrer" class="text-blue-500 hover:text-blue-700 hover:underline">$1</a>', e($reply->content))) !!}
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    {{-- 展開ボタン --}}
                                    @if($moreReplies->count() > 0)
                                        <button class="toggle-replies-btn flex justify-center items-center w-full bg-[var(--button-bg)] font-medium text-[var(--white)] py-2 shadow rounded-md" data-target="moreReplies-{{ $answer->id }}" data-label="さらに返信を表示（{{ $moreReplyCount }}件)">さらに返信を表示（{{ $moreReplyCount }}件）</button>
                                    @endif
                                </div>
                            </div>
                            <!-- ベストアンサー選択モーダル -->
                            <div id="bestAnswerModal" class="fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50 hidden">
                                <div class="bg-white p-6 rounded-lg max-w-md shadow-lg space-y-6">
                                    <h2 class="text-lg font-semibold border-b border-[var(--texy-brown)] pb-2">
                                        この回答をベストアンサーに設定しますか？
                                    </h2>

                                    <div class="text-base text-red-600 space-y-2 mt-2 mb-4">
                                        <p>※ 投稿時に報酬が設定されていた場合、<span class="font-semibold">選ばれたユーザーに自動で渡されます。</span></p>
                                        <p>※ ベストアンサーを選ぶと、<span class="font-semibold">質問の受付は終了</spanします。</p>
                                        <p>※ <span class="font-semibold">一度選ぶと取り消せません。</span></p>
                                    </div>

                                    <div class="flex justify-center gap-6">
                                        <button onclick="closeBestModal()"
                                                class="px-3 py-1 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400">
                                            キャンセル
                                        </button>

                                        <form id="bestAnswerForm" action="{{ route('answer.setBest', $answer->id) }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                    class="px-3 py-1 bg-yellow-500 text-white rounded-md hover:bg-yellow-600">
                                                ベストアンサーにする
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @elseif ($relatedQuestions->isNotEmpty())
                    @if ($hasSameCategoryQuestions)
                        <a href="{{ route('search.index', ['group' => $questionData->category->group->id]) }}">
                            <h1 class="text-lg font-semibold my-4 hover:underline">同じカテゴリの質問（{{ $relatedQuestions->count() }}件）</h1>
                        </a>
                    @else
                        <a href="{{ route('question.index') }}">
                            <h1 class="text-lg font-semibold my-4 hover:text-[var(--text-main)] hover:underline">最新の質問（{{ $relatedQuestions->count() }}件）</h1>
                        </a>
                    @endif
                    <div class="space-y-4">
                        @foreach ($relatedQuestions as $related)
                            <a href="{{ route('question.show', $related->id) }}" class="block bg-[var(--white)] rounded-md p-3 space-y-2">
                                <p class="text-sm">{{ $related->user->name }}さん</p>
                                <p>{{ Str::limit($related->content, 155, '...') }}</p>
                                <div class="flex items-center text-sm gap-2">
                                    <p>{{ $related->category->name }}</p>
                                    <p>{{ $related->created_at->format('Y/m/d H:i') }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="mt-8 text-gray-500">他の質問はまだありません。</p>
                @endif
            </div>
        </div>
    </div>
    <!-- モーダル本体（初期は非表示） -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-70 hidden items-center justify-center z-50">
        <!-- 背景に対して絶対位置 -->
        <button id="closeModal" class="absolute top-10 right-10 w-14 h-14 bg-[var(--button-bg)] text-white text-2xl font-bold rounded-full flex items-center justify-center z-50">&times;</button>

        <!-- 中央の画像 -->
        <div>
            <img src="{{ $questionData->image_url }}"
                    alt="拡大画像"
                    class="max-w-full max-h-full rounded shadow-lg">
        </div>
    </div>
    @error('error')
        <script>
            alert("{{ $message }}");
        </script>
    @enderror
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const hasImage = {{ !empty($input['image_url']) ? 'true' : 'false' }};
            const buttons = document.querySelectorAll('.toggle-replies-btn');

            if (hasImage) {
                const thumbnail = document.getElementById('thumbnail');
                const modal = document.getElementById('imageModal');
                const closeModal = document.getElementById('closeModal');

                thumbnail.addEventListener('click', () => {
                    $('#imageModal').removeClass('hidden').addClass('flex');
                });

                closeModal.addEventListener('click', () => {
                    $('#imageModal').removeClass('flex').addClass('hidden')
                });

                // モーダルの背景クリックでも閉じる
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) {
                        $('#imageModal').removeClass('flex').addClass('hidden');
                    }
                });
            }

            buttons.forEach(button => {
                button.addEventListener('click', function () {
                    const targetId = this.getAttribute('data-target');//moreRepliesの取得他の返信との連携
                    const targetElem = document.getElementById(targetId);//他の返信一覧を取得
                    if (!targetElem) return;

                    targetElem.classList.toggle('hidden');

                    if (targetElem.classList.contains('hidden')) {
                        // 隠れてたら元の文字に戻す
                        this.textContent = this.getAttribute('data-label');
                    } else {
                        // 見えてたら「閉じる」に
                        this.textContent = '閉じる';
                    }
                });
            });
        });
        const bestModal= document.getElementById('bestAnswerModal');
        function openBestModal() {
            bestModal.classList.remove('hidden');
            bestModal.classList.add('flex');
        }

        function closeBestModal() {
            bestModal.classList.add('hidden');
            bestModal.classList.remove('flex');
        }
    </script>
</body>
</html>
