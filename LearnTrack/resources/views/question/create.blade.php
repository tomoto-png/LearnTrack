<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>質問</title>
    <style>
        :root {
            --bg-green: #b3cfad;
            --bg-light-gray: #e3e6d8;
            --text-brown: #7c6f4f;
            --accent-yellow: #d9ca79;
            --button-hover: #d1af4d;
        }
        .toggle-checkbox:checked + .toggle-label {
            background-color: #d9ca79;
        }

        .toggle-checkbox:checked + .toggle-label .toggle-circle {
            transform: translateX(24px);
        }

        .toggle-label {
            background-color: #d1d5db;
        }

        .toggle-circle {
            transition: transform 0.3s ease;
        }
    </style>
</head>
<body class="bg-[var(--bg-green)] text-[var(--text-brown)] min-h-screen flex items-center justify-center">
    <div class="px-6 max-w-xl lg:max-w-3xl w-full">
        <div class="p-6 lg:p-8 bg-[var(--bg-light-gray)] rounded-xl shadow-md">
            <form id="questionForm" action="{{ route('question.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                @if ($mode === 'confirm')
                    <h1 class="text-xl font-semibold border-b-2 border-[var(--texy-brown)] pb-3">質問確認</h1>

                    <div class="space-y-3 mt-2 border-b-2 border-[var(--texy-brown)] pb-3">
                        <div class="flex items-center space-x-2">
                            @if ($user->avatar)
                                <div class="w-12 h-12 rounded-full border border-[var(--accent-yellow)] shadow overflow-hidden">
                                    <img class="w-full h-full object-cover"
                                        src="{{ asset('storage/' . $user->avatar) }}"
                                        alt="{{ $user->name }}のアバター">
                                </div>
                            @else
                                <div class="w-12 h-12 rounded-full bg-[var(--bg-green)] flex items-center justify-center text-sm text-white shadow">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <p>{{ $user->name }}さん</p>
                                <p class="text-sm">投稿日時</p>
                            </div>
                        </div>
                        <div>
                            <div class="rounded border text-base leading-relaxed">
                                {!! nl2br(preg_replace('/(https?:\/\/[^\s]+)/i',
                                '<a href="$1" target="_blank" rel="noopener noreferrer" class="text-blue-500 hover:underline">$1</a>',
                                e($input['content']))) !!}
                            </div>
                            <input type="hidden" name="content" value="{{ $input['content'] }}">
                        </div>

                        @if (!empty($input['image_url']))
                            <div class="flex justify-center my-6">
                                <img src="{{ asset('storage/' . $input['image_url']) }}"
                                     alt="アップロード画像"
                                     class="w-64 h-auto rounded shadow-md border">
                            </div>
                        @endif

                        @if ($input['reward'] > 0)
                            <div class="flex items-center space-x-1">
                                <img src="{{ asset('images/icons8-トマト-48.png')}}" alt="" class="h-6 w-6">
                                <p class="text-base font-medium">{{ $input['reward'] }}</p>
                            </div>
                        @endif
                        <input type="hidden" name="reward" value="{{ $input['reward'] }}">
                    </div>
                    <div class="space-y-1 my-5">
                        <div class="flex items-center justify-between">
                            <label for="autoRepostEnabled" class="text-lg font-medium text-[var(--text-brown)]">自動再質問</label>
                            <input type="checkbox" id="autoRepostEnabled" name="auto_repost_enabled"
                                class="toggle-checkbox hidden" value="1" {{ old('auto_repost_enabled', $input['auto_repost_enabled'] ?? false) ? 'checked' : ''}}>
                            <!-- スイッチの背景のデザイン -->
                            <label for="autoRepostEnabled" class="toggle-label block w-14 h-8 rounded-full transition bg-gray-300 relative cursor-pointer">
                                <!-- スイッチ内部の円 -->
                                <span class="absolute left-1 top-1 w-6 h-6 bg-white rounded-full transition-transform toggle-circle"></span>
                            </label>
                        </div>
                        <p class="text-sm">※この設定を有効にすると、掲載期間中に回答がつかなかった場合、自動的に質問が再投稿されます。</p>
                        <p class="text-sm">※無効の場合、ベストアンサー報酬は自動的に返却され、質問は削除されます。</p>
                        @error('auto_repost_enabled')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <input type="hidden" name="mode" id="modeInput" value="">
                    <div class="flex justify-end space-x-4">
                        <button type="button"
                            onclick="submitWithMode('edit')"
                            class="px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 transition">修正する</button>
                        <button type="button"
                            onclick="submitWithMode('post')"
                            class="px-4 py-2 rounded-lg bg-green-500 text-white font-semibold hover:bg-green-600 transition">投稿する</button>
                    </div>
                @else
                    {{-- 入力画面 --}}
                    <h1 class="text-xl font-semibold border-b-2 border-[var(--texy-brown)] pb-3">質問投稿</h1>
                    <div class="space-y-3 mt-2">
                        <div>
                            <label for="content" class="block text-base font-semibold mb-3">質問文</label>
                            <textarea name="content" id="content"
                                class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none h-40 sm:h-48 lg:h-56 focus:ring-2 focus:ring-green-300"
                                placeholder="例：〇〇の解き方がわからないので教えてください。">{{ old('content', $input['content'] ?? '') }}</textarea>
                            <p class="text-sm mb-2">
                                ※URLを使用する際は、後ろにスペースや改行を入れてから質問内容を入力してください。
                            </p>
                            @error('content')
                                <p class="text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="image" class="cursor-pointer flex items-center space-x-2">
                                <img src="{{ asset('images/imag.svg') }}" alt="画像アップロード" class="w-6 h- opacity-70 hover:opacity-100 transition" />
                                <span class="text-base text-gray-500">画像を添付（任意）</span>
                            </label>
                            <input type="file" name="image_url" id="image" accept="image/*" class="hidden">
                        </div>

                        @php
                            $previewImagePath = old('image_url') ?: session('temp_image_path');
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
                            <p class="text-red-500">{{ $message }}</p>
                        @enderror
                        <div class="mb-4">
                            <div class="flex items-center">
                                <label for="reward" class="block text-base font-semibold mb-1">
                                    お礼金額（任意）
                                </label>
                            </div>
                            <p class="text-sm mb-2">
                                ※ベストな回答者に送られます
                            </p>
                            <div id="rewardSelect" class="flex items-center space-x-2">
                                <img src="{{ asset('images/icons8-トマト-48.png')}}" alt="" class="h-6 w-6">
                                <select name="reward" id="reward" class="w-18 px-2 py-1 border rounded-md text-sm">
                                    <option value="0">なし</option>
                                    <option value="10" {{ (old('reward', $input['reward'] ?? '') == 10) ? 'selected' : ''}}>10個</option>
                                    <option value="30" {{ (old('reward', $input['reward'] ?? '') == 30) ? 'selected' : ''}}>30個</option>
                                    <option value="60" {{ (old('reward', $input['reward'] ?? '') == 60) ? 'selected' : ''}}>60個</option>
                                </select>
                                <span class="text-sm">/ {{ $user->count }}個中</span>
                            </div>
                            @error('reward')
                                <p class="text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <input type="checkbox" id="autoRepostEnabled" name="auto_repost_enabled" value="1" class="hidden"
                            {{ old('auto_repost_enabled', $input['auto_repost_enabled'] ?? false) ? 'checked' : '' }}>
                        @error('auto_repost_enabled')
                            <p class="text-red-500">{{ $message }}</p>
                        @enderror
                        <input type="hidden" name="mode" value="confirm">
                        <div class="flex justify-end space-x-4">
                            <a href="{{ route('question.cancel') }}"
                                class="px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 transition">キャンセル</a>
                            <button type="submit"
                                class="px-4 py-2 rounded-lg bg-green-500 text-white font-semibold hover:bg-green-600 transition">確認する</button>
                        </div>
                    </div>
                @endif
            </form>
        </div>
    </div>
    <script>
        const inputImage = document.getElementById('image');
        const previewArea = document.getElementById('preview-area');
        const previewImage = document.getElementById('image-preview');
        const clearBtn = document.getElementById('clear-image');
        const image = document.getElementById('remove_image');

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
            document.getElementById('modeInput').value = mode;
            document.getElementById('questionForm').submit();
        };
    </script>
</body>
</html>
