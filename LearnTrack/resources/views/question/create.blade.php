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
            --bg-green: #a0b89c;
            --bg-light-gray: #d6d9c8;
            --text-brown: #6b5e3f;
            --button-bg: #6c8c5d;
            --button-hover: #57724a;
            --accent-color: #3f5c38;
            --white: white;
        }
        .toggle-checkbox:checked + .toggle-label {
            background-color: var(--button-bg);
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
                    <h1 class="text-xl font-semibold border-b border-[var(--texy-brown)] pb-3">質問確認</h1>

                    <div class="space-y-3 mt-2 h-[350px] overflow-y-auto">
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
                            <p id="text" class="text-base leading-relaxed {{ !empty($input['image_url']) ? 'max-h-20' : 'max-h-64' }} overflow-hidden">
                                {!! nl2br(preg_replace('/(https?:\/\/[^\s]+)/i',
                                '<a href="$1" target="_blank" rel="noopener noreferrer" class="text-blue-500 hover:underline">$1</a>',
                                e($input['content']))) !!}
                            </p>
                            <button type="button" id="toggleBtn" class="text-blue-500 hover:text-blue-700 mt-1 hidden">...続きを読む</button>
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
                        @error('reward')
                            <p class="text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="border-t border-[var(--texy-brown)] pt-5 space-y-3 mb-2">
                        <div class="flex items-center justify-between">
                            <p class="text-lg font-medium select-none">カテゴリーを選択</p>
                            <button type="button" id="group-btn" class="text-lg pr-4">
                                ▲
                            </button>
                        </div>
                        <p class="text-sm">
                            ※ カテゴリーを選択しない場合は、自動的に「その他」カテゴリーが適用されます。
                        </p>

                        @foreach ($categoryGroups as $group)
                            <div class="p-4 border-b border-[var(--texy-brown)] pb-2 group hidden">
                                <!-- 展開ボタン -->
                                <button type="button"
                                class="toggle-btn flex justify-between items-center w-full text-base font-light focus:outline-none"
                                data-target="group-{{ $group->id }}">
                                <span>{{ $group->name }}</span>
                                <span class="arrow text-lg">▲</span>
                                </button>

                                <!-- 選択肢 -->
                                <div id="group-{{ $group->id }}" class="category-options hidden mt-4 flex-wrap gap-3">
                                    @foreach ($group->categories as $category)
                                        <label class="cursor-pointer select-none">
                                            <input type="radio" name="category_id" value="{{ $category->id }}" class="hidden peer" {{ old('category_id', $input['category_id'] ?? '') == $category->id ? 'checked' : '' }}/>
                                            <div
                                                class="px-3 py-1 rounded-full border border-[var(--accent-color)] peer-checked:bg-[var(--button-bg)] peer-checked:text-[var(--white)] peer-checked:border-transparent transition-colors duration-200 select-none">
                                                {{ $category->name }}
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="space-y-1 mb-8">
                        <div class="flex items-center justify-between">
                            <label for="autoRepostEnabled" class="text-lg font-medium text-[var(--text-brown)]">自動再質問</label>
                            <input type="checkbox" id="autoRepostEnabled" name="auto_repost_enabled"
                                class="toggle-checkbox hidden" value="1" {{ old('auto_repost_enabled', $input['auto_repost_enabled'] ?? false) ? 'checked' : ''}}>
                            <!-- スイッチの背景のデザイン -->
                            <label for="autoRepostEnabled" class="toggle-label block w-14 h-8 rounded-full transition bg-gray-300 relative cursor-pointer">
                                <!-- スイッチ内部の円 -->
                                <span class="absolute left-1 top-1 w-6 h-6 bg-[var(--white)] rounded-full transition-transform toggle-circle"></span>
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
                            class="px-4 py-2 rounded-lg bg-[var(--button-bg)] text-[var(--white)] font-semibold hover:bg-[--button-hover] transition">投稿する</button>
                    </div>
                @else
                    {{-- 入力画面 --}}
                    <h1 class="text-xl font-semibold border-b border-[var(--texy-brown)] pb-3">質問投稿</h1>
                    <div class="space-y-3 mt-2 overflow-y-auto">
                        <div class="h-[430px] overflow-y-auto px-1">
                            <div>
                                <label for="content" class="block text-base font-semibold mb-3">質問文</label>
                                <textarea
                                    name="content"
                                    id="content"
                                    class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none h-40 sm:h-48 lg:h-56 focus:ring-2 focus:ring-[var(--accent-color)]"
                                    placeholder="例：〇〇の解き方がわからないので教えてください。"
                                    maxlength="2000"
                                >{{ old('content', $input['content'] ?? '') }}</textarea>
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
                                            class="absolute top-0 right-0 transform translate-x-1/2 -translate-y-1/2 bg-gray-700 text-[var(--white)] rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-500"
                                            type="button" aria-label="画像を削除">
                                        ×
                                    </button>
                                </div>
                            </div>
                            @error('image_url')
                                <p class="text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
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
                        <input type="hidden" name="category_id" value="{{ old('category_id', $input['category_id'] ?? '') }}">
                        @error('auto_repost_enabled')
                            <p class="text-red-500">{{ $message }}</p>
                        @enderror
                        <input type="hidden" name="mode" value="confirm">
                    </div>
                    <div class="flex justify-end space-x-4 mt-4 border-t border-[var(--texy-brown)] pt-3">
                        <a href="{{ route('question.cancel') }}"
                            class="px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 transition">キャンセル</a>
                        <button type="submit"
                            class="px-4 py-2 rounded-lg bg-[var(--button-bg)] text-[var(--white)] font-semibold hover:bg-[var(--button-hover)] transition">確認する</button>
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
        const modeInput = document.getElementById('modeInput');
        const form = document.getElementById('questionForm');
        const text = document.getElementById('text');
        const btn = document.getElementById('toggleBtn');
        const mode = "{{ $mode }}";

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
        };
        if (mode === 'confirm') {
            const computedStyle = window.getComputedStyle(text);
            const lineHeight = parseFloat(computedStyle.lineHeight);

            const lineCount = text.scrollHeight / lineHeight;
            const hasImage = {{ !empty($input['image_url']) ? 'true' : 'false' }};
            let limitLines;
            let styleHeight;
            if (hasImage) {
                limitLines = 3;
                styleHeight = 'max-h-20';
            } else {
                limitLines = 10;
                styleHeight = 'max-h-64';
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
        }
        document.addEventListener('DOMContentLoaded', function () {
            const buttons = document.querySelectorAll('.toggle-btn');//すべてのボタンを取得
            const groupBtn = document.getElementById('group-btn');
            const groups = document.querySelectorAll('.group');
            const radios = document.querySelectorAll('input[type="radio"][name="category_id"]');
            let lastChecked = null

            if (groupBtn && groups.length > 0) {
                groupBtn.addEventListener('click', function () {
                    groups.forEach(group => {
                        group.classList.toggle('hidden');
                    });
                    groupBtn.textContent = groups[0].classList.contains('hidden') ? '▲' : '▼';
                })

                radios.forEach(radio => {
                    radio.addEventListener('click', function (e) {
                        if (lastChecked === this) {
                            this.checked = false;
                            lastChecked = null;
                        } else {
                            lastChecked = this;
                        }
                    });
                });
            }

            if (buttons.length > 0) {
                //forEachで書くボタンにクリック効果をつける
                buttons.forEach(btn => {
                    btn.addEventListener('click', function () {
                        const targetId = this.getAttribute('data-target');//group-idを取得
                        const target = document.getElementById(targetId);//group-idの要素を取得

                        target.classList.toggle('hidden');
                        target.classList.toggle('flex');

                        // 矢印の向き切り替え
                        const arrow = this.querySelector('.arrow');//クリックした矢印ボタンを取得
                        arrow.textContent = target.classList.contains('hidden') ? '▲' : '▼';
                    });
                });
            }
        });
    </script>
</body>
</html>
