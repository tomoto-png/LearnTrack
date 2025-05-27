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
            --bg-green: #b3cfad;
            --bg-light-gray: #e3e6d8;
            --text-brown: #7c6f4f;
            --accent-yellow: #d9ca79;
            --button-hover: #d1af4d;
        }
    </style>
</head>
<body class="bg-[var(--bg-green)]">
    <div class="flex flex-col max-w-4xl mx-auto">
        <a href="{{ route('question.index' )}}">
            <span class="font-bold text-2xl">&larr;</span>
        </a>
        <div class="px-6 max-w-xl lg:max-w-3xl w-full mx-auto sm:0">
            {{-- 質問 --}}
            <div class="p-8 bg-[var(--bg-light-gray)] rounded-lg">
                <div class="space-y-4">
                    <div class="flex items-center space-x-2">
                        @if ($questionData->user->avatar)
                            <div class="w-11 h-11 rounded-full border border-[var(--accent-yellow)] shadow overflow-hidden">
                                <img class="w-full h-full object-cover"
                                    src="{{ asset('storage/' . $questionData->user->avatar) }}"
                                    alt="{{ $questionData->user->name }}のアバター">
                            </div>
                        @else
                            <div class="w-11 h-11 rounded-full bg-[var(--bg-green)] flex items-center justify-center text-base text-white shadow">
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
                            <img src="{{ asset('storage/' . $questionData->image_url) }}" alt="" class="w-64 h-auto rounded shadow-md border">
                        </div>
                    @endif


                    @if ($questionData->reward > 0)
                        <div class="flex items-center space-x-1">
                            <img src="{{ asset('images/icons8-トマト-48.png')}}" alt="" class="h-6 w-6">
                            <p class="text-base font-medium">{{ $questionData->reward }}</p>
                        </div>
                    @endif
                    <a href="{{ route('answer.create', ['id' => $questionData->id]) }}">回答する</a>
                </div>
            </div>
            <div>
                {{-- 回答一覧 --}}
            <div class="p-8 bg-[var(--bg-light-gray)] space-y-4 rounded-lg mt-6">
                <h2 class="text-xl">回答</h2>
                @foreach ($questionData->answers as $answer)
                    <div class="space-y-4 border-b border-[var(--texy-brown)] pb-3">
                        <div class="flex items-center space-x-2">
                            @if ($answer->user->avatar)
                                <div class="w-11 h-11 rounded-full border border-[var(--accent-yellow)] shadow overflow-hidden">
                                    <img class="w-full h-full object-cover"
                                        src="{{ asset('storage/' . $answer->user->avatar) }}"
                                        alt="{{ $answer->user->name }}のアバター">
                                </div>
                            @else
                                <div class="w-11 h-11 rounded-full bg-[var(--bg-green)] flex items-center justify-center text-base text-white shadow">
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
                                <img src="{{ asset('storage/' . $answer->image_url) }}" alt="" class="w-64 h-auto rounded shadow-md border">
                            </div>
                        @endif
                        @can('create', [App\Models\AnswerReply::class, $answer])
                            <div>
                                <a href="{{ route('replie.create', $answer->id) }}">返信する</a>
                            </div>
                        @endcan
                        @if ($answer->AnswerReply->isNotEmpty(

                        ))
                            @foreach ($answer->AnswerReply as $reply)
                                <div>
                                    <p>{{ $reply->user->name }}</p>
                                    <p>{{ $reply->content }}</p>
                                </div>
                            @endforeach
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</body>
</html>
