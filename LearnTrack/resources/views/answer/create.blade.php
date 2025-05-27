<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>回答する</title>
</head>
<body>
    <form action="{{ route('answer.store') }}" id="questionForm" method="POST" enctype="multipart/form-data">
        @csrf
        @if ($mode === 'confirm')
            <div>
                <p>確認画面</p>
                <p>{!! nl2br(preg_replace('/(https?:\/\/[^\s]+)/i',
                '<a href="$1" target="_blank" rel="noopener noreferrer" class="text-blue-500 hover:underline">$1</a>',
                e($input['content']))) !!}</p>
                <input type="hidden" name="content" value="{{ $input['content'] }}">
                <input type="hidden" name="question_id" value="{{ $questionInput->id }}">
                @if (!empty($input['image_url']))
                    <img src="{{ asset('storage/'. $input['image_url']) }}" alt="">
                @endif
                <input type="hidden" id="modeInput" name="mode" value="">
                <div>
                    <button type="button" onclick="submitWithMode('edit')">修正する</button>
                    <button type="button" onclick="submitWithMode('post')">回答する</button>
                </div>
            </div>
        @elseif ($mode === 'input')
            <div>
                <div>
                    <h1>質問回答</h1>
                    <p> {!! nl2br(preg_replace('/(https?:\/\/[^\s]+)/i', '<a href="$1" target="_blank" rel="noopener noreferrer" class="text-blue-500 hover:text-blue-700 hover:underline">$1</a>', e(($questionInput ?? $question)->content))) !!}</p>
                </div>
                <div>
                    <h1>回答文</h1>
                    <textarea name="content" cols="30" rows="10">{{ old('content', $input['content'] ?? '') }}</textarea>
                    @error('content')
                        <p>{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="image" class="cursor-pointer flex items-center space-x-2">
                        <img src="{{ asset('images/imag.svg') }}" alt="画像アップロード" class="w-6 h-6 opacity-70 hover:opacity-100 transition" />
                        <span class="">画像を添付（任意）</span>
                    </label>
                    <input type="file" name="image_url" id="image" accept="image/*" class="hidden">
                </div>

                @php
                    $previewImagePath = session('confirm_image_path');
                @endphp
                <div class="mt-4 {{ $previewImagePath ? '' : 'hidden' }}" id="preview-area">
                    <div class="relative inline-block">
                        <img id="image-preview" src="{{ $previewImagePath ? asset('storage/' . $previewImagePath) : '' }}" class="w-32 h-auto rounded border" />
                        <input type="hidden" name="remove_image" id="remove_image" value="false">
                        <!-- バツボタン -->
                        <button id="clear-image"
                                class="absolute top-0 right-0 transform translate-x-1/2 -translate-y-1/2 bg-gray-700 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-500"
                                type="button" aria-label="画像を削除">
                            ×
                        </button>
                    </div>
                </div>
                @error('image_url')
                    <p>{{ $message }}</p>
                @enderror
                <input type="hidden" name="mode" value="confirm">
                <input type="hidden" name="question_id" value="{{ $question->id ?? $questionInput->id }}">
                <div>
                    <a href="{{ route('question.show', ['id' => ($question ?? $questionInput)->id]) }}">キャンセル</a>
                    <button type="submit">確認する</button>
                </div>
            </div>
        @endif
    </form>
    <script>
        const inputImage = document.getElementById('image');
        const image = document.getElementById('remove_image');
        const previewImage = document.getElementById('image-preview');
        const previewArea = document.getElementById('preview-area');
        const clearBtn = document.getElementById('clear-image');
        const modeInput = document.getElementById('modeInput');
        const form = document.getElementById('questionForm');
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
    </script>
</body>
</html>
