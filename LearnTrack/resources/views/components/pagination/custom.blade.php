{{-- components/pagination/custom.blade.php --}}
@props(['paginator'])

@if ($paginator->hasPages())
    <div class="mt-4 flex justify-center">
        <nav class="inline-flex items-center space-x-2">
            {{-- Prev --}}
            @if ($paginator->onFirstPage())
                <span
                    class="px-3 py-1 text-base font-medium text-gray-400 bg-gray-200 border border-gray-300 rounded-full cursor-not-allowed">←</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                    class="px-3 py-1 text-base font-medium text-[var(--white)] bg-[var(--button-bg)] border border-[var(--button-hover)] rounded-full hover:bg-[var(--button-hover)] transition-transform transform hover:scale-105">←</a>
            @endif

            @php
                $currentPage = $paginator->currentPage();
                $lastPage = $paginator->lastPage();
                $start = max($currentPage - 2, 1);
                $end = min($currentPage + 2, $lastPage);
            @endphp

            @if ($start > 1)
                <a href="{{ $paginator->url(1) }}"
                    class="px-3 py-2 text-base font-medium bg-gray-200 border border-gray-300 rounded-full hover:bg-gray-400 hover:text-[var(--white)] transition-transform transform">1</a>
                @if ($start > 2)
                    <span class="px-2 text-base text-gray-500">...</span>
                @endif
            @endif

            @for ($i = $start; $i <= $end; $i++)
                @if ($i == $currentPage)
                    <span
                        class="px-3 py-2 text-base font-semibold text-[var(--white)] bg-[var(--button-bg)] border border-gray-300 rounded-full shadow-inner">{{ $i }}</span>
                @else
                    <a href="{{ $paginator->url($i) }}"
                        class="px-3 py-2 text-base font-medium bg-gray-200 border border-gray-300 rounded-full hover:bg-gray-400 hover:text-[var(--white)] transition-transform transform">{{ $i }}</a>
                @endif
            @endfor

            @if ($end < $lastPage)
                @if ($end < $lastPage - 1)
                    <span class="px-2 text-base text-gray-500">...</span>
                @endif
                <a href="{{ $paginator->url($lastPage) }}"
                    class="px-3 py-2 text-base font-medium bg-gray-200 border border-gray-300 rounded-full hover:bg-[var(--button-hover)] hover:text-[var(--white)] transition-transform transform">{{ $lastPage }}</a>
            @endif

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                    class="px-3 py-1 text-base font-medium text-[var(--white)] bg-[var(--button-bg)] border border-[var(--button-bg)] rounded-full hover:bg-[var(--button-hover)] transition-transform transform hover:scale-105">→</a>
            @else
                <span
                    class="px-3 py-1 text-base font-medium text-gray-400 bg-gray-200 border border-gray-300 rounded-full cursor-not-allowed">→</span>
            @endif
        </nav>
    </div>
@endif
