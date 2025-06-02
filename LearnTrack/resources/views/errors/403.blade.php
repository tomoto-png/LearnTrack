<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>403 Forbidden</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.x/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="text-center">
        <h1 class="text-6xl font-bold text-red-600 mb-4">403</h1>
        <p class="text-xl mb-6">この操作を行う権限がありません。</p>
        <a href="{{ url()->previous() }}" class="text-blue-600 hover:underline">前のページへ戻る</a>
    </div>
</body>
</html>
