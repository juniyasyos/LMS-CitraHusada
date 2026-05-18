@extends('components.layout')
@section('title', 'Review Sertifikat Eksternal')

@section('content')
<div class="flex h-screen overflow-hidden bg-slate-50 dark:bg-slate-950 transition-colors duration-300" 
    x-data="{ 
        sidebarOpen: false, 
        darkMode: localStorage.getItem('theme') === 'dark',
        openModalVerifikasi: false 
    }">
    
    {{-- Sidebar --}}
    <aside id="sidebar"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-slate-900 border-r dark:border-slate-800 transition-transform duration-300 lg:translate-x-0 lg:static lg:inset-0 flex-shrink-0 overflow-y-auto">
        @include('components.nav-superadmin', ['hideSideMenu' => true])
    </aside>

    {{-- Overlay Mobile --}}
    <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-black/50 backdrop-blur-sm lg:hidden" x-cloak></div>

    <div class="flex-1 flex flex-col min-w-0 transition-colors duration-300">
        {{-- Header --}}
        <header class="bg-white dark:bg-slate-900 border-b dark:border-slate-800 h-16 flex items-center justify-between px-4 lg:px-8 flex-shrink-0 z-10">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = true" class="lg:hidden p-2 text-gray-500 dark:text-white hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition">
                    <i class="fa-solid fa-bars text-lg"></i>
                </button>
                <h1 class="text-sm font-semibold text-gray-600 dark:text-white truncate">Laporan & Monitoring</h1>
            </div>

            <div class="flex items-center gap-3 lg:gap-4">
                @include('components.notif-superadmin')
                <div class="flex items-center gap-3 pl-2 lg:pl-4 border-l border-gray-100 dark:border-slate-800">
                    <div class="text-right hidden sm:block">
                        <p class="text-xs font-bold text-gray-800 dark:text-white leading-tight">Admin</p>
                        <p class="text-[10px] text-gray-500 dark:text-gray-300 font-medium italic">Administrator</p>
                    </div>
                    <div class="w-8 h-8 bg-gray-200 dark:bg-slate-700 rounded-full flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-user text-gray-500 dark:text-white text-xs"></i>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-4 lg:p-8 custom-scrollbar">
            {{-- Breadcrumb --}}
            <nav class="mb-6 text-[14px] font-medium">
                <ol class="flex items-center gap-2 text-gray-500 dark:text-gray-400">
                    <li>
                        <a href="/admin/laporan-monitoring" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                            Laporan & Monitoring
                        </a>
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="text-gray-300 dark:text-gray-600"> > </span>
                        <span class="text-gray-800 dark:text-white font-semibold">Review Pelatihan</span>
                    </li>
                </ol>
            </nav>

            {{-- Main Card Container --}}
            <div class="bg-white dark:bg-slate-900 rounded-xl border border-gray-100 dark:border-slate-800 shadow-sm p-6 lg:p-12 mb-10 transition-colors duration-300 relative">
                
                {{-- Header Inside Card --}}
                <div class="mb-8">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white uppercase tracking-wider">Detail Sertifikat</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Periksa dokumen dan tentukan kelayakan JPL.</p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">
                    {{-- Kolom Kiri: Pratinjau Sertifikat --}}
                    <div class="lg:col-span-7 space-y-6">
                        <div class="relative group rounded-xl overflow-hidden border-2 border-gray-100 dark:border-slate-800 shadow-md bg-white dark:bg-slate-900 p-4 flex justify-center">
                            <img src="{{ asset('images/sertif-depan.png') }}" alt="Sertifikat Depan" class="w-full max-w-md object-contain rounded-xl transition-transform duration-500 group-hover:scale-[1.02]">
                            <div class="absolute inset-0 ring-1 ring-inset ring-black/5 rounded-xl pointer-events-none"></div>
                        </div>

                        <div class="relative group rounded-xl overflow-hidden border-2 border-gray-100 dark:border-slate-800 shadow-md bg-white dark:bg-slate-900 p-4 flex justify-center">
                            <img src="{{ asset('images/sertif-belakang.png') }}" alt="Sertifikat Belakang" class="w-full max-w-md object-contain rounded-xl transition-transform duration-500 group-hover:scale-[1.02]">
                            <div class="absolute inset-0 ring-1 ring-inset ring-black/5 rounded-xl pointer-events-none"></div>
                        </div>
                    </div>

                    {{-- Kolom Kanan: Form Penilaian --}}
                    <div class="lg:col-span-5 flex flex-col space-y-6">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-4 rounded-xl border-2 border-gray-100 dark:border-slate-800 text-center bg-white dark:bg-slate-900 shadow-sm transition-colors">
                                <p class="text-sm font-bold text-gray-800 dark:text-white">Internal JPL</p>
                                <p class="text-[10px] text-gray-400 mt-1 uppercase font-medium">18 Jam</p>
                            </div>
                            <div class="p-4 rounded-xl border-2 border-gray-100 dark:border-slate-800 text-center bg-white dark:bg-slate-900 shadow-sm transition-colors">
                                <p class="text-sm font-bold text-gray-800 dark:text-white">Eksternal JPL</p>
                                <p class="text-[10px] text-gray-400 mt-1 uppercase font-medium">8 Jam</p>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-gray-700 dark:text-white uppercase tracking-tight">Total Keseluruhan</label>
                            <div class="relative flex items-center justify-between bg-gray-50/50 dark:bg-slate-800/50 border-2 border-gray-100 dark:border-slate-700 rounded-xl px-4 h-[54px] overflow-hidden">
                                <div class="flex-1 mr-4">
                                    <div class="w-full bg-gray-200 dark:bg-slate-700 rounded-full h-1.5 overflow-hidden">
                                        <div class="bg-blue-600 h-full rounded-full transition-all duration-1000 ease-out" style="width: 75%"></div>
                                    </div>
                                </div>
                                <div class="shrink-0">
                                    <span class="text-xs font-bold text-blue-600 whitespace-nowrap">30 / 40 Jam Target</span>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-gray-700 dark:text-white uppercase tracking-tight">Komentar/Catatan Peninjauan</label>
                            <textarea rows="2" class="w-full bg-white dark:bg-slate-800 border-2 border-gray-100 dark:border-slate-700 rounded-xl px-4 py-3 text-xs text-gray-700 dark:text-white focus:border-blue-500 focus:outline-none transition-colors"></textarea>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-gray-700 dark:text-white uppercase tracking-tight">Konfirmasi Jam Pembelajaran (JPL)</label>
                            <textarea rows="1" class="w-full bg-white dark:bg-slate-800 border-2 border-gray-100 dark:border-slate-700 rounded-xl px-4 py-3 text-xs text-gray-700 dark:text-white focus:border-blue-500 focus:outline-none transition-colors"></textarea>
                        </div>

                        <div class="pt-5 flex justify-center"> 
                            <button @click="openModalVerifikasi = true" class="bg-blue-600 hover:bg-blue-700 text-white px-10 py-2.5 rounded-lg text-[12px] font-bold shadow-lg shadow-blue-200 dark:shadow-none transition-all active:scale-95">
                                Simpan Verifikasi
                            </button>
                        </div>
                    </div>
                </div>

                {{-- MODAL POP UP: VERIFIKASI KELAYAKAN --}}
                <div x-show="openModalVerifikasi" 
                    class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    x-cloak>
                    
                    <div @click.away="openModalVerifikasi = false" 
                        class="bg-white dark:bg-slate-900 w-full max-w-md rounded-2xl shadow-2xl overflow-hidden transition-all duration-300 border dark:border-slate-800"
                        x-transition:enter="transition ease-out duration-300 transform"
                        x-transition:enter-start="scale-95"
                        x-transition:enter-end="scale-100">
                        
                        {{-- Modal Content --}}
                        <div class="p-8 space-y-6">
                            <div class="space-y-2">
                                <h3 class="text-sm font-bold text-gray-800 dark:text-white">Verifikasi Kelayakan</h3>
                                <p class="text-[11px] text-gray-500 dark:text-gray-400 leading-relaxed">
                                    Tinjau seluruh isi dan nilai sertifikat sebelum memberikan keputusan verifikasi untuk kelayakan sertifikat.
                                </p>
                            </div>

                            {{-- Input Catatan --}}
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Catatan Hasil Peninjauan (opsional)</label>
                                <textarea rows="4" placeholder="Tuliskan catatan..." 
                                    class="w-full bg-slate-50 dark:bg-slate-800 border border-gray-100 dark:border-slate-700 rounded-xl p-4 text-xs dark:text-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all resize-none"></textarea>
                            </div>

                            {{-- Status Keputusan --}}
                            <div class="space-y-3">
                                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Status Keputusan</label>
                                <div class="grid grid-cols-2 gap-3">
                                    <button class="flex items-center justify-center gap-2 py-2.5 px-4 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg text-[10px] font-bold uppercase tracking-widest transition-all active:scale-95 shadow-sm">
                                        <i class="fa-regular fa-circle-check"></i> Setuju
                                    </button>
                                    <button class="flex items-center justify-center gap-2 py-2.5 px-4 bg-red-500 hover:bg-red-600 text-white rounded-lg text-[10px] font-bold uppercase tracking-widest transition-all active:scale-95 shadow-sm">
                                        <i class="fa-regular fa-circle-xmark"></i> Tolak
                                    </button>
                                </div>
                            </div>

                            {{-- Info Box --}}
                            <div class="bg-blue-50/50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800 p-4 rounded-xl flex gap-3">
                                <i class="fa-solid fa-circle-info text-blue-500 mt-0.5 text-xs"></i>
                                <p class="text-[10px] text-blue-600/80 dark:text-blue-400 leading-relaxed">
                                    Menyetujui akan secara otomatis mencatat JPL ke database sistem.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 10px; }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; }
    [x-cloak] { display: none !important; }
</style>
@endsection