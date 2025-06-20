<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>Document</title>
</head>
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
    @layer utilities {
        .max-h-custom {
            max-height: 315px;
        }
    }
</style>
<body class="bg-[var(--bg-green)] text-[var(--text-brown)] min-h-screen flex items-center justify-center">
    <div class="px-6 max-w-xl lg:max-w-3xl w-full">
        <div class="p-6 lg:p-8 bg-[var(--bg-light-gray)] h-[600px] rounded-xl shadow-md">
            <form id="questionForm" action="{{ route('replie.store') }}" method="POST">
                @csrf
                @if ($mode === 'confirm')
                    <div>
                        <h1 class="text-xl font-semibold border-b border-[var(--texy-brown)] pb-3">返信確認</h1>
                        <div class="space-y-3 mt-2 h-[440px] overflow-y-auto border-b border-[var(--texy-brown)] pb-3 px-1">
                            <div class="flex items-center space-x-2">
                                @if ($user->avatar)
                                    <div class="w-12 h-12 rounded-full border border-[var(--accent-color)] shadow overflow-hidden">
                                        <img class="w-full h-full object-cover"
                                            src="{{ $user->avatar }}"
                                            alt="{{ $user->name }}のアバター">
                                    </div>
                                @else
                                    <div class="w-12 h-12 rounded-full bg-[var(--bg-green)] flex items-center justify-center text-sm text-[var(--white)] shadow">
                                        <img src="{{ asset('images/person_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.svg') }}" alt="画像アップロード" class="w-4 h-4 opacity-70">
                                    </div>
                                @endif
                                <div>
                                    <p>{{ $user->name }}さん</p>
                                    <p class="text-sm">投稿日時</p>
                                </div>
                            </div>
                            <div>
                                <p id="text" class="text-base leading-relaxed max-h-custom overflow-hidden">{!! nl2br(preg_replace('/(https?:\/\/[^\s]+)/i',
                                    '<a href="$1" target="_blank" rel="noopener noreferrer" class="text-blue-500 hover:underline">$1</a>',
                                    e($replieInput->content))) !!}</p>
                                <button type="button" id="toggleBtn" class="text-blue-500 hover:text-blue-700 hidden">...続きを読む</button>
                                <input type="hidden" name="content" value="{{ $input['content'] }}">
                            </div>
                            <input type="hidden" name="content" value="{{ $replieInput->content }}">
                            <input type="hidden" name="answer_id" value="{{ $replieInput->answer_id }}">
                            <input type="hidden" name="mode" id="modeInput" value="">
                        </div>
                        <div class="flex justify-end space-x-4 mt-4">
                            <button type="button"
                                onclick="submitWithMode('edit')"
                                class="px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 transition">修正する</button>
                            <button type="button"
                                onclick="submitWithMode('post')"
                                class="px-4 py-2 rounded-lg bg-[var(--button-bg)] text-[var(--white)] font-semibold hover:bg-[--button-hover] transition">返信する</button>
                        </div>
                    </div>
                @elseif ($mode === 'input')
                    <div>
                        <h1 class="text-xl font-semibold border-b border-[var(--texy-brown)] pb-3">返信投稿</h1>
                        <div class="space-y-3 mt-2 h-[440px] overflow-y-auto border-b border-[var(--texy-brown)] pb-3 px-1">
                            <div>
                                <p class="block text-base font-semibold mb-2">回答内容</p>
                                <div class="p-1 border-l-4 border-gray-400 pl-4 space-y-1">
                                    <p id="text" class="text-base leading-relaxed max-h-12 overflow-hidden"> {!! nl2br(preg_replace('/(https?:\/\/[^\s]+)/i', '<a href="$1" target="_blank" rel="noopener noreferrer" class="text-blue-500 hover:text-blue-700 hover:underline">$1</a>', e(($answer ?? $input)->content))) !!}</p>
                                    <button type="button" id="toggleBtn" class="text-blue-500 hover:text-blue-700 hidden">...続きを読む</button>
                                </div>
                            </div>
                            <div>
                                <label for="content" class="block text-base font-semibold mb-2">回答文</label>
                                <textarea
                                    name="content"
                                    id="content"
                                    class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none h-40 sm:h-46 lg:h-52 focus:ring-2 focus:ring-[var(--accent-color)]"
                                    placeholder="5文字〜2000文字で入力してください。"
                                    minlength="5"
                                    maxlength="2000">{{ old('content', $replieInput->content ?? '')}}</textarea>
                                <p class="text-sm mb-2">
                                    ※URLを使用する際は、後ろにスペースや改行を入れてから質問内容を入力してください。
                                </p>
                                @error('content')
                                    <p class="text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <input type="hidden" name="answer_id" id="answer_id" value="{{ $answer->id ?? $replieInput->answer_id}}">
                        <input type="hidden" name="mode" id="mode" value="confirm">
                        <div class="flex justify-end space-x-4 mt-4">
                            <a href="{{ route('question.show', ['id' => ($questionId)]) }}" class="px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 transition">キャンセル</a>
                            <button type="submit"
                                class="px-4 py-2 rounded-lg bg-[var(--button-bg)] text-[var(--white)] font-semibold hover:bg-[var(--button-hover)] transition">確認する</button>
                        </div>
                    </div>
                @endif
            </form>
        </div>
    </div>
    <script>
        const modeInput = document.getElementById('modeInput');
        const form = document.getElementById('questionForm');
        const text = document.getElementById('text');
        const btn = document.getElementById('toggleBtn');

        function submitWithMode(mode) {
            modeInput.value = mode;
            form.submit();
        }

        const computedStyle = window.getComputedStyle(text);//スタイル情報を取得
        const lineHeight = parseFloat(computedStyle.lineHeight);//1行分の高さを取得
        const lineCount = text.scrollHeight / lineHeight;//全体の質問文の高さと1行分高さの計算で行数が出る
        const mode = "{{ $mode }}";
        const limitLines = (mode === 'confirm') ?  23: 2;
        const styleHeight = (mode === 'confirm') ?  'max-h-custom': 'max-h-12';

        if (lineCount > limitLines) {
            btn.classList.remove('hidden');
            btn.addEventListener('click', () => {
                if (text.className.includes(styleHeight)) {
                    text.classList.remove(styleHeight);
                    btn.textContent = '閉じる';
                } else {
                    text.classList.add(styleHeight);
                    btn.textContent = '...続きを読む';
                }
            })
        }
    </script>
</body>
</html>
