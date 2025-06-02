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
            --button-bg: #6c8c5d;
            --button-hover: #57724a;
            --accent-color: #3f5c38;
            --white: white;
        }
    </style>
</head>
<body class="bg-[var(--bg-green)] text-[var(--text-brown)]">
    <div class="flex flex-col max-w-6xl p-4 mx-auto">
        <a href="{{ route('question.index' )}}">
            <span class="font-bold py-8 text-2xl">&larr;</span>
        </a>
        <div class="px-6 max-w-xl lg:max-w-3xl mt-12 w-full mx-auto sm:0">
            {{-- 質問 --}}
            <div class="p-8 bg-[var(--bg-light-gray)] rounded-md">
                <div class="space-y-4">
                    <div class="flex items-center space-x-2">
                        @if ($questionData->user->avatar)
                            <div class="w-11 h-11 rounded-full border border-[var(--accent-color)] shadow overflow-hidden">
                                <img class="w-full h-full object-cover"
                                    src="{{ $questionData->user->avatar }}"
                                    alt="{{ $questionData->user->name }}のアバター">
                            </div>
                        @else
                            <div class="w-11 h-11 rounded-full bg-[var(--bg-green)] flex items-center justify-center text-base text-[var(--white)] shadow">
                                {{ strtoupper(substr($questionData->user->name, 0, 1)) }}
                            </div>
                        @endif
                        <div>
                            <p>{{ $questionData->user->name }}さん</p>
                            <p class="text-sm">{{ $questionData->updated_at->format('Y/m/d H:i') }}</p>
                        </div>
                    </div>

                    {{-- nl2brは/nがある時に改行する、コード内にURLがあるとpタグからaタグに変換する --}}
                    <div class="text-base leading-relaxed">
                        {!! nl2br(preg_replace('/(https?:\/\/[^\s]+)/i', '<a href="$1" target="_blank" rel="noopener noreferrer" class="text-blue-500 hover:text-blue-700 hover:underline">$1</a>', e($questionData->content))) !!}
                    </div>

                    @if (!@empty($questionData->image_url))
                        <div class="flex justify-center my-6">
                            <img src="{{ $questionData->image_url }}" alt="" class="w-64 h-auto rounded shadow-md border" id="thumbnail">
                        </div>
                    @endif


                    @if ($questionData->reward > 0)
                        <div class="flex items-center space-x-1">
                            <img src="{{ asset('images/icons8-トマト-48.png')}}" alt="" class="h-6 w-6">
                            <p class="text-base font-medium">{{ $questionData->reward }}</p>
                        </div>
                    @endif
                    <p class="text-sm">
                        {{ $questionData->category->group->name }} / {{ $questionData->category->name }}
                    </p>
                </div>
                @can('create', [App\Models\Answer::class, $questionData])
                    <div class="border-t border-[var(--texy-brown)] pt-3 mt-3">
                        <a href="{{ route('answer.create', ['id' => $questionData->id]) }}" class="flex justify-center items-center bg-[var(--button-bg)] font-medium text-[var(--white)] py-2 shadow rounded-md">回答する</a>
                    </div>
                @endcan
            </div>
            {{-- 回答一覧 --}}
            <div class="bg-[var(--bg-light-gray)] mt-6 p-8 rounded-md">
                @if (@empty($questionData->answers))
                    <h2 class="text-xl font-semibold">回答</h2>
                    <div class="space-y-4">
                        @foreach ($questionData->answers as $answer)
                            <div class="space-y-3 border-b border-[var(--text-brown)] pb-3">
                                <div class="flex items-center space-x-2">
                                    @if ($answer->user->avatar)
                                        <div class="w-11 h-11 rounded-full border border-[var(--accent-color)] shadow overflow-hidden">
                                            <img class="w-full h-full object-cover"
                                                src="{{ $answer->user->avatar }}"
                                                alt="{{ $answer->user->name }}のアバター">
                                        </div>
                                    @else
                                        <div class="w-11 h-11 rounded-full bg-[var(--bg-green)] flex items-center justify-center text-base text-[var(--white)] shadow">
                                            {{ strtoupper(substr($answer->user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <p>{{ $answer->user->name }}さん</p>
                                        <p class="text-sm">{{ $answer->updated_at->format('Y/m/d H:i') }}</p>
                                    </div>
                                </div>
                                <div class="text-base leading-relaxed">
                                    {!! nl2br(preg_replace('/(https?:\/\/[^\s]+)/i', '<a href="$1" target="_blank" rel="noopener noreferrer" class="text-blue-500 hover:text-blue-700 hover:underline">$1</a>', e($answer->content))) !!}
                                </div>
                                @if (!@empty($answer->image_url))
                                    <div class="flex justify-center my-6">
                                        <img src="{{ $answer->image_url }}" alt="" class="w-64 h-auto rounded shadow-md border">
                                    </div>
                                @endif
                                @can('create', [App\Models\AnswerReply::class, $answer])
                                    <div>
                                        <a href="{{ route('replie.create', $answer->id) }}">返信する</a>
                                    </div>
                                @endcan
                                {{-- 回答の返信一覧 --}}
                                @foreach ($answer->AnswerReply as $reply)
                                    <div class="pl-4">
                                        <div class="flex items-center space-x-2">
                                            @if ($reply->user->avatar)
                                                <div class="w-8 h-8 rounded-full border border-[var(--accent-color)] shadow overflow-hidden">
                                                    <img class="w-full h-full object-cover"
                                                        src="{{ $reply->user->avatar }}"
                                                        alt="{{ $reply->user->name }}のアバター">
                                                </div>
                                            @else
                                                <div class="w-11 h-11 rounded-full bg-[var(--bg-green)] flex items-center justify-center text-base text-[var(--white)] shadow">
                                                    {{ strtoupper(substr($reply->user->name, 0, 1)) }}
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
                                        </div>
                                        <div class="p-3">
                                            <div class="border-l-4 border-gray-400 pl-4 space-y-2">
                                                <p class="text-sm">{{ $reply->updated_at->format('Y/m/d H:i') }}</p>
                                                <div class="text-base leading-relaxed">
                                                    {!! nl2br(preg_replace('/(https?:\/\/[^\s]+)/i', '<a href="$1" target="_blank" rel="noopener noreferrer" class="text-blue-500 hover:text-blue-700 hover:underline">$1</a>', e($reply->content))) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                @elseif ($relatedQuestions->isNotEmpty())
                    @if ($hasSameCategoryQuestions)
                        <h1 class="text-lg font-semibold border-b border-[var(--texy-brown)] pb-2">同じカテゴリの質問</h1>
                    @else
                        <h1 class="text-lg font-semibold border-b border-[var(--texy-brown)] pb-2">最新の質問</h1>
                    @endif
                    <div class="mt-4 space-y-4">
                        @foreach ($relatedQuestions as $related)
                            <a href="{{ route('question.show', $related->id) }}" class="block bg-[var(--white)] rounded-md p-3 space-y-2">
                                <p class="text-sm">{{ $related->user->name }}さん</p>
                                <p>{{ Str::limit($related->content, 155, '...') }}</p>
                                <div class="flex items-center text-sm gap-2">
                                    <p>{{ $related->category->name }}</p>
                                    <p>{{ $related->updated_at->format('Y/m/d H:i') }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="mt-8 text-gray-500">他の質問はまだありません。</p>
                @endif
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
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const hasImage = {{ !empty($input['image_url']) ? 'true' : 'false' }};
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
        });
    </script>
</body>
</html>
