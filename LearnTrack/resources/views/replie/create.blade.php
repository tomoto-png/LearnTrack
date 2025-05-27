<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>Document</title>
</head>
<body>
    <div>
        <form id="questionForm" action="{{ route('replie.store') }}" method="POST">
            @csrf
            @if ($mode === 'input')
                <div>
                    <h1>返信</h1>
                    <div>
                        {!! nl2br(preg_replace('/(https?:\/\/[^\s]+)/i', '<a href="$1" target="_blank" rel="noopener noreferrer" class="text-blue-500 hover:underline">$1</a>', e(($answer ?? $input)->content))) !!}
                    </div>
                    <input type="hidden" name="answer_id" id="answer_id" value="{{ $answer->id ?? $replieInput->answer_id}}">
                    <input type="hidden" name="mode" id="mode" value="confirm">
                    <div>
                        <textarea name="content" id="" cols="30" rows="10">{{ old('content', $replieInput->content ?? '')}}</textarea>
                        @error('content')
                            <p class="text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <a href="{{ route('question.show', ['id' => ($questionId)]) }}">キャンセル</a>
                        <button type="submit">確認</button>
                    </div>
                </div>
            @elseif ($mode === 'confirm')
                <div>
                    <h1>確認</h1>
                    <p>{{ $replieInput->content }}</p>
                    <input type="hidden" name="content" value="{{ $replieInput->content }}">
                    <input type="hidden" name="answer_id" value="{{ $replieInput->answer_id }}">
                    <input type="hidden" name="mode" id="modeInput" value="">
                    <div>
                        <button type="button" onclick="submitWithMode('edit')">修正する</button>
                        <button type="button" onclick="submitWithMode('post')">返信する</button>
                    </div>
                </div>
            @endif
        </form>
    </div>
    <script>
        const modeInput = document.getElementById('modeInput');
        const form = document.getElementById('questionForm');

        function submitWithMode(mode) {
            modeInput.value = mode;
            form.submit();
        }
    </script>
</body>
</html>
