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
            <h1 class="text-xl font-semibold border-b border-[var(--texy-brown)] pb-3">学習計画編集</h1>

            <form action="{{ route('plan.update', ['id' => $plan->id]) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-3 mt-2 border-b border-[var(--texy-brown)] pb-3">
                    <div>
                        <label for="name" class="block text-base font-semibold mb-2">
                            計画名
                        </label>
                        <input type="text" id="name" name="name" class="w-full px-4 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[var(--button-bg)]" value="{{ old('name', $plan->name) }}" placeholder="計画名を入力" required>
                        @error('name')
                            <p class="text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="description" class="block text-base font-semibold mb-2">
                            詳細
                        </label>
                        <textarea id="description" name="description" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[var(--button-bg)]" placeholder="計画の詳細を入力（任意）" rows="4">{{ old('description', $plan->description) }}</textarea>
                        @error('description')
                            <p class="text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="target_hours" class="block text-base mb-2 font-semibold text-[var(--text-brown)]">
                                目標時間
                            </label>
                            <input type="number" id="target_hours" name="target_hours" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[var(--button-bg)]" value="{{old('description',$plan->target_hours) }}" min="0.5" max="100" step="0.5" placeholder="目標時間を入力" required>
                            @error('target_hours')
                                <p class="text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="priority" class="block text-base font-semibold text-[var(--text-brown)]">
                                優先度
                            </label>
                            <select name="priority" id="priority" required class="w-full mt-2 px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[var(--button-bg)]">
                                <option value="low" {{ old('priority', $plan->priority) == 'low' ? 'selected' : '' }}>🟢 低</option>
                                <option value="medium" {{ old('priority', $plan->priority) == 'medium' ? 'selected' : '' }}>🟡 中</option>
                                <option value="high" {{ old('priority', $plan->priority) == 'high' ? 'selected' : '' }}>🔴 高</option>
                            </select>
                            @error('priority')
                                <p class="text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="flex justify-end space-x-4 mt-5">
                    <a href="{{ route('plan.index') }}"
                        class="px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 transition">キャンセル</a>
                    <button type="submit"
                        class="px-4 py-2 rounded-lg bg-[var(--button-bg)] text-[var(--white)] font-semibold hover:bg-[var(--button-hover)] transition">変更する</button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>

