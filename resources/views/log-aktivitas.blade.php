@extends('components.layout')
@section('title', 'Log Aktivitas')
@section('content')

{{-- Menambahkan state sidebarOpen untuk kontrol menu mobile --}}
<div class="flex h-screen overflow-hidden bg-slate-50 dark:bg-slate-950 transition-colors duration-300" 
     x-data="{ sidebarOpen: false, darkMode: localStorage.getItem('theme') === 'dark' }">
    
    {{-- Sidebar Responsive Logic --}}
    <aside id="sidebar"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-slate-900 border-r dark:border-slate-800 transition-transform duration-300 lg:translate-x-0 lg:static lg:inset-0 flex-shrink-0 overflow-y-auto">
        @include('components.nav-superadmin')
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
                <h1 class="text-sm font-semibold text-gray-600 dark:text-white truncate">Log Aktivitas</h1>
            </div>

            <div class="flex items-center gap-3 lg:gap-4">
                <div class="">
                    @include('components.notif-superadmin')
                </div>
                <div class="flex items-center gap-3 pl-2 lg:pl-4 border-l border-gray-100 dark:border-slate-800">
                    <div class="text-right hidden sm:block">
                        <p class="text-xs font-bold text-gray-800 dark:text-white leading-tight transition-colors">Superadmin</p>
                        <p class="text-[10px] text-gray-500 dark:text-gray-300 font-medium transition-colors">Administrator Utama</p>
                    </div>
                    <div class="w-8 h-8 bg-gray-200 dark:bg-slate-700 rounded-full overflow-hidden border border-gray-100 dark:border-slate-800 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-user text-gray-500 dark:text-white text-xs"></i>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-4 lg:p-8 custom-scrollbar">
            {{-- Main Audit Card --}}
            <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 p-4 lg:p-8 mb-6 transition-all hover:shadow-md">
                
                <div class="mb-6">
                    <div class="flex items-center gap-2 text-blue-600 dark:text-blue-400 mb-1">
                        <i class="fa-solid fa-shield-halved text-xs"></i>
                        <span class="text-[10px] font-bold uppercase tracking-wider">Keamanan & Audit</span>
                    </div>
                    <h2 class="text-lg lg:text-xl font-bold text-gray-800 dark:text-white tracking-tight transition-colors">Audit Log Real-time</h2>
                    <p class="text-xs lg:text-sm text-gray-500 dark:text-gray-200 mt-1 max-w-4xl leading-relaxed transition-colors italic">
                        Pantau setiap tindakan administrator dan sistem untuk keperluan audit keamanan dan pelacakan perubahan data operasional.
                    </p>
                </div>

                {{-- Toolbar Responsive --}}
                <div class="flex flex-col lg:flex-row lg:items-center gap-3 mb-8">
                    <div class="relative flex-1">
                        <input type="text" 
                            class="w-full pl-4 pr-10 py-2 border border-gray-200 dark:border-slate-700 rounded-lg bg-gray-50 dark:bg-slate-800 text-xs text-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all placeholder:dark:text-gray-400" 
                            placeholder="Cari aksi, detail, atau IP...">
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <button class="flex-1 lg:flex-none flex items-center justify-center gap-2 px-3 py-2 border border-gray-200 dark:border-slate-700 rounded-lg text-[10px] lg:text-[11px] font-bold text-gray-600 dark:text-white hover:bg-gray-50 dark:hover:bg-slate-800 transition active:scale-95">
                            <i class="fa-solid fa-filter text-blue-500 dark:text-blue-400"></i> Filter
                        </button>
                        <button class="flex-1 lg:flex-none flex items-center justify-center gap-2 px-3 py-2 border border-gray-200 dark:border-slate-700 rounded-lg text-[10px] lg:text-[11px] font-bold text-gray-600 dark:text-white hover:bg-gray-50 dark:hover:bg-slate-800 transition active:scale-95">
                            <i class="fa-solid fa-calendar-days text-blue-500 dark:text-blue-400"></i> Tanggal
                        </button>
                        <button class="flex-1 lg:flex-none flex items-center justify-center gap-2 px-3 py-2 border border-gray-200 dark:border-slate-700 rounded-lg text-[10px] lg:text-[11px] font-bold text-gray-600 dark:text-white hover:bg-gray-50 dark:hover:bg-slate-800 transition active:scale-95">
                            <i class="fa-solid fa-download text-blue-500 dark:text-blue-400"></i> Unduh
                        </button>
                    </div>
                </div>

                {{-- Table Wrapper: overflow-x-auto agar bisa di-scroll di mobile --}}
                <div class="overflow-x-auto border border-gray-100 dark:border-slate-800 rounded-xl mb-6 transition-colors">
                    <table class="w-full text-left text-xs min-w-[800px]">
                        <thead class="bg-gray-50/80 dark:bg-slate-800/50 text-gray-500 dark:text-white font-bold border-b dark:border-slate-800 transition-colors">
                            <tr>
                                <th class="py-4 px-6 uppercase tracking-widest">Tanggal & Waktu</th>
                                <th class="py-4 px-4 uppercase tracking-widest">Nama Pengguna</th>
                                <th class="py-4 px-4 uppercase tracking-widest">Aksi</th>
                                <th class="py-4 px-4 uppercase tracking-widest">Detail Aktivitas</th>
                                <th class="py-4 px-6 text-right uppercase tracking-widest">IP Address</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-slate-800 text-gray-700 dark:text-white transition-colors">
                            @php
                                $logs = [
                                    ['time' => '2023-11-24 14:22:10', 'user' => 'Budi Santoso', 'role' => 'Superadmin • ADM-001', 'action' => 'Tambah', 'icon' => 'fa-user-plus', 'color' => 'bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 border-blue-100 dark:border-blue-900/50', 'detail' => 'Menambahkan pengguna baru: Siti Aminah (NIP: 19880211)', 'ip' => '192.168.1.105'],
                                    ['time' => '2023-11-24 13:45:05', 'user' => 'dr. Linda Wijaya', 'role' => 'Admin Unit • ADM-042', 'action' => 'Ubah', 'icon' => 'fa-pen-to-square', 'color' => 'bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 border-amber-100 dark:border-amber-900/50', 'detail' => 'Mengubah deskripsi pada modul "Prosedur Sterilisasi Alat"', 'ip' => '192.168.1.112'],
                                    ['time' => '2023-11-24 12:30:11', 'user' => 'Sistem', 'role' => 'System • SYS-AUTO', 'action' => 'Ekspor', 'icon' => 'fa-file-export', 'color' => 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 border-indigo-100 dark:border-indigo-900/50', 'detail' => 'Auto-generate laporan bulanan Unit Bedah (PDF)', 'ip' => '127.0.0.1'],
                                    ['time' => '2023-11-24 11:15:44', 'user' => 'Budi Santoso', 'role' => 'Superadmin • ADM-001', 'action' => 'Hapus', 'icon' => 'fa-trash-can', 'color' => 'bg-red-500 text-white border-red-500 dark:bg-red-900/50 dark:text-red-200 dark:border-red-900', 'detail' => 'Menghapus kategori "Arsip Lama 2019"', 'ip' => '192.168.1.105'],
                                    ['time' => '2023-11-24 09:00:01', 'user' => 'Agus Pratama', 'role' => 'Admin Unit • ADM-015', 'action' => 'Masuk', 'icon' => 'fa-right-to-bracket', 'color' => 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 border-emerald-100 dark:border-emerald-900/50', 'detail' => 'Sesi dimulai untuk Unit Farmasi', 'ip' => '10.20.30.45'],
                                ];
                            @endphp

                            @foreach($logs as $log)
                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-800 transition duration-150">
                                <td class="py-5 px-6 leading-relaxed whitespace-nowrap">
                                    <p class="font-bold text-gray-800 dark:text-white transition-colors">{{ explode(' ', $log['time'])[0] }}</p>
                                    <p class="text-gray-400 dark:text-gray-300 font-mono text-[10px] transition-colors">{{ explode(' ', $log['time'])[1] }}</p>
                                </td>
                                <td class="py-5 px-4 leading-tight min-w-[150px]">
                                    <p class="font-bold text-gray-800 dark:text-white transition-colors">{{ $log['user'] }}</p>
                                    <p class="text-[10px] text-gray-400 dark:text-gray-300 italic transition-colors truncate">{{ $log['role'] }}</p>
                                </td>
                                <td class="py-5 px-4">
                                    <div class="inline-flex items-center gap-2 px-3 py-1.5 border rounded-lg font-bold text-[9px] uppercase tracking-tighter transition-colors {{ $log['color'] }}">
                                        <i class="fa-solid {{ $log['icon'] }} text-[10px]"></i>
                                        {{ $log['action'] }}
                                    </div>
                                </td>
                                <td class="py-5 px-4 text-gray-500 dark:text-gray-200 max-w-xs leading-relaxed italic transition-colors">
                                    {{ $log['detail'] }}
                                </td>
                                <td class="py-5 px-6 text-right text-gray-400 dark:text-gray-300 font-mono font-medium transition-colors">
                                    {{ $log['ip'] }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination Responsive --}}
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4 transition-colors">
                    <p class="text-[10px] text-gray-400 dark:text-white font-medium italic order-2 sm:order-1">Menampilkan 1-5 dari 124 log</p>
                    <div class="flex items-center gap-1 order-1 sm:order-2">
                        <button class="w-8 h-8 flex items-center justify-center border border-gray-200 dark:border-slate-800 rounded-lg text-gray-400 dark:text-white hover:bg-white dark:hover:bg-slate-800 transition"><i class="fa-solid fa-chevron-left text-[10px]"></i></button>
                        <button class="w-8 h-8 flex items-center justify-center bg-blue-600 text-white rounded-lg text-[10px] font-bold shadow-md shadow-blue-100 dark:shadow-none transition-transform active:scale-90">1</button>
                        <button class="w-8 h-8 flex items-center justify-center text-gray-400 dark:text-white text-[10px] font-bold hover:bg-white dark:hover:bg-slate-800 rounded-lg transition hidden xs:flex">2</button>
                        <button class="w-8 h-8 flex items-center justify-center text-gray-400 dark:text-white text-[10px] font-bold hover:bg-white dark:hover:bg-slate-800 rounded-lg transition hidden xs:flex">3</button>
                        <span class="px-1 text-gray-300 dark:text-gray-600 text-[10px] hidden xs:block">...</span>
                        <button class="w-8 h-8 flex items-center justify-center border border-gray-200 dark:border-slate-800 rounded-lg text-gray-400 dark:text-white hover:bg-white dark:hover:bg-slate-800 transition"><i class="fa-solid fa-chevron-right text-[10px]"></i></button>
                    </div>
                </div>
            </div>

            {{-- Footer Info Responsive --}}
            <div class="mt-4 bg-white dark:bg-slate-900 rounded-xl border border-gray-100 dark:border-slate-800 p-4 lg:p-6 flex flex-col sm:flex-row items-start gap-4 shadow-sm mb-10 transition-all hover:shadow-md">
                <div class="w-10 h-10 bg-blue-50 dark:bg-blue-900/30 rounded-full flex items-center justify-center flex-shrink-0 shadow-inner transition-colors">
                    <i class="fa-solid fa-circle-info text-blue-600 dark:text-blue-400"></i>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-gray-800 dark:text-white transition-colors uppercase tracking-tight">Informasi Retensi Data</h4>
                    <p class="text-xs text-gray-500 dark:text-gray-200 mt-1 leading-relaxed transition-colors">
                        Data log aktivitas disimpan otomatis selama <span class="font-bold text-gray-700 dark:text-white">365 hari</span>. Untuk akses data historis lebih lama, silakan hubungi <span class="font-bold text-gray-700 dark:text-white underline decoration-blue-200">Tim IT Infrastruktur RS</span>.
                    </p>
                </div>
            </div>
        </main>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 10px; }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; }
    
    /* Utility untuk device sangat kecil */
    @media (max-width: 380px) {
        .xs\:hidden { display: none; }
        .xs\:flex { display: flex; }
    }
    
    [x-cloak] { display: none !important; }
</style>
@endsection