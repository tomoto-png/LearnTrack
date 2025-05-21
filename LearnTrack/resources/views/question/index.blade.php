<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>質問ひろば</title>
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
<body class="bg-[var(--bg-green)] text-[var(--text-brown)]">
    <div class="fixed inset-y-0 left-0 hidden lg:block">
        @include('components.sidebar')
    </div>
    <div class="flex-1 p-4 mt-4 sm:p-6 sm:mt-6 lg:ml-72">
        <header class="flex justify-between items-center mb-6">
            <h1 class="text-xl sm:text-2xl font-semibold">質問ひろば</h1>
            <button id="menuButton"
                class="fixed top-7 right-6 sm:top-10 sm:right-8 bg-[var(--accent-yellow)] text-white p-2 rounded-lg shadow-lg hover:bg-[var(--button-hover)] transition-transform transform hover:scale-110 lg:hidden z-[9999]">
                <img id="menuIcon" src="{{ asset('images/menu_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.svg') }}" class="w-6 h-6">
            </button>
        </header>
        <div>
            <a href="{{ route('question.create') }}">質問する</a>
        </div>
        <div class="bg-[var(--bg-light-gray)] p-6 rounded-lg">
            @foreach ($questionDatas as $data)
                <a href="{{ route('question.show', $data->id) }}" class="block bg-white rounded-md p-3 my-3 space-y-1">
                    <p class="text-xl">{{ $data->user->name }}</p>
                    <p>{{ Str::limit($data->content, 155, '...') }}</p>
                    <p>{{ $data->updated_at->format('Y/m/d H:i') }}</p>
                </a>
            @endforeach
        </div>
    </div>
</body>
</html>
