@if ($paginator->hasPages())
    <div class="flex justify-between items-center mb-8 mt-4">
        {{-- Information Text --}}
        <p class="text-[10px] text-gray-400 font-medium uppercase tracking-wider">
            Menampilkan <span class="font-bold text-gray-600">{{ $paginator->firstItem() }}-{{ $paginator->lastItem() }}</span> dari {{ $paginator->total() }} pengguna
        </p>

        <div class="flex items-center gap-1">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="w-8 h-8 flex items-center justify-center border border-gray-100 rounded-lg text-gray-300 cursor-not-allowed">
                    <i class="fa-solid fa-chevron-left text-[10px]"></i>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="w-8 h-8 flex items-center justify-center border border-gray-200 rounded-lg text-gray-400 hover:bg-white transition shadow-sm">
                    <i class="fa-solid fa-chevron-left text-[10px]"></i>
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="w-8 h-8 flex items-center justify-center text-gray-400 text-[10px] font-bold">{{ $element }}</span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="w-8 h-8 flex items-center justify-center bg-blue-600 text-white rounded-lg text-[10px] font-bold shadow-md">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="w-8 h-8 flex items-center justify-center text-gray-400 text-[10px] font-bold hover:bg-white transition rounded-lg border border-transparent hover:border-gray-200">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="w-8 h-8 flex items-center justify-center border border-gray-200 rounded-lg text-gray-400 hover:bg-white transition shadow-sm">
                    <i class="fa-solid fa-chevron-right text-[10px]"></i>
                </a>
            @else
                <span class="w-8 h-8 flex items-center justify-center border border-gray-100 rounded-lg text-gray-300 cursor-not-allowed">
                    <i class="fa-solid fa-chevron-right text-[10px]"></i>
                </span>
            @endif
        </div>
    </div>
@endif
