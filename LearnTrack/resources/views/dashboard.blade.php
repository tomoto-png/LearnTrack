<!DOCTYPE html>
<html lang="ja">
<head>
    <title>ダッシュボード</title>
</head>
<body>
    <h1>ようこそ、{{ Auth::user()->name }} さん！</h1>
    <form action="{{ route('logout') }}" method="POST" id="logout-form" style="display: none;">
        @csrf
        <button type="submit">ログアウト</button>
    </form>
    <a href="javascript:void(0);" onclick="document.getElementById('logout-form').submit();">ログアウト</a>
</body>
</html>