@extends('components.layout')
@section('title', 'Sampah Pelatihan')

@section('content')
<div class="flex h-screen overflow-hidden bg-slate-50 dark:bg-slate-950 transition-colors duration-300" 
     x-data="{ sidebarOpen: false, darkMode: localStorage.getItem('theme') === 'dark' }">
    
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-slate-900 border-r dark:border-slate-800 transition-transform duration-300 lg:translate-x-0 lg:static lg:inset-0 shrink-0 overflow-y-auto">
        @include('components.nav-superadmin')
    </aside>

    <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition:enter="transition opacity-100 ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:leave="transition opacity-100 ease-in duration-200" x-transition:leave-end="opacity-0" class="fixed inset-0 z-40 bg-black/50 backdrop-blur-sm lg:hidden" x-cloak></div>

    <div class="flex-1 flex flex-col min-w-0 transition-colors duration-300">
        <header class="bg-white dark:bg-slate-900 border-b dark:border-slate-800 h-16 flex items-center justify-between px-4 lg:px-8 shrink-0 z-10 transition-colors duration-300">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = true" class="lg:hidden p-2 text-gray-500 dark:text-white"><i class="fa-solid fa-bars text-lg"></i></button>
                <h1 class="text-sm font-semibold text-gray-600 dark:text-white truncate">Sampah Pelatihan</h1>
            </div>
            <div class="flex items-center gap-3 lg:gap-4">
                @include('components.notif-superadmin')
                <div class="flex items-center gap-3 pl-2 lg:pl-4 border-l border-gray-100 dark:border-slate-800">
                    <div class="text-right hidden sm:block">
                        <p class="text-xs font-bold text-gray-800 dark:text-white leading-tight">Superadmin</p>
                        <p class="text-[10px] text-gray-500 dark:text-gray-300 font-medium italic">Utama</p>
                    </div>
                    <div class="w-8 h-8 bg-gray-200 dark:bg-slate-700 rounded-full overflow-hidden border border-gray-100 dark:border-slate-800 flex items-center justify-center">
                        <i class="fa-solid fa-user text-gray-500 dark:text-white text-xs"></i>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-4 lg:p-8 custom-scrollbar">
            {{-- Breadcrumb --}}
            <nav class="mb-6 text-[14px] font-medium">
                <ol class="flex items-center gap-2 text-gray-500 dark:text-gray-400">
                    <li><a href="{{ route('manajemen-pelatihan') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Manajemen Pelatihan</a></li>
                    <li class="flex items-center gap-2"><span class="text-gray-300 dark:text-gray-600"> > </span><span class="text-gray-800 dark:text-white font-semibold">Sampah</span></li>
                </ol>
            </nav>

            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-800 text-emerald-600 dark:text-emerald-400 text-xs font-bold rounded-xl flex items-center gap-3">
                    <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-100 dark:border-red-800 text-red-600 dark:text-red-400 text-xs font-bold rounded-xl flex items-center gap-3">
                    <i class="fa-solid fa-circle-xmark"></i> {{ session('error') }}
                </div>
            @endif

            <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 p-4 lg:p-6 mb-10 transition-colors duration-300">
                <div class="flex flex-col sm:flex-row justify-between items-start gap-4 mb-6">
                    <div>
                        <h2 class="text-lg font-bold text-gray-800 dark:text-white flex items-center gap-2"><i class="fa-solid fa-trash-can text-red-500 text-sm"></i> Pelatihan yang Dihapus</h2>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Materi yang dihapus akan otomatis terhapus permanen setelah 30 hari.</p>
                    </div>
                    <a href="{{ route('manajemen-pelatihan') }}" class="bg-slate-600 hover:bg-slate-700 text-white px-4 py-2.5 rounded-lg flex items-center gap-2 text-xs font-bold transition shadow-sm active:scale-95">
                        <i class="fa-solid fa-arrow-left text-xs"></i> Kembali
                    </a>
                </div>

                <form action="{{ route('pelatihan.trash') }}" method="GET" id="filterTrashForm" class="mb-6">
                    <div class="relative w-full sm:w-72">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 dark:text-white"><i class="fa-solid fa-magnifying-glass text-xs"></i></span>
                        <input type="text" name="search" id="searchTrashMateri" value="{{ $search }}" class="block w-full pl-9 pr-3 py-2.5 border border-gray-200 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 text-xs transition-all placeholder:dark:text-gray-400" placeholder="Cari pelatihan terhapus...">
                    </div>
                </form>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-6">
                    @forelse($trashedMateris as $materi)
                        <div class="border border-red-100 dark:border-red-900/30 rounded-xl overflow-hidden opacity-75 hover:opacity-100 transition-all group relative" x-data="{ menuOpen: false }">
                            <div class="h-40 bg-slate-100 dark:bg-slate-800 overflow-hidden relative">
                                @if($materi->image_path)
                                    <img src="{{ asset('storage/' . $materi->image_path) }}" alt="{{ $materi->judul }}" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <i class="fa-solid fa-folder text-gray-300 dark:text-slate-600 text-5xl"></i>
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-red-900/10"></div>
                            </div>
                            <div class="p-4">
                                <p class="text-xs font-bold text-gray-700 dark:text-white mb-1 truncate">{{ $materi->judul }}</p>
                                <p class="text-[10px] text-red-400 italic"><i class="fa-solid fa-clock mr-1"></i>Dihapus: {{ $materi->deleted_at->format('d M Y, H:i') }}</p>
                                @php $daysLeft = 30 - $materi->deleted_at->diffInDays(now()); @endphp
                                <p class="text-[9px] text-gray-400 mt-1">Otomatis terhapus dalam <span class="font-bold text-red-500">{{ max($daysLeft, 0) }} hari</span></p>
                            </div>
                            {{-- 3-dot menu --}}
                            <div class="absolute top-2 right-2">
                                <button @click="menuOpen = !menuOpen" class="w-7 h-7 rounded-full bg-white/80 dark:bg-slate-900/80 flex items-center justify-center text-gray-500 dark:text-white hover:bg-white transition text-xs"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                                <div x-show="menuOpen" @click.away="menuOpen = false" x-cloak class="absolute right-0 mt-1 w-44 bg-white dark:bg-slate-900 rounded-lg shadow-xl border border-gray-100 dark:border-slate-800 py-1 z-20">
                                    <form action="{{ route('pelatihan.restore', $materi->materi_id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-2 text-xs text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20"><i class="fa-solid fa-rotate-left mr-2"></i>Pulihkan</button>
                                    </form>
                                    <form action="{{ route('pelatihan.forceDestroy', $materi->materi_id) }}" method="POST" onsubmit="return confirm('HAPUS PERMANEN? Data tidak dapat dikembalikan!')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="w-full text-left px-4 py-2 text-xs text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20"><i class="fa-solid fa-fire mr-2"></i>Hapus Permanen</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-16 text-center">
                            <i class="fa-solid fa-recycle text-5xl text-gray-200 dark:text-slate-700 mb-4"></i>
                            <p class="text-sm text-gray-400 dark:text-gray-500 font-medium">Sampah kosong — tidak ada pelatihan yang dihapus.</p>
                        </div>
                    @endforelse
                </div>

                <div class="mt-8">{{ $trashedMateris->links() }}</div>
            </div>
        </main>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 10px; }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; }
</style>
@endsection
