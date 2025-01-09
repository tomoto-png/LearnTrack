<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>新規追加</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --bg-green: #b3cfad;
            --bg-light-gray: #e3e6d8;
            --text-brown: #9f9579;
            --accent-yellow: #d9ca79;
            --button-hover: #c5a02d;
        }
    </style>
</head>
<body class="bg-[var(--bg-green)] text-[var(--text-brown)] min-h-screen flex justify-center items-center">

    <div class="w-full max-w-4xl p-6 bg-[var(--bg-light-gray)] rounded-xl shadow-lg">
        <header class="mb-8 text-center">
            <h1 class="text-3xl font-bold text-[var(--text-brown)] border-b-2 border-[var(--accent-yellow)] pb-2">📋 新規追加</h1>
        </header>

        <form action="{{ route('plan.store') }}" method="POST">
            @csrf
            <div class="space-y-6">

                <div>
                    <label for="name" class="block text-lg font-medium text-[var(--text-brown)]">
                        計画名
                    </label>
                    <input type="text" id="name" name="name" class="w-full mt-2 px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[var(--accent-yellow)]" placeholder="計画名を入力" required>
                </div>

                <div>
                    <label for="description" class="block text-lg font-medium text-[var(--text-brown)]">
                        詳細
                    </label>
                    <textarea id="description" name="description" class="w-full mt-2 px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[var(--accent-yellow)]" placeholder="計画の詳細を入力" rows="4"></textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="target_hours" class="block text-lg font-medium text-[var(--text-brown)]">
                            目標時間
                        </label>
                        <input type="number" id="target_hours" name="target_hours" class="w-full mt-2 px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[var(--accent-yellow)]" min="0.5" max="100" step="0.5" placeholder="目標時間を入力" required>
                    </div>
                    <div>
                        <label for="priority" class="block text-lg font-medium text-[var(--text-brown)]">
                            優先度
                        </label>
                        <select name="priority" id="priority" required class="w-full mt-2 px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[var(--accent-yellow)]">
                            <option value="low" class="text-green-600">🟢 Low</option>
                            <option value="medium" class="text-yellow-600">🟡 Medium</option>
                            <option value="high" class="text-red-600">🔴 High</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-between items-center space-x-4">
                    <a href="{{ route('plan.index') }}" class="bg-[var(--accent-yellow)] text-white px-6 py-3 rounded-lg font-semibold hover:bg-[var(--button-hover)] transition-all w-full sm:w-auto text-center">
                        キャンセル
                    </a>
                    <button type="submit" class="bg-[var(--accent-yellow)] text-white px-6 py-3 rounded-lg font-semibold hover:bg-[var(--button-hover)] transition-all w-full sm:w-auto text-center">
                        追加する
                    </button>
                </div>

            </div>
        </form>
    </div>

</body>
</html>
