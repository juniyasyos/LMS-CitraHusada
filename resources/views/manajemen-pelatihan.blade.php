@extends('components.layout')
@section('title', 'Manajemen Media')

@section('content')
{{-- Menambahkan state sidebarOpen untuk kontrol menu mobile --}}
<div class="flex h-screen overflow-hidden bg-slate-50 dark:bg-slate-950 transition-colors duration-300" 
     x-data="{ openTambahFolder: false, sidebarOpen: false, darkMode: localStorage.getItem('theme') === 'dark' }">
    
    {{-- Sidebar Responsive Logic --}}
    <aside 
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-slate-900 border-r dark:border-slate-800 transition-transform duration-300 lg:translate-x-0 lg:static lg:inset-0 flex-shrink-0 overflow-y-auto">
        @include('components.nav-superadmin')
    </aside>

    {{-- Overlay untuk menutup sidebar mobile --}}
    <div x-show="sidebarOpen" 
         @click="sidebarOpen = false" 
         x-transition:enter="transition opacity-100 ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:leave="transition opacity-100 ease-in duration-200"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-40 bg-black/50 backdrop-blur-sm lg:hidden" x-cloak>
    </div>

    <div class="flex-1 flex flex-col min-w-0 transition-colors duration-300">
        
        {{-- Header Responsive --}}
        <header class="bg-white dark:bg-slate-900 border-b dark:border-slate-800 h-16 flex items-center justify-between px-4 lg:px-8 flex-shrink-0 z-10 transition-colors duration-300">
            <div class="flex items-center gap-4">
                {{-- Button Hamburger Mobile --}}
                <button @click="sidebarOpen = true" class="lg:hidden p-2 text-gray-500 dark:text-white">
                    <i class="fa-solid fa-bars text-lg"></i>
                </button>
                <h1 class="text-sm font-semibold text-gray-600 dark:text-white truncate">Manajemen Media</h1>
            </div>

            <div class="flex items-center gap-3 lg:gap-4">
                <div class="">
                    @include('components.notif-superadmin')
                </div>
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
            <div class="flex flex-col sm:flex-row justify-between items-start gap-4 mb-6">
                <div>
                    <h2 class="text-lg lg:text-xl font-bold text-gray-800 dark:text-white transition-colors">Manajemen Media</h2>
                    <p class="text-xs lg:text-sm text-gray-500 dark:text-gray-300 mt-1 transition-colors leading-relaxed">Kelola media pelatihan rumah sakit untuk penugasan yang tepat.</p>
                </div>
                <div class="flex items-center gap-3">
                    <button class="w-full sm:w-auto bg-red-600 hover:bg-red-700 text-white px-5 py-2.5 rounded-lg flex items-center justify-center gap-2 text-sm font-bold transition shadow-sm active:scale-95">
                        <i class="fa-solid fa-trash text-xs"></i>
                        Sampah
                    </button>

                    <button @click="openTambahFolder = true" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg flex items-center justify-center gap-2 text-sm font-bold transition shadow-sm active:scale-95">
                        <i class="fa-solid fa-plus text-xs"></i>
                        Tambah Folder
                    </button>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 p-4 lg:p-6 mb-10 transition-colors duration-300">
                
                <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-8">
                    <div class="w-full md:w-auto">
                        <div class="relative">
                            <select class="appearance-none w-full md:w-auto bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg px-4 py-2 pr-10 text-xs font-medium text-gray-600 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 cursor-pointer transition-all">
                                <option>Urutkan menurut</option>
                                <option>Terbaru</option>
                                <option>Terlama</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-400 dark:text-white">
                                <i class="fa-solid fa-chevron-down text-[10px]"></i>
                            </div>
                        </div>
                    </div>
                    <div class="relative w-full md:w-64">
                        <span class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 dark:text-white">
                            <i class="fa-solid fa-magnifying-glass text-xs"></i>
                        </span>
                        <input type="text" 
                            class="block w-full pl-4 pr-10 py-2 border border-gray-200 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 text-xs transition-all placeholder:dark:text-gray-400" 
                            placeholder="Cari data...">
                    </div>
                </div>

                {{-- Grid Folder Responsive --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-6">
                    @php
                        $folders = [
                            ['name' => 'Materi Keperawatan', 'time' => '1 bulan lalu', 'status' => 'active'],
                            ['name' => 'SOP Farmasi 2026', 'time' => '3 bulan lalu', 'status' => 'active'],
                            ['name' => 'Modul Gawat Darurat', 'time' => '1 bulan lalu', 'status' => 'active'],
                            ['name' => 'Kesehatan Lingkungan', 'time' => '1 bulan lalu', 'status' => 'active'],
                            ['name' => 'Radiologi Dasar', 'time' => '4 bulan lalu', 'status' => 'active'],
                            ['name' => 'Prosedur Administrasi', 'time' => '2 bulan lalu', 'status' => 'active'],
                        ];
                    @endphp

                    @foreach($folders as $folder)
                        @if($folder['status'] == 'active')
                            <a href="/daftar-materi-kuis" class="border border-gray-100 dark:border-slate-800 rounded-xl p-6 lg:p-8 flex flex-col items-center justify-center hover:bg-gray-50 dark:hover:bg-slate-800 transition-all group shadow-sm hover:shadow-md active:scale-95">
                                <div class="mb-4">
                                    <i class="fa-solid fa-folder text-amber-400 text-6xl lg:text-7xl group-hover:text-amber-500 transition-colors"></i>
                                </div>
                                <div class="text-center w-full">
                                    <p class="text-xs font-bold text-gray-700 dark:text-white mb-1 truncate px-2 tracking-tight transition-colors">{{ $folder['name'] }}</p>
                                    <p class="text-[10px] text-gray-400 dark:text-gray-400 font-medium italic transition-colors">{{ $folder['time'] }}</p>
                                </div>
                            </a>
                        @else
                            <div class="border border-gray-50 dark:border-slate-800/50 rounded-xl p-6 lg:p-8 flex flex-col items-center justify-center bg-gray-50/30 dark:bg-slate-800/20 opacity-60 cursor-not-allowed group shadow-sm">
                                <div class="mb-4">
                                    <i class="fa-solid fa-folder text-gray-400 dark:text-gray-600 text-6xl lg:text-7xl transition-colors"></i>
                                </div>
                                <div class="text-center w-full">
                                    <p class="text-xs font-bold text-gray-500 dark:text-gray-400 mb-1 truncate px-2 tracking-tight transition-colors">{{ $folder['name'] }}</p>
                                    <p class="text-[10px] text-gray-400 dark:text-gray-500 font-medium italic transition-colors">{{ $folder['time'] }}</p>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </main>
    </div>

    {{-- MODAL TAMBAH FOLDER (Responsif) --}}
    <div x-show="openTambahFolder" 
        class="fixed inset-0 z-[60] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        x-cloak>
        
        <div @click.away="openTambahFolder = false" 
            class="bg-white dark:bg-slate-900 w-full max-w-4xl rounded-2xl shadow-2xl overflow-hidden transition-colors duration-300">
            
            <div class="flex justify-between items-center px-6 lg:px-8 py-5 border-b border-gray-100 dark:border-slate-800">
                <h2 class="text-base font-bold text-gray-800 dark:text-white">Tambah Folder</h2>
                <button @click="openTambahFolder = false" class="text-gray-400 hover:text-gray-800 dark:hover:text-white transition">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <div class="p-6 lg:p-8 max-h-[80vh] overflow-y-auto custom-scrollbar">
                <form action="#" method="POST" class="space-y-5">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-white mb-2 uppercase tracking-tight">Nama Materi</label>
                        <input type="text" placeholder="Contoh: Protokol Keselamatan Radiasi" 
                            class="w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg h-12 px-4 focus:ring-2 focus:ring-blue-500/10 outline-none transition-all text-sm text-gray-700 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-white mb-2 uppercase tracking-tight">Deskripsi Pelatihan</label>
                        <textarea rows="4" placeholder="Keterangan singkat..." 
                                class="w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg p-4 focus:ring-2 focus:ring-blue-500/10 outline-none transition-all text-sm text-gray-700 dark:text-white resize-none"></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 dark:text-white mb-2 uppercase tracking-tight">JPL</label>
                            <input type="number" placeholder="3" class="w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg h-12 px-4 text-sm dark:text-white">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 dark:text-white mb-2 uppercase tracking-tight">Tanggal Pelaksanaan</label>
                            <input type="text" placeholder="3 April - 13 April 2026" class="w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg h-12 px-4 text-sm dark:text-white">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-white mb-2 uppercase tracking-tight">Keterangan</label>
                        <input type="text" placeholder="Keterangan..." 
                            class="w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg h-12 px-4 focus:ring-2 focus:ring-blue-500/10 outline-none transition-all text-sm text-gray-700 dark:text-white">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 dark:text-white mb-2">Unggah Thumbnail</label>
                            <div class="relative border-2 border-dashed border-gray-200 dark:border-slate-700 rounded-xl p-6 lg:p-8 flex flex-col items-center justify-center bg-slate-50/50 dark:bg-slate-800/50 hover:bg-slate-50 transition-colors cursor-pointer">
                                <i class="fa-solid fa-image text-blue-500 text-2xl mb-2"></i>
                                <p class="text-[11px] font-bold text-gray-700 dark:text-white">Upload File</p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 dark:text-white mb-2">Unit Kerja Terkait</label>
                            <div class="border border-gray-200 dark:border-slate-700 rounded-xl p-4 grid grid-cols-2 gap-2 bg-gray-50/30 dark:bg-slate-800/30">
                                @foreach(['Bedah', 'ICU', 'IGD', 'Radiologi'] as $unit)
                                <label class="flex items-center gap-2 cursor-pointer group">
                                    <input type="checkbox" checked class="w-4 h-4 rounded border-gray-300 dark:border-slate-600 text-blue-600">
                                    <span class="text-[11px] font-bold text-gray-600 dark:text-gray-300 group-hover:text-gray-900 transition-colors">{{ $unit }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4">
                        <button @click="openTambahFolder = false" type="button" class="w-full sm:w-auto px-8 py-2.5 border dark:border-slate-700 text-gray-500 dark:text-white font-bold rounded-lg hover:bg-gray-50 dark:hover:bg-slate-800 transition text-xs">Batal</button>
                        <button type="submit" class="w-full sm:w-auto px-8 py-2.5 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition text-xs">Simpan Pelatihan</button>
                    </div>
                </form>
            </div>
        </div>
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