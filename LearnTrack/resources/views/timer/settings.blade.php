<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>タイマー設定</title>
    <style>
        :root {
            --bg-green: #b3cfad;
            --bg-light-gray: #e3e6d8;
            --text-brown: #9f9579;
            --accent-yellow: #d9ca79;
        }
    </style>
</head>
<body class="bg-[var(--bg-green)] min-h-screen flex justify-center">

    <div class="w-full max-w-6xl p-4">
        <div class="flex">
            <button type="submit" form="timeroSettingsForm"
                    class="text-[var(--text-brown)] transition">
                <span class="font-bold text-2xl">&larr;</span>
            </button>
            <h1 class="text-2xl font-semibold text-[var(--text-brown)] py-6 ml-4">タイマー設定</h1>
        </div>
        <div class="flex items-center justify-center mt-12">

            <form id="timeroSettingsForm" action="{{ route('pomodoro.saveTimerSettings') }}" method="POST"
                    class="w-full max-w-3xl p-10">
                @csrf
                <div class="flex items-center justify-between">
                    <label for="soundEffect" class="text-lg font-medium text-[var(--text-brown)]">効果音を有効にする</label>
                    <input type="checkbox" id="soundEffect" name="sound_effect" value="1"
                        class="toggle-checkbox hidden"
                        {{ intval(old('sound_effect', Auth::user()->timerSetting->sound_effect ?? 1)) === 1 ? 'checked' : '' }}>
                    <!-- スイッチの背景のデザイン -->
                    <label for="soundEffect" class="toggle-label block w-14 h-8 rounded-full transition bg-gray-300 relative cursor-pointer">
                        <!-- スイッチ内部の円 -->
                        <span class="absolute left-1 top-1 w-6 h-6 bg-white rounded-full transition-transform toggle-circle"></span>
                    </label>
                    @error('sound_effect')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </form>
        </div>
    </div>
    <style>
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
</body>
</html>
