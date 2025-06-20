<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>計画追加</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
    </style>
</head>
<body class="bg-[var(--bg-green)] text-[var(--text-brown)] min-h-screen flex justify-center items-center">

    <div class="px-6 max-w-xl lg:max-w-2xl w-full">
        <div class="p-6 lg:p-8 bg-[var(--bg-light-gray)] rounded-xl shadow-md">
            <h1 class="text-xl font-semibold border-b border-[var(--texy-brown)] pb-3">新規投稿</h1>

            <form action="{{ route('plan.store') }}" method="POST">
                @csrf
                <div class="space-y-3 mt-2 border-b border-[var(--texy-brown)] pb-3">
                    <div>
                        <label for="name" class="block text-base font-semibold mb-2">
                            計画名
                        </label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            class="w-full px-4 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[var(--accent-color)]"
                            placeholder="30文字以内で入力してください"
                            maxlength="30"
                            required
                        >
                    </div>

                    <div>
                        <label for="description" class="block text-base font-semibold mb-2">
                            詳細
                        </label>
                        <textarea
                            id="description"
                            name="description"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[var(--accent-color)]"
                            placeholder="255文字以内で入力してください(任意)"
                            maxlength="255"
                            rows="4"></textarea>
                    </div>

                    <div>
                        <label for="target_hours" class="block text-base mb-2 font-semibold text-[var(--text-brown)]">
                            目標時間
                        </label>
                        <input type="number" id="target_hours" name="target_hours" class="w-44 p-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[var(--accent-color)]" min="0.5" max="100" step="0.5" placeholder="目標時間を入力" required>
                    </div>
                    <div>
                        <label for="priority" class="block text-base font-semibold text-[var(--text-brown)]">
                            優先度
                        </label>
                        <select name="priority" id="priority" required class="mt-2 w-44 p-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[var(--accent-color)]">
                            <option value="low" class="text-green-600">🟢 低</option>
                            <option value="medium" class="text-yellow-600">🟡 中</option>
                            <option value="high" class="text-red-600">🔴 高</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end space-x-4 mt-5">
                    <a href="{{ route('plan.index') }}"
                        class="px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 transition">キャンセル</a>
                    <button type="submit"
                        class="px-4 py-2 rounded-lg bg-[var(--button-bg)] text-[var(--white)] font-semibold hover:bg-[var(--button-hover)] transition">作成する</button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
