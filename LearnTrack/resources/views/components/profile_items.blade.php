@forelse($datas as $data)
    @if ($filter === 'question')
        <a href="{{ route('question.show', $data->id) }}"
            class="block bg-white rounded-lg shadow-sm p-4 mb-4 hover:shadow-md transition">
            <p class="mt-1 text-sm sm:text-base text-[var(--text-main)]">{{ Str::limit($data->content, 155, '...') }}</p>
            <div class="flex items-center text-xs sm:text-sm text-gray-500 gap-3 mt-2">
                <span class="px-2 py-0.5 bg-gray-100 rounded">{{ $data->category->name }}</span>
                <span>{{ $data->updated_at->format('Y/m/d H:i') }}</span>
            </div>
        </a>
    @else
        <a href="{{ route('question.show', $data->question->id) }}"
            class="block bg-white rounded-lg shadow-sm p-4 mb-4 hover:shadow-md transition">
            <p class="text-sm text-gray-500">{{ $data->question->user->name }} さん</p>
            <p class="mt-1 text-sm sm:text-base text-[var(--text-main)]">{{ Str::limit($data->question->content, 155, '...') }}</p>
            <div class="pl-2">
                <div class="pl-1 border-l-2 border-[var(--text-brown)]">
                    <p class="mt-1 text-sm sm:text-base text-[var(--text-main)]">{{ Str::limit($data->content, 155, '...') }}</p>
                    <span>{{ $data->updated_at->format('Y/m/d H:i') }}</span>
                </div>
            </div>
        </a>
    @endif
@empty
    <div class="text-center text-lg">
        @if ($filter === 'question')
            <p>該当する質問はありません。</p>
        @else
            <p>該当する回答はありません。</p>
        @endif
    </div>
@endforelse
