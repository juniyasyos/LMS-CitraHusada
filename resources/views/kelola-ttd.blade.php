@extends('components.layout')
@section('title', 'Kelola Tanda Tangan')

@section('content')
<div class="flex h-screen overflow-hidden bg-slate-50 dark:bg-slate-950 transition-colors duration-300" 
    x-data="{ sidebarOpen: false, darkMode: localStorage.getItem('theme') === 'dark' }">
    
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
                    <div class="w-8 h-8 bg-gray-200 dark:bg-slate-700 rounded-full overflow-hidden border border-gray-100 dark:border-slate-800 flex items-center justify-center shrink-0">
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
                        <span class="text-gray-800 dark:text-white font-semibold">Kelola Tanda Tangan</span>
                    </li>
                </ol>
            </nav>

            <div class="grid grid-cols-1 xl:grid-cols-12 gap-8 items-start">
                
                {{-- Kolom Kiri: Form Informasi Direktur --}}
                <div class="xl:col-span-4 bg-white dark:bg-slate-900 rounded-xl border border-gray-100 dark:border-slate-800 shadow-sm p-6 lg:p-8">
                    <div class="mb-8">
                        <h3 class="text-sm font-bold text-gray-800 dark:text-white uppercase tracking-tight">Informasi Direktur</h3>
                        <p class="text-[10px] text-gray-400 mt-1">Detail ini akan dicetak di bagian bawah sertifikat.</p>
                    </div>

                    <form action="#" class="space-y-6">
                        {{-- Upload Tanda Tangan --}}
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase">File Tanda Tangan</label>
                            <div class="relative border-2 border-dashed border-gray-200 dark:border-slate-700 rounded-xl p-8 flex flex-col items-center justify-center bg-gray-50/50 dark:bg-slate-800/30 group hover:border-blue-400 transition-colors cursor-pointer">
                                <div class="w-12 h-12 bg-white dark:bg-slate-900 rounded-full shadow-sm flex items-center justify-center mb-3">
                                    <i class="fa-solid fa-upload text-gray-400 group-hover:text-blue-500 transition-colors"></i>
                                </div>
                                <p class="text-[11px] font-bold text-gray-600 dark:text-white">Unggah</p>
                                <input type="file" class="absolute inset-0 opacity-0 cursor-pointer">
                            </div>
                            <div class="flex justify-center">
                                <button type="button" class="text-[10px] font-bold text-red-500 hover:text-red-600 transition flex items-center gap-1">
                                    <i class="fa-solid fa-trash-can"></i> Hapus
                                </button>
                            </div>
                        </div>

                        {{-- Input Nama --}}
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase">Nama Lengkap</label>
                            <input type="text" value="Dr. Hj. Siti Nurhaliza" class="w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg px-3 py-2 text-xs text-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
                        </div>

                        {{-- Input Jabatan --}}
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase">Jabatan</label>
                            <input type="text" value="Direktur Utama" class="w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg px-3 py-2 text-xs text-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
                        </div>

                        {{-- Input NIK --}}
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase">NIK</label>
                            <input type="text" value="123456789" class="w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg px-3 py-2 text-xs text-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
                        </div>

                        <div class="pt-4">
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-lg text-xs font-bold shadow-lg shadow-blue-100 dark:shadow-none transition-all active:scale-95">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Kolom Kanan: Pratinjau Sertifikat --}}
                <div class="xl:col-span-8 bg-white dark:bg-slate-900 rounded-xl border border-gray-100 dark:border-slate-800 shadow-sm p-6 lg:p-10 min-h-[600px]">
                    <div class="mb-10">
                        <h3 class="text-sm font-bold text-gray-800 dark:text-white uppercase tracking-widest">
                            Pratinjau Penempatan Sertifikat
                        </h3>
                    </div>

                    <div class="flex items-center justify-center bg-gray-50 dark:bg-slate-950/50 rounded-2xl p-4 lg:p-12 border dark:border-slate-800">
                        <img 
                            src="{{ asset('images/sertif-depan.png') }}" 
                            class="w-full h-auto transition-all duration-500"
                            alt="Sertifikat"
                        >
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