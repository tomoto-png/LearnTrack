<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>プロフィール編集</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* カラーパレット */
        :root {
            --bg-green: #b3cfad;
            --bg-light-gray: #e3e6d8;
            --text-brown: #9f9579;
            --accent-yellow: #d9ca79;
        }
    </style>
</head>
<body class="bg-[var(--bg-green)] text-[var(--text-brown)] min-h-screen flex items-center justify-center">
    <div class="container mx-auto p-6 bg-[var(--bg-light-gray)] shadow-lg rounded-lg max-w-lg">
        <h1 class="text-3xl font-semibold mb-6 text-center">プロフィール編集</h1>

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-lg font-medium mb-2">名前</label>
                <input type="text"
                       name="name"
                       id="name"
                       value="{{ old('name', $user->name) }}"
                       required
                       class="w-full px-4 py-2 border border-[var(--text-brown)] rounded-lg focus:ring-2 focus:ring-[var(--accent-yellow)] focus:outline-none">
            </div>

            <div>
                <label for="bio" class="block text-lg font-medium mb-2">自己紹介</label>
                <textarea name="bio"
                          id="bio"
                          required
                          rows="4"
                          class="w-full px-4 py-2 border border-[var(--text-brown)] rounded-lg focus:ring-2 focus:ring-[var(--accent-yellow)] focus:outline-none">{{ old('bio', $user->bio) }}</textarea>
            </div>

            <div>
                <label for="avatar" class="block text-lg font-medium mb-2">画像</label>
                <input type="file"
                       name="avatar"
                       id="avatar"
                       class="w-full px-4 py-2 border border-[var(--text-brown)] rounded-lg bg-white focus:ring-2 focus:ring-[var(--accent-yellow)] focus:outline-none">
            </div>

            <button type="submit"
                    class="w-full bg-[var(--accent-yellow)] text-[var(--text-brown)] py-2 rounded-lg font-semibold hover:bg-[var(--text-brown)] hover:text-white transition focus:outline-none">
                更新
            </button>
        </form>
    </div>
</body>
</html>