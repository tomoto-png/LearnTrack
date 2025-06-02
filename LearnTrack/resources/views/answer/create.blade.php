<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>回答する</title>
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
                max-height: 445px;
            }
        }
    </style>
</head>
<body class="bg-[var(--bg-green)] text-[var(--text-brown)] min-h-screen flex items-center justify-center">
    <div class="px-6 max-w-xl lg:max-w-3xl w-full">
        <div class="p-6 lg:p-8 bg-[var(--bg-light-gray)] h-[700px] rounded-xl shadow-md">
            <form action="{{ route('answer.store') }}" id="questionForm" method="POST" enctype="multipart/form-data">
                @csrf
                @if ($mode === 'confirm')
                    {{-- 確認画面 --}}
                    <h1 class="text-xl font-semibold border-b border-[var(--texy-brown)] pb-3">回答確認</h1>
                    <div class="space-y-3 mt-2 h-[545px] overflow-y-auto border-b border-[var(--texy-brown)] pb-3">
                        <div class="flex items-center space-x-2">
                            @if ($user->avatar)
                                <div class="w-12 h-12 rounded-full border border-[var(--accent-color)] shadow overflow-hidden">
                                    <img class="w-full h-full object-cover"
                                        src="{{ $user->avatar }}"
                                        alt="{{ $user->name }}のアバター">
                                </div>
                            @else
                                <div class="w-12 h-12 rounded-full bg-[var(--bg-green)] flex items-center justify-center text-sm text-[var(--white)] shadow">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <p>{{ $user->name }}さん</p>
                                <p class="text-sm">投稿日時</p>
                            </div>
                        </div>
                        <div>
                            <p id="text" class="text-base leading-relaxed {{ !empty($input['image_url']) ? 'max-h-52' : 'max-h-custom' }} overflow-hidden">{!! nl2br(preg_replace('/(https?:\/\/[^\s]+)/i',
                                '<a href="$1" target="_blank" rel="noopener noreferrer" class="text-blue-500 hover:underline">$1</a>',
                                e($input['content']))) !!}</p>
                            <button type="button" id="toggleBtn" class="text-blue-500 hover:text-blue-700 hidden">...続きを読む</button>
                            <input type="hidden" name="content" value="{{ $input['content'] }}">
                        </div>
                        <input type="hidden" name="question_id" value="{{ $questionInput->id }}">
                        @if (!empty($input['image_url']))
                            <div class="flex justify-center my-6">
                                <img src="{{ asset('storage/' . $input['image_url']) }}"
                                    alt="アップロード画像"
                                    class="w-64 h-auto rounded shadow-md border">
                            </div>
                        @endif

                        <input type="hidden" id="modeInput" name="mode" value="">
                    </div>
                    <div class="flex justify-end space-x-4 mt-4">
                        <button type="button"
                            onclick="submitWithMode('edit')"
                            class="px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 transition">修正する</button>
                        <button type="button"
                            onclick="submitWithMode('post')"
                            class="px-4 py-2 rounded-lg bg-[var(--button-bg)] text-[var(--white)] font-semibold hover:bg-[--button-hover] transition">回答する</button>
                    </div>
                @elseif ($mode === 'input')
                    {{-- 入力画面 --}}
                    <h1 class="text-xl font-semibold border-b border-[var(--texy-brown)] pb-3">回答投稿</h1>
                    <div class="space-y-3 mt-2 h-[545px] overflow-y-auto border-b border-[var(--texy-brown)] pb-3 px-1">
                        <div>
                            <p class="block text-base font-semibold mb-2">質問内容</p>
                            <div class="p-1 border-l-4 border-gray-400 pl-4 space-y-1">
                                <p id="text" class="text-base leading-relaxed max-h-12 overflow-hidden"> {!! nl2br(preg_replace('/(https?:\/\/[^\s]+)/i', '<a href="$1" target="_blank" rel="noopener noreferrer" class="text-blue-500 hover:text-blue-700 hover:underline">$1</a>', e(($questionInput ?? $question)->content))) !!}</p>
                                <button type="button" id="toggleBtn" class="text-blue-500 hover:text-blue-700 hidden">...続きを読む</button>
                            </div>
                        </div>

                        <div>
                            <label for="content" class="block text-base font-semibold mb-2">回答文</label>
                            <textarea name="content" id="content"
                                class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none h-40 sm:h-46 lg:h-52 focus:ring-2 focus:ring-[var(--accent-color)]"
                                placeholder="5文字〜2000文字で入力してください。">{{ old('content', $input['content'] ?? '') }}</textarea>
                            <p class="text-sm mb-2">
                                ※URLを使用する際は、後ろにスペースや改行を入れてから質問内容を入力してください。
                            </p>
                            @error('content')
                                <p class="text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="image" class="cursor-pointer flex items-center space-x-2">
                                <img src="{{ asset('images/imag.svg') }}" alt="画像アップロード" class="w-5 h-5 opacity-70 hover:opacity-100 transition" />
                                <span class="text-base">画像を添付（任意）</span>
                            </label>
                            <input type="file" name="image_url" id="image" accept="image/*" class="hidden">
                        </div>

                        @php
                            $previewImagePath = session('confirm_image_path');
                        @endphp
                        <div class="mt-2 {{ $previewImagePath ? '' : 'hidden' }}" id="preview-area">
                            <div class="relative inline-block">
                                <img id="image-preview" src="{{ $previewImagePath ? asset('storage/' . $previewImagePath) : '' }}" class="w-32 h-auto rounded border" />
                                <input type="hidden" name="remove_image" id="remove_image" value="false">
                                <!-- バツボタン -->
                                <button id="clear-image"
                                        class="absolute top-0 right-0 transform translate-x-1/2 -translate-y-1/2 bg-gray-700 text-[var(--white)] rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-500"
                                        type="button" aria-label="画像を削除">
                                    ×
                                </button>
                            </div>
                        </div>
                        @error('image_url')
                            <p class="text-red-500">{{ $message }}</p>
                        @enderror
                        <input type="hidden" name="mode" value="confirm">
                        <input type="hidden" name="question_id" value="{{ $question->id ?? $questionInput->id }}">
                    </div>
                    <div class="flex justify-end space-x-4 mt-4">
                        <a href = "{{ route('answer.cancel', ['id' => ($question ?? $questionInput)->id]) }}" class="px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 transition">キャンセル</a>
                        <button type="submit"
                            class="px-4 py-2 rounded-lg bg-[var(--button-bg)] text-[var(--white)] font-semibold hover:bg-[var(--button-hover)] transition">確認する</button>
                    </div>
                @endif
            </form>
        </div>
    </div>
    <script>
        const inputImage = document.getElementById('image');
        const image = document.getElementById('remove_image');
        const previewImage = document.getElementById('image-preview');
        const previewArea = document.getElementById('preview-area');
        const clearBtn = document.getElementById('clear-image');
        const modeInput = document.getElementById('modeInput');
        const form = document.getElementById('questionForm');
        const text = document.getElementById('text');
        const btn = document.getElementById('toggleBtn');

        if (inputImage) {

            inputImage.addEventListener('change', function (event) {//ユーザーがファイルを選択（または変更）したときに "change" イベントが発生します, 	event には「どんなイベントが起きたか」の情報が入っている
                const file = event.target.files[0];//event.target は <input type="file"> 要素を取得
                if (file) {
                    image.value = 'false';
                    const reader = new FileReader();//FileReader を作成, ローカルファイルを読み込むためのツール
                    reader.onload = function(e) {   //reader.onload はファイルの読み込みが完了したときに実行される処理
                        previewImage.src = e.target.result;//読み込んだBase64形式のデータURLを<img>のsrcに
                        previewArea.classList.remove('hidden');
                    };
                    reader.readAsDataURL(file);//画像ファイルを Base64 のURLとして読み込み開始
                } else {
                    previewImage.src = '';
                    previewArea.classList.add('hidden');
                }
            });
        }

        if (clearBtn) {
            clearBtn.addEventListener('click', function () {
                inputImage.value = '';// ファイル選択をクリア
                previewImage.src = '';
                previewArea.classList.add('hidden');
                image.value = 'true';
            });
        }

        function submitWithMode(mode) {
            modeInput.value = mode;
            form.submit();
        }


        const computedStyle = window.getComputedStyle(text);//スタイル情報を取得
        const lineHeight = parseFloat(computedStyle.lineHeight);//1行分の高さを取得
        const lineCount = text.scrollHeight / lineHeight;//全体の質問文の高さと1行分高さの計算で行数が出る
        const mode = "{{ $mode }}";
        const hasImage = {{ !empty($input['image_url']) ? 'true' : 'false' }};
        const limitLines = (mode === 'confirm') ?  19: 2;
        let styleHeight;

        if (mode === 'confirm' && hasImage) {
            styleHeight = 'max-h-52';
        } else if (mode === 'confirm') {
            styleHeight = 'max-h-custom';
        } else {
            styleHeight = 'max-h-12';
        }
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
