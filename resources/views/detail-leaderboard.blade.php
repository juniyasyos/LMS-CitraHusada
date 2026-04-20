@extends('components.layout')
@section('title', 'Detail Leaderboard')

@section('content')
{{-- Menambahkan state sidebarOpen untuk kontrol menu mobile --}}
<div class="flex h-screen overflow-hidden bg-slate-50 dark:bg-slate-950 transition-colors duration-300" 
    x-data="{ sidebarOpen: false, darkMode: localStorage.getItem('theme') === 'dark' }">
    
    {{-- Sidebar Responsive Logic --}}
    <aside id="sidebar"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-slate-900 border-r dark:border-slate-800 transition-transform duration-300 lg:translate-x-0 lg:static lg:inset-0 flex-shrink-0 overflow-y-auto">
        @include('components.nav-superadmin', ['hideSideMenu' => true])
    </aside>

    {{-- Overlay untuk menutup sidebar di mobile --}}
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
                <h1 class="text-sm font-semibold text-gray-800 dark:text-white truncate">Detail Leaderboard</h1>
            </div>
            
            <div class="flex items-center gap-3 lg:gap-6">
                {{-- Search bar disembunyikan di mobile kecil --}}
                <div class="relative w-48 lg:w-64 hidden md:block">
                    <input type="text" placeholder="Cari data..." 
                        class="block w-full pl-4 pr-10 py-1.5 border border-gray-200 dark:border-slate-700 rounded-lg bg-gray-50 dark:bg-slate-800 text-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-xs transition-all placeholder:dark:text-gray-400">
                    <i class="fa-solid fa-magnifying-glass absolute right-3 top-2.5 text-gray-400 dark:text-white text-[10px]"></i>
                </div>

                <div class="flex items-center gap-2 lg:gap-4">
                    <div class="">
                        @include('components.notif-superadmin')
                    </div>
                    
                    <div class="flex items-center gap-3 pl-2 lg:pl-4 border-l border-gray-100 dark:border-slate-800">
                        <div class="text-right hidden sm:block">
                            <p class="text-xs font-bold text-gray-800 dark:text-white leading-tight">Superadmin</p>
                            <p class="text-[10px] text-gray-500 dark:text-gray-300 font-medium italic">Utama</p>
                        </div>
                        <div class="w-8 h-8 bg-gray-200 dark:bg-slate-700 rounded-full overflow-hidden border border-gray-100 dark:border-slate-800 flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-user text-gray-500 dark:text-white text-xs"></i>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-4 lg:p-8 custom-scrollbar">
            
            {{-- Breadcrumb (Hidden di mobile sangat kecil) --}}
            <nav class="mb-6 text-[13px] lg:text-[14px] font-medium hidden xs:block">
                <ol class="flex items-center gap-2 text-gray-500 dark:text-gray-400">
                    <li>
                        <a href="/beranda-superadmin" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                            Beranda
                        </a>
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="text-gray-300 dark:text-gray-600"> > </span>
                        <span class="text-gray-800 dark:text-white font-semibold">Detail Leaderboard</span>
                    </li>
                </ol>
            </nav>

            <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 overflow-hidden mb-6 transition-colors duration-300">
                <div class="p-4 lg:p-6 border-b dark:border-slate-800 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white dark:bg-slate-900 transition-colors">
                    <h3 class="font-bold text-gray-800 dark:text-white text-sm lg:text-base leading-tight">Leaderboard Jam Pembelajaran Per Unit Kerja</h3>
                    
                    {{-- Dropdown Filter --}}
                    <div class="relative inline-block text-left w-full sm:w-auto" x-data="{ open: false }">
                        <button @click="open = !open" 
                            class="flex items-center justify-between sm:justify-start gap-2 w-full sm:w-auto px-4 py-2 border border-gray-200 dark:border-slate-700 rounded-lg text-xs font-bold text-gray-500 dark:text-white hover:bg-gray-50 dark:hover:bg-slate-800 transition active:scale-95 bg-white dark:bg-slate-900">
                            <span>Terapkan Filter</span>
                            <i class="fa-solid fa-sliders text-[10px]"></i>
                        </button>

                        <div x-show="open" 
                            @click.away="open = false"
                            x-cloak
                            x-transition
                            class="absolute right-0 mt-2 w-48 bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 rounded-xl shadow-xl z-50 overflow-hidden">
                            
                            <div class="p-2 space-y-1">
                                <div class="px-3 py-1 mb-1">
                                    <p class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest">Status JPL</p>
                                </div>
                                <button class="flex items-center justify-between w-full px-3 py-2 text-[11px] font-bold text-gray-600 dark:text-gray-300 rounded-lg hover:bg-emerald-50 dark:hover:bg-emerald-900/30 hover:text-emerald-600 transition group">
                                    Terpenuhi
                                    <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                                </button>
                                <button class="flex items-center justify-between w-full px-3 py-2 text-[11px] font-bold text-gray-600 dark:text-gray-300 rounded-lg hover:bg-amber-50 dark:hover:bg-amber-900/30 hover:text-amber-600 transition group">
                                    Belum Terpenuhi
                                    <div class="w-2 h-2 rounded-full bg-amber-500"></div>
                                </button>
                            </div>
                            <div class="border-t border-gray-50 dark:border-slate-700 p-2">
                                <button class="w-full py-1 text-[10px] font-bold text-red-400 hover:text-red-600 transition">Reset Filter</button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Table Wrapper: Penting untuk scroll horizontal di mobile --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs min-w-[700px]">
                        <thead class="bg-gray-50/50 dark:bg-slate-800/50 text-gray-500 dark:text-white font-bold border-b dark:border-slate-800">
                            <tr>
                                <th class="py-4 px-6 uppercase tracking-wider">Nama Karyawan</th>
                                <th class="py-4 px-4 uppercase tracking-wider">Unit Kerja</th>
                                <th class="py-4 px-4 uppercase tracking-wider text-center">Pelatihan</th>
                                <th class="py-4 px-4 uppercase tracking-wider text-center">JPL</th>
                                <th class="py-4 px-6 uppercase tracking-wider text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-slate-800 text-gray-700 dark:text-white font-medium">
                            @php
                                $leaderboard = [
                                    ['name' => 'dr. Ahmad Subarjo', 'nip' => '198103101', 'unit' => 'Unit Gawat Darurat', 'count' => 4, 'jpl' => 20, 'status' => 'Terpenuhi'],
                                    ['name' => 'Siti Aminah, S.Kep', 'nip' => '198203201', 'unit' => 'Keperawatan Rawat Inap', 'count' => 1, 'jpl' => 6, 'status' => 'Belum Terpenuhi'],
                                    ['name' => 'Budi Santoso', 'nip' => '198303301', 'unit' => 'Administrasi & Keuangan', 'count' => 7, 'jpl' => 35, 'status' => 'Terpenuhi'],
                                    ['name' => 'dr. Lilis Handayani', 'nip' => '198403401', 'unit' => 'Poliklinik Spesialis', 'count' => 2, 'jpl' => 9, 'status' => 'Belum Terpenuhi'],
                                    ['name' => 'Rahmat Hidayat', 'nip' => '198503501', 'unit' => 'Farmasi', 'count' => 5, 'jpl' => 25, 'status' => 'Terpenuhi'],
                                ];
                            @endphp

                            @foreach($leaderboard as $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-800 transition border-b border-gray-50 dark:border-slate-800 last:border-0">
                                <td class="py-5 px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 bg-gray-200 dark:bg-slate-700 rounded flex items-center justify-center font-bold text-gray-500 dark:text-white text-[10px] uppercase transition-colors shrink-0">
                                            {{ substr($item['name'], 0, 1) }}{{ substr(strrchr($item['name'], " "), 1, 1) }}
                                        </div>
                                        <div class="truncate">
                                            <p class="font-bold text-gray-800 dark:text-white transition-colors truncate">{{ $item['name'] }}</p>
                                            <p class="text-[10px] text-gray-400 dark:text-gray-300">NIP: {{ $item['nip'] }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-5 px-4 text-gray-500 dark:text-gray-300 leading-tight transition-colors">{{ $item['unit'] }}</td>
                                <td class="py-5 px-4 text-center font-bold text-gray-800 dark:text-white transition-colors">{{ $item['count'] }}</td>
                                <td class="py-5 px-4 text-center font-bold text-gray-800 dark:text-white transition-colors">{{ $item['jpl'] }}</td>
                                <td class="py-5 px-6 text-center">
                                    <span class="px-3 py-1 rounded-full text-[9px] font-bold transition-colors {{ $item['status'] == 'Terpenuhi' ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-500 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-800' : 'bg-amber-50 dark:bg-amber-900/30 text-amber-500 dark:text-amber-400 border border-amber-100 dark:border-amber-800' }}">
                                        {{ strtoupper($item['status']) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Export Buttons - Responsive Stack --}}
            <div class="flex flex-col sm:flex-row justify-end gap-3 mb-8 transition-colors">
                <button class="flex items-center justify-center gap-2 px-5 py-2.5 border-2 border-blue-600 text-blue-600 bg-white dark:bg-slate-900 rounded-lg text-[11px] font-bold hover:bg-blue-50 dark:hover:bg-blue-900/20 transition active:scale-95">
                    <i class="fa-solid fa-file-excel"></i> Export Excel
                </button>
                <button class="flex items-center justify-center gap-2 px-5 py-2.5 border-2 border-red-500 text-red-500 bg-white dark:bg-slate-900 rounded-lg text-[11px] font-bold hover:bg-red-50 dark:hover:bg-red-900/20 transition active:scale-95">
                    <i class="fa-solid fa-file-pdf"></i> Export PDF
                </button>
            </div>

            {{-- Information Card --}}
            <div class="bg-white dark:bg-slate-900 rounded-xl border border-gray-100 dark:border-slate-800 p-6 flex items-start gap-4 shadow-sm mb-10 transition-colors">
                <div class="w-8 h-8 bg-gray-100 dark:bg-slate-800 rounded-full flex-shrink-0 flex items-center justify-center transition-colors">
                    <i class="fa-solid fa-check text-gray-600 dark:text-white text-xs"></i>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-gray-800 dark:text-white transition-colors uppercase tracking-tight">Informasi Status</h4>
                    <p class="text-xs text-gray-500 dark:text-gray-300 mt-1 leading-relaxed transition-colors">
                        Status <span class="font-bold text-gray-700 dark:text-white underline decoration-emerald-500/30 decoration-2 underline-offset-4">"Terpenuhi"</span> berarti karyawan telah mencapai ambang batas minimum Jam Pembelajaran (JPL) yang diwajibkan oleh administrasi rumah sakit.
                    </p>
                </div>
            </div>
        </main>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; height: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 10px; }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; }
    
    /* Utility untuk mobile kecil */
    @media (max-width: 480px) {
        .xs\:hidden { display: none; }
        .xs\:block { display: block; }
    }
    
    [x-cloak] { display: none !important; }
</style>
@endsection