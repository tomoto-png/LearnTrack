<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>カテゴリー選択</title>
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
<body class="bg-[var(--bg-green)] text-[var(--text-brown)] min-h-screen flex items-center justify-center">
    <div class="px-6 max-w-xl lg:max-w-3xl  w-full">
        <div class="p-6 lg:p-8 bg-[var(--bg-light-gray)] rounded-xl shadow-md">
            <div class="flex items-center gap-2">
                <a href="{{ url()->previous()}}">
                    <span class="font-bold py-8 text-2xl">&larr;</span>
                </a>
                <p class="text-lg font-medium select-none">カテゴリーを選択</p>
            </div>
            @foreach ($categoryGroups as $group)
                <div class="p-4 border-b border-[var(--texy-brown)] pb-2 group">
                    {{-- グループの選択肢 --}}
                    <div class="flex justify-between items-center w-full text-base font-light focus:outline-none">
                        <a href="{{ route('search.index', ['group' => $group->id]) }}" class="hover:underline">
                            {{ $group->name }}
                        </a>
                    </div>

                    <!-- 選択肢 -->
                    <div class="category-options flex mt-4 flex-wrap gap-3">
                        @foreach ($group->categories as $category)
                            <a href="{{ route('search.index', ['category' => $category->id]) }}">
                                <div
                                    class="px-3 py-1 rounded-full bg-[var(--button-bg)] text-[var(--white)] hover:bg-[var(--button-hover)] border border-[var(--accent-color)] transition-colors duration-200 cursor-pointer">
                                    {{ $category->name }}
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</body>
</html>
