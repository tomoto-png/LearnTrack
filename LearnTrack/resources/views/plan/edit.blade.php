<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>編集</title>
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
<body class="bg-[var(--bg-green)] text-[var(--text-brown)] min-h-screen flex flex-col md:flex-row">

    <!-- サイドバー -->
    <div class="fixed inset-y-0 left-0 w-72 shadow-md md:block hidden z-20">
        @include('components.sidebar')
    </div>

    <!-- メインコンテンツ -->
    <div class="flex-1 p-6 mt-5 mb-5 ml-0 md:ml-72">
        <header class="mb-10">
            <h1 class="text-3xl font-semibold text-center text-gray-800">編集</h1>
        </header>

        <section class="bg-[var(--bg-light-gray)] shadow-lg rounded-xl p-8">
            <form action="{{ route('plan.update', ['id' => $plan->id]) }}" method="POST">
                @method('PUT')
                @csrf
                <div class="space-y-6">

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-800">計画名</label>
                        <input type="text" id="name" name="name" class="mt-2 w-full px-4 py-3 rounded-lg border focus:outline-none focus:ring-2 focus:ring-[var(--accent-yellow)]" value="{{ old('name', $plan->name) }}" required>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-800">計画の詳細</label>
                        <textarea id="description" name="description" class="mt-2 w-full px-4 py-3 rounded-lg border focus:outline-none focus:ring-2 focus:ring-[var(--accent-yellow)]" rows="4">{{ old('description', $plan->description) }}</textarea>
                    </div>

                    <div>
                        <label for="target_hours" class="block text-sm font-medium text-gray-800">目標時間（時間）</label>
                        <input type="number" id="target_hours" name="target_hours" class="mt-2 w-full px-4 py-3 rounded-lg border focus:outline-none focus:ring-2 focus:ring-[var(--accent-yellow)]" value="{{ old('target_hours', $plan->target_hours) }}" min="0.5" max="100" step="0.5" placeholder="目標時間を入力" required>
                    </div>

                    <div>
                        <label for="priority" class="block text-sm font-medium text-gray-800">優先度</label>
                        <select name="priority" id="priority" required class="mt-2 w-full px-4 py-3 rounded-lg border focus:outline-none focus:ring-2 focus:ring-[var(--accent-yellow)]">
                            <option value="low" {{ old('priority', $plan->priority) == 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ old('priority', $plan->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ old('priority', $plan->priority) == 'high' ? 'selected' : '' }}>High</option>
                        </select>
                    </div>

                    <div class="mt-6 text-center text-sm flex justify-between">
                        <a class="bg-[var(--accent-yellow)] hover:bg-[var(--button-hover)] transition-colors transform hover:translate-y-[-2px] text-white px-6 py-3 rounded-lg font-semibold" href="{{ route('plan.index') }}">
                            キャンセル
                        </a>

                        <button type="submit" class="bg-[var(--accent-yellow)] hover:bg-[var(--button-hover)] transition-colors transform hover:translate-y-[-2px] text-white px-6 py-3 rounded-lg font-semibold">
                            更新する
                        </button>
                    </div>
                </div>
            </form>
        </section>
    </div>

</body>
</html>
