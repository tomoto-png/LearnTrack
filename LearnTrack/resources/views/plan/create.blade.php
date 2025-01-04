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
        }
    </style>
</head>
<body class="bg-[var(--bg-green)] text-[var(--text-brown)] min-h-screen flex">

    @include('components.sidebar')

    <div class="flex-1 p-6">
        <header class="mb-10">
            <h1 class="text-2xl font-semibold">新規追加</h1>
        </header>

        <!-- 新規学習計画フォーム -->
        <section class="bg-[var(--bg-light-gray)] p-6 rounded-lg shadow-lg">
            <form action="{{ route('plan.store') }}" method="POST">
                @csrf
                <div class="space-y-6">

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-800">計画名</label>
                        <input type="text" id="name" name="name" class="mt-2 w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[var(--accent-yellow)]" placeholder="計画名を入力" required>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-800">計画の詳細</label>
                        <textarea id="description" name="description" class="mt-2 w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[var(--accent-yellow)]" placeholder="計画の詳細を入力" rows="4"></textarea>
                    </div>


                    <div>
                        <label for="target_hours" class="block text-sm font-medium text-gray-800">目標時間（時間）</label>
                        <input type="number" id="target_hours" name="target_hours" class="mt-2 w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[var(--accent-yellow)]" min="0.5" max="100" step="0.5" placeholder="目標時間を入力" required>
                    </div>

                    <select name="priority" required class="mt-2 w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[var(--accent-yellow)]">
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                    </select>

                    <div class="mt-4 text-center text-sm flex justify-between">
                        <a class="bg-[var(--accent-yellow)] text-white px-6 py-2 rounded-lg font-semibold hover:bg-yellow-500 transition" href={{ route("plan.index") }}>
                            キャンセル
                        </a>

                        <button type="submit" class="bg-[var(--accent-yellow)] text-white px-6 py-2 rounded-lg font-semibold hover:bg-yellow-500 transition">
                            追加する
                        </button>
                    </div>
                </div>
            </form>
        </section>
    </div>

</body>
</html>
