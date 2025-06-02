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
<body class="bg-[var(--bg-green)] text-[var(--text-brown)]">
    <div class="fixed inset-y-0 left-0 z-50 hidden lg:block">
        @include('components.sidebar')
    </div>
    <div class="flex-1 p-4 mt-4 sm:p-6 sm:mt-6 lg:ml-72">
        <header class="flex justify-between items-center mb-6">
            <h1 class="text-xl sm:text-2xl font-semibold">質問ひろば</h1>
            <button id="menuButton"
                class="fixed top-7 right-6 sm:top-10 sm:right-8 bg-[var(--accent-color)] text-[var(--white)] p-2 rounded-lg shadow-lg hover:bg-[var(--button-hover)] transition-transform transform hover:scale-110 lg:hidden z-50">
                <img id="menuIcon" src="{{ asset('images/menu_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.svg') }}" class="w-6 h-6">
            </button>
        </header>
        <div class="bg-[var(--bg-light-gray)] p-8 rounded-lg">
            <div class="flex">
                <a href="{{ route('question.create') }}"
                    class="flex items-center justify-center bg-[var(--button-bg)] text-[var(--white)] px-3 md:px-4 h-8 md:h-9 text-sm sm:text-base rounded-md hover:bg-[var(--button-hover)] transition-shadow shadow">
                    質問する
                </a>
            </div>
            <div class="mt-6">
                @foreach ($questionDatas as $data)
                    <a href="{{ route('question.show', $data->id) }}" class="block bg-[var(--white)] rounded-md p-3 my-3 space-y-2">
                        <p class="text-sm">{{ $data->user->name }}さん</p>
                        <p>{{ Str::limit($data->content, 155, '...') }}</p>
                        <div class="flex items-center text-sm gap-2">
                            <p>{{ $data->category->name }}</p>
                            <p>{{ $data->updated_at->format('Y/m/d H:i') }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</body>
</html>
