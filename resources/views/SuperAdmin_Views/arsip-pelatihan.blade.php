@extends('components.layout')
@section('title', 'Arsip Pelatihan')

@section('content')
    <div class="flex h-screen overflow-hidden bg-slate-50 dark:bg-slate-950 transition-colors duration-300"
        x-data="{ sidebarOpen: false, darkMode: localStorage.getItem('theme') === 'dark' }">

        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-slate-900 border-r dark:border-slate-800 transition-transform duration-300 lg:translate-x-0 lg:static lg:inset-0 shrink-0 overflow-y-auto">
            @include('components.nav-superadmin')
        </aside>

        <div x-show="sidebarOpen" @click="sidebarOpen = false"
            x-transition:enter="transition opacity-100 ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:leave="transition opacity-100 ease-in duration-200" x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-40 bg-black/50 backdrop-blur-sm lg:hidden" x-cloak></div>

        <div class="flex-1 flex flex-col min-w-0 transition-colors duration-300">
            <header
                class="bg-white dark:bg-slate-900 border-b dark:border-slate-800 h-16 flex items-center justify-between px-4 lg:px-8 shrink-0 z-10 transition-colors duration-300">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = true" class="lg:hidden p-2 text-gray-500 dark:text-white"><i
                            class="fa-solid fa-bars text-lg"></i></button>
                    <h1 class="text-sm font-semibold text-gray-600 dark:text-white truncate">Arsip Pelatihan</h1>
                </div>
                <div class="flex items-center gap-3 lg:gap-4">
                    @include('components.notif-superadmin')
                    <div class="flex items-center gap-3 pl-2 lg:pl-4 border-l border-gray-100 dark:border-slate-800">
                        <div class="text-right hidden sm:block">
                            <p class="text-xs font-bold text-gray-800 dark:text-white leading-tight">Superadmin</p>
                            <p class="text-[10px] text-gray-500 dark:text-gray-300 font-medium italic">Utama</p>
                        </div>
                        <div
                            class="w-8 h-8 bg-gray-200 dark:bg-slate-700 rounded-full overflow-hidden border border-gray-100 dark:border-slate-800 flex items-center justify-center">
                            <i class="fa-solid fa-user text-gray-500 dark:text-white text-xs"></i>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-4 lg:p-8 custom-scrollbar">
                <div class="flex flex-col sm:flex-row justify-between items-start gap-4 mb-6">
                    <div>
                        <h2 class="text-lg lg:text-xl font-bold text-gray-800 dark:text-white transition-colors">Arsip
                            Pelatihan</h2>
                        <p class="text-xs lg:text-sm text-gray-500 dark:text-gray-300 mt-1 leading-relaxed">Daftar folder pelatihan yang telah dinonaktifkan atau diarsipkan.</p>
                    </div>
                    <div class="flex flex-wrap items-center gap-3">
                        <a href="{{ route('manajemen-pelatihan') }}"
                            class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2.5 rounded-lg flex items-center gap-2 text-xs font-bold transition shadow-sm active:scale-95">
                            <i class="fa-solid fa-arrow-left text-xs"></i> Kembali
                        </a>
                    </div>
                </div>

                @if(session('success'))
                    <div
                        class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-800 text-emerald-600 dark:text-emerald-400 text-xs font-bold rounded-xl flex items-center gap-3">
                        <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                    </div>
                @endif

                <div
                    class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 p-4 lg:p-6 mb-10 transition-colors duration-300">
                    <form action="{{ route('pelatihan.arsip') }}" method="GET" id="filterForm"
                        class="flex flex-col md:flex-row justify-end items-center gap-4 mb-8">
                        <div class="relative w-full md:w-64">
                            <span class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 dark:text-white"><i
                                    class="fa-solid fa-magnifying-glass text-xs"></i></span>
                            <input type="text" name="search" id="searchMateri" value="{{ $search }}"
                                class="block w-full pl-4 pr-10 py-2 border border-gray-200 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 text-xs transition-all placeholder:dark:text-gray-400"
                                placeholder="Cari di arsip...">
                        </div>
                    </form>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-6">
                        @forelse($materis as $materi)
                            <div class="relative group" x-data="{ menuOpen: false }">
                                <div class="block border border-gray-100 dark:border-slate-800 rounded-xl overflow-hidden bg-white dark:bg-slate-900 opacity-75 hover:opacity-100 transition-opacity">
                                    <div class="p-6 flex items-center justify-center bg-gray-100 dark:bg-slate-800">
                                        <i class="fa-solid fa-folder text-gray-400 text-6xl lg:text-7xl"></i>
                                    </div>

                                    <div class="p-4 text-center">
                                        <p class="text-xs font-bold text-gray-700 dark:text-white mb-1 truncate px-2">
                                            {{ $materi->judul }}
                                        </p>
                                        <p class="text-[10px] text-gray-400 dark:text-gray-500 font-medium italic">
                                            Diarsipkan: {{ $materi->updated_at->format('d M Y') }}
                                        </p>
                                    </div>
                                </div>

                                <div class="absolute top-2 right-2 z-20">
                                    <button @click.prevent="menuOpen = !menuOpen"
                                        class="w-7 h-7 rounded-full bg-white/90 dark:bg-slate-800/90 shadow-sm flex items-center justify-center text-gray-500 dark:text-white hover:bg-white dark:hover:bg-slate-700 transition text-xs border border-gray-100 dark:border-slate-700">
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </button>

                                    <div x-show="menuOpen" @click.away="menuOpen = false" x-cloak x-transition
                                        class="absolute right-0 mt-1 w-40 bg-white dark:bg-slate-900 rounded-lg shadow-xl border border-gray-100 dark:border-slate-800 py-1 z-30 overflow-hidden">

                                        <form action="{{ route('pelatihan.unarchive', $materi->materi_id) }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                class="w-full text-left px-4 py-2 text-xs text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors">
                                                <i class="fa-solid fa-rotate-left mr-2"></i>Pulihkan
                                            </button>
                                        </form>

                                        <form action="{{ route('pelatihan.destroyArchive', $materi->materi_id) }}" method="POST"
                                            onsubmit="return confirm('Hapus permanen dari arsip? Tindakan ini tidak dapat dibatalkan.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="w-full text-left px-4 py-2 text-xs text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                                <i class="fa-solid fa-trash-can mr-2"></i>Hapus Permanen
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full py-16 text-center">
                                <i class="fa-solid fa-box-open text-5xl text-gray-200 dark:text-slate-700 mb-4"></i>
                                <p class="text-sm text-gray-400 dark:text-gray-500 font-medium">Arsip kosong.</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-8">{{ $materis->links() }}</div>
                </div>
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let searchInput = document.getElementById('searchMateri');
            let timeout = null;
            if (searchInput) {
                searchInput.addEventListener('input', function () {
                    clearTimeout(timeout);
                    timeout = setTimeout(function () {
                        document.getElementById('filterForm').submit();
                    }, 1000);
                });
            }
        });
    </script>

    <style>
        [x-cloak] { display: none !important; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 10px; }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; }
    </style>
@endsection
