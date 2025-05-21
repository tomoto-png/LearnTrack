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
        {{-- 質問 --}}
        <div class="space-y-2">
            <div class="flex items-center space-x-2">
                @if ($questionData->user->avatar)
                    <div class="w-10 h-10 rounded-full border border-[var(--accent-yellow)] shadow overflow-hidden">
                        <img class="w-full h-full object-cover"
                            src="{{ asset('storage/' . $questionData->user->avatar) }}"
                            alt="{{ $questionData->user->name }}のアバター">
                    </div>
                @else
                    <div class="w-6 h-6 rounded-full bg-[var(--bg-green)] flex items-center justify-center text-sm text-white shadow">
                        {{ strtoupper(substr($questionData->user->name, 0, 1)) }}
                    </div>
                @endif
                <p>{{ $questionData->user->name }}さん</p>
            </div>

            {{-- nl2brは/nがある時に改行する、コード内にURLがあるとpタグからaタグに変換する --}}
            <p>{!! nl2br(preg_replace('/(https?:\/\/[^\s]+)/i', '<a href="$1" target="_blank" rel="noopener noreferrer" class="text-blue-500 hover:text-blue-700 hover:underline">$1</a>', e($questionData->content))) !!}</p>

            @if (!@empty($questionData->image_url))
                <img src="{{ asset('storage/' . $questionData->image_url) }}" alt="" class="w-44 h-auto">
            @endif
            <button type="button" id="openModal">回答する</button>
        </div>
        {{-- 回答 --}}
        <div>

        </div>
        {{-- 回答モーダル --}}
        <div id="modalOverlay" class="bg-black fixed inset-0 bg-opacity-50 flex items-center justify-center z-50 hidden">
            <div id="modal" class="flex flex-col bg-white p-6 rounded-2xl max-w-2xl w-[90%] space-y-6 shadow-lg">
                <!-- タイトル -->
                <h1 class="text-2xl font-bold text-gray-800">回答モーダル</h1>

                <!-- テキストエリア -->
                <textarea
                    name="content"
                    rows="8"
                    class="w-full p-3 border border-gray-300 rounded-lg resize-none focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent text-sm"
                    placeholder="ここに回答を入力してください..."></textarea>

                <!-- ボタンエリア -->
                <div class="flex justify-end space-x-4">
                    <button
                        type="button"
                        id="closeModal"
                        class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition">
                        キャンセル
                    </button>
                    <button
                        type="button"
                        class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
                        回答する
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script>
        const open = document.getElementById('openModal');
        const close = document.getElementById('closeModal');
        const modal = document.getElementById('modalOverlay');
        const modalContent = document.getElementById('modal');
        function closeModal() {
            modal.classList.toggle('hidden');
        }
        open.addEventListener('click', function() {
            closeModal();
        });
        close.addEventListener('click', closeModal);
        modal.addEventListener('click', function(e) {
            console.log(modalContent);
            if (!modalContent.contains(e.target)) {//containsを使用することでmodalContentないの要素て一致するか判定
                closeModal();
            }
        });
    </script>
</body>
</html>
