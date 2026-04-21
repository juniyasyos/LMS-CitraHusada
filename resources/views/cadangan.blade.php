@extends('components.layout')
@section('title', 'Cadangan')
@section('content')

{{-- Menambahkan state untuk dropdown pengaturan, modal reset, mode hapus, dan toggle status --}}
<div class="flex h-screen overflow-hidden bg-slate-50 dark:bg-slate-950 transition-colors duration-300" 
    x-data="{ 
        sidebarOpen: false, 
        darkMode: localStorage.getItem('theme') === 'dark',
        openPengaturan: false,
        openModalReset: false,
        isSelectionMode: false,
        backupActive: true 
    }">
    
    <aside id="sidebar"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-slate-900 border-r dark:border-slate-800 transition-transform duration-300 lg:translate-x-0 lg:static lg:inset-0 flex-shrink-0 overflow-y-auto">
        @include('components.nav-superadmin')
    </aside>

    <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-black/50 backdrop-blur-sm lg:hidden" x-cloak></div>

    <div class="flex-1 flex flex-col min-w-0 transition-colors duration-300">
        
        <header class="bg-white dark:bg-slate-900 border-b dark:border-slate-800 h-16 flex items-center justify-between px-4 lg:px-8 flex-shrink-0 z-10 transition-colors duration-300">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = true" class="lg:hidden p-2 text-gray-500 dark:text-white hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition">
                    <i class="fa-solid fa-bars text-lg"></i>
                </button>
                <h1 class="text-sm font-semibold text-gray-600 dark:text-white truncate">Cadangan</h1>
            </div>

            <div class="flex items-center gap-3 lg:gap-4">
                @include('components.notif-superadmin')
                <div class="flex items-center gap-3 pl-2 lg:pl-4 border-l border-gray-100 dark:border-slate-800">
                    <div class="text-right hidden sm:block">
                        <p class="text-xs font-bold text-gray-800 dark:text-white leading-tight">Superadmin</p>
                        <p class="text-[10px] text-gray-500 dark:text-gray-300 font-medium italic">Utama</p>
                    </div>
                    <div class="w-8 h-8 bg-gray-200 dark:bg-slate-700 rounded-full overflow-hidden border border-gray-100 dark:border-slate-800 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-user text-gray-500 dark:text-white text-xs"></i>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-4 lg:p-8 custom-scrollbar">
            <div class="flex flex-col sm:flex-row justify-between items-start gap-4 mb-6">
                <div>
                    <h2 class="text-lg lg:text-xl font-bold text-gray-800 dark:text-white transition-colors">Cadangan Sistem</h2>
                    <p class="text-xs lg:text-sm text-gray-500 dark:text-gray-300 mt-1 transition-colors leading-relaxed">Lakukan pencadangan basis data dan aset secara berkala.</p>
                </div>
                <div class="flex flex-wrap gap-2 w-full sm:w-auto relative">
                    <button class="flex-1 sm:flex-none bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg flex items-center justify-center gap-2 text-xs font-bold transition shadow-sm active:scale-95 shadow-blue-100 dark:shadow-none">
                        <i class="fa-solid fa-triangle-exclamation text-[10px]"></i>
                        Lakukan Pencadangan Sekarang
                    </button>
                    
                    {{-- Dropdown Pengaturan Cadangan --}}
                    <div class="relative flex-1 sm:flex-none">
                        <button @click="openPengaturan = !openPengaturan" class="w-full bg-white dark:bg-slate-800 hover:bg-gray-50 dark:hover:bg-slate-700 text-gray-600 dark:text-white border border-gray-200 dark:border-slate-700 px-5 py-2.5 rounded-lg flex items-center justify-center gap-2 text-xs font-bold transition shadow-sm active:scale-95">
                            <i class="fa-solid fa-gear text-[10px]"></i>
                            Pengaturan Cadangan
                        </button>

                        {{-- Kotak Menggantung (Dropdown) --}}
                        <div x-show="openPengaturan" @click.away="openPengaturan = false" x-cloak x-transition
                            class="absolute right-0 mt-2 w-72 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl shadow-xl z-50 p-5">
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-gray-700 dark:text-white uppercase tracking-tight">Pencadangan Otomatis</span>
                                    {{-- Toggle Switch --}}
                                    <button @click="backupActive = !backupActive" 
                                        :class="backupActive ? 'bg-blue-600' : 'bg-gray-300 dark:bg-slate-600'"
                                        class="relative inline-flex h-5 w-10 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out">
                                        <span :class="backupActive ? 'translate-x-5' : 'translate-x-0'"
                                            class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                                    </button>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase">Pilih Tanggal</label>
                                    <input type="date" class="w-full bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-lg p-2 text-xs dark:text-white">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase">Waktu</label>
                                    <input type="time" class="w-full bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-lg p-2 text-xs dark:text-white">
                                </div>
                                <button class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg text-xs font-bold transition">Atur Pencadangan</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 lg:gap-6 mb-8">
                <div class="bg-white dark:bg-slate-900 p-4 lg:p-5 rounded-xl border border-gray-100 dark:border-slate-800 shadow-sm transition-all hover:shadow-md">
                    <div class="flex items-center gap-2 lg:gap-3 text-gray-400 dark:text-gray-300 mb-2 transition-colors">
                        <i class="fa-solid fa-clock text-xs"></i>
                        <span class="text-[9px] lg:text-[11px] font-bold uppercase tracking-wider">Tugas Aktif</span>
                    </div>
                    <p class="text-lg lg:text-xl font-bold text-gray-800 dark:text-white transition-colors">0</p>
                </div>
                <div class="bg-white dark:bg-slate-900 p-4 lg:p-5 rounded-xl border border-gray-100 dark:border-slate-800 shadow-sm transition-all hover:shadow-md">
                    <div class="flex items-center gap-2 lg:gap-3 text-gray-400 dark:text-gray-300 mb-2 transition-colors">
                        <i class="fa-solid fa-circle-check text-xs"></i>
                        <span class="text-[9px] lg:text-[11px] font-bold uppercase tracking-wider">Berhasil</span>
                    </div>
                    <p class="text-lg lg:text-xl font-bold text-gray-800 dark:text-white transition-colors">0</p>
                </div>
                <div class="bg-white dark:bg-slate-900 p-4 lg:p-5 rounded-xl border border-gray-100 dark:border-slate-800 shadow-sm transition-all hover:shadow-md">
                    <div class="flex items-center gap-2 lg:gap-3 text-gray-400 dark:text-gray-300 mb-2 transition-colors">
                        <i class="fa-solid fa-circle-xmark text-xs"></i>
                        <span class="text-[9px] lg:text-[11px] font-bold uppercase tracking-wider">Gagal</span>
                    </div>
                    <p class="text-lg lg:text-xl font-bold text-gray-800 dark:text-white transition-colors">0</p>
                </div>
                <div class="bg-white dark:bg-slate-900 p-4 lg:p-5 rounded-xl border border-gray-100 dark:border-slate-800 shadow-sm transition-all hover:shadow-md">
                    <div class="flex items-center gap-2 lg:gap-3 text-gray-400 dark:text-gray-300 mb-2 transition-colors">
                        <i class="fa-solid fa-database text-xs"></i>
                        <span class="text-[9px] lg:text-[11px] font-bold uppercase tracking-wider">Penyimpanan</span>
                    </div>
                    <p class="text-sm font-bold text-gray-800 dark:text-white transition-colors truncate">Local Storage</p>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 min-h-[400px] flex flex-col mb-10 transition-colors duration-300 overflow-hidden">
                <div class="p-6 border-b dark:border-slate-800 flex flex-col md:flex-row justify-between items-center gap-4">
                    <h3 class="font-bold text-gray-800 dark:text-white transition-colors">Daftar Cadangan</h3>
                    <div class="relative w-full sm:w-64">
                        <input type="text" placeholder="Cari..." class="w-full pl-3 pr-8 py-2 border border-gray-200 dark:border-slate-700 rounded-lg text-xs bg-gray-50 dark:bg-slate-800 text-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all placeholder:dark:text-gray-400">
                        <i class="fa-solid fa-magnifying-glass absolute right-3 top-2.5 text-gray-400 dark:text-white text-xs"></i>
                    </div>
                </div>

                <div class="p-6 bg-gray-50/50 dark:bg-slate-800/50 flex flex-col md:flex-row justify-between items-center border-b dark:border-slate-800 gap-4 transition-colors">
                    <p class="text-xs text-gray-500 dark:text-gray-200 italic transition-colors text-center md:text-left">Pantau dan kelola tugas cadangan Anda secara real-time.</p>
                    <div class="flex flex-wrap justify-center gap-2">
                        {{-- Tombol Reset memunculkan Modal --}}
                        <button @click="openModalReset = true" class="flex items-center gap-2 px-3 py-1.5 border-2 border-red-400 text-red-500 bg-red-50 dark:bg-red-950/20 rounded-lg text-[10px] lg:text-[11px] font-bold hover:bg-red-100 transition active:scale-95">
                            <i class="fa-solid fa-rotate-left"></i> Reset
                        </button>
                        {{-- Tombol Bersihkan mengubah mode seleksi --}}
                        <button @click="isSelectionMode = !isSelectionMode" :class="isSelectionMode ? 'bg-amber-500 text-white' : 'border-2 border-amber-300 text-amber-600 bg-amber-50 dark:bg-amber-950/20'" class="flex items-center gap-2 px-3 py-1.5 rounded-lg text-[10px] lg:text-[11px] font-bold transition active:scale-95">
                            <i class="fa-solid fa-trash-can"></i> <span x-text="isSelectionMode ? 'Batal Pilih' : 'Bersihkan'"></span>
                        </button>
                        <button class="flex items-center gap-2 px-3 py-1.5 border-2 border-emerald-400 text-emerald-600 bg-emerald-50 dark:bg-emerald-950/20 rounded-lg text-[10px] lg:text-[11px] font-bold hover:bg-emerald-100 transition active:scale-95">
                            <i class="fa-solid fa-arrows-rotate"></i> Segarkan
                        </button>
                    </div>
                </div>

                <div class="flex-1 flex flex-col items-center justify-center p-8 lg:p-12 transition-colors relative">
                    {{-- Contoh tampilan Centang jika isSelectionMode aktif --}}
                    <div x-show="isSelectionMode" class="absolute top-4 left-6 text-xs text-gray-400 font-bold uppercase" x-transition>Pilih data untuk dihapus:</div>
                    
                    <div class="text-gray-200 dark:text-gray-700 mb-4 transition-colors">
                        <i class="fa-solid fa-folder-open text-7xl lg:text-8xl"></i>
                    </div>
                    <p class="text-xs lg:text-sm text-gray-400 dark:text-gray-400 font-medium tracking-wide transition-colors">Belum ada riwayat cadangan hari ini</p>
                </div>

                {{-- Footer tombol hapus saat mode seleksi --}}
                <div x-show="isSelectionMode" x-cloak x-transition class="p-4 bg-gray-100 dark:bg-slate-800 border-t dark:border-slate-700 flex justify-end">
                    <button class="bg-red-600 text-white px-6 py-2 rounded-lg text-xs font-bold shadow-lg shadow-red-200 dark:shadow-none hover:bg-red-700 active:scale-95 transition">
                        Hapus Data Terpilih
                    </button>
                </div>
            </div>
        </main>
    </div>

    {{-- Modal Konfirmasi Reset --}}
    <div x-show="openModalReset" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" x-cloak x-transition>
        <div class="bg-white dark:bg-slate-900 w-full max-w-sm rounded-2xl shadow-2xl p-6 text-center border dark:border-slate-800">
            <div class="w-16 h-16 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-triangle-exclamation text-2xl"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-2">Konfirmasi Reset</h3>
            <p class="text-xs text-gray-500 dark:text-gray-400 mb-6 leading-relaxed">Apakah Anda yakin ingin melakukan reset data cadangan? Tindakan ini tidak dapat dibatalkan.</p>
            <div class="flex gap-3">
                <button @click="openModalReset = false" class="flex-1 py-2 text-xs font-bold text-gray-500 hover:bg-gray-100 dark:hover:bg-slate-800 rounded-lg transition">Batal</button>
                <button class="flex-1 py-2 bg-red-600 text-white text-xs font-bold rounded-lg hover:bg-red-700 transition">Ya, Reset Data</button>
            </div>
        </div>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 10px; }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; }
    [x-cloak] { display: none !important; }
</style>
@endsection