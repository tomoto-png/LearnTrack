<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>タイマー設定</title>
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
            background-color: var(--accent-color);
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
<body class="bg-[var(--bg-green)] text-[var(--text-brown)] min-h-screen flex justify-center">

    <div class="w-full max-w-6xl p-4">
        <div class="flex">
            <button type="submit" form="timeroSettingsForm"
                    class="transition">
                <span class="font-bold text-2xl">&larr;</span>
            </button>
            <h1 class="text-2xl font-semibold py-8 ml-4">タイマー設定</h1>
        </div>
        <div class="flex items-center justify-center mt-12">

            <form id="timeroSettingsForm" action="{{ route('pomodoro.saveTimerSettings') }}" method="POST"
                    class="w-full max-w-3xl">
                @csrf
                <div class="flex items-center justify-between">
                    <label for="soundEffect" class="text-lg font-medium text-[var(--text-brown)]">効果音を有効にする</label>
                    <input type="checkbox" id="soundEffect" name="sound_effect" value="1"
                        class="toggle-checkbox hidden"
                        {{ intval(old('sound_effect', Auth::user()->timerSetting->sound_effect ?? 1)) === 1 ? 'checked' : '' }}>
                    <!-- スイッチの背景のデザイン -->
                    <label for="soundEffect" class="toggle-label block w-14 h-8 rounded-full transition bg-gray-300 relative cursor-pointer">
                        <!-- スイッチ内部の円 -->
                        <span class="absolute left-1 top-1 w-6 h-6 bg-[var(--white)] rounded-full transition-transform toggle-circle"></span>
                    </label>
                    @error('sound_effect')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </form>
        </div>
    </div>
</body>
</html>
