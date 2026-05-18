@extends('components.layout')
@section('title', 'Beranda')

@section('content')
@php
    // Simulasi Role: ganti ke 'teacher' untuk melihat tampilan teacher
    $role = $role ?? 'admin'; 
@endphp

<div class="flex h-screen overflow-hidden bg-slate-50 dark:bg-slate-950 transition-colors duration-300" 
    x-data="{ sidebarOpen: false, darkMode: localStorage.getItem('theme') === 'dark' }">
    
    {{-- Sidebar --}}
    <aside id="sidebar"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-slate-900 border-r dark:border-slate-800 transition-transform duration-300 lg:translate-x-0 lg:static lg:inset-0 flex-shrink-0 overflow-y-auto">
        
        @include('components.nav-superadmin', ['role' => $role])
    </aside>

    {{-- Overlay Mobile --}}
    <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-black/50 backdrop-blur-sm lg:hidden" x-cloak></div>

    <div class="flex-1 flex flex-col min-w-0 transition-colors duration-300">
        
        {{-- Header --}}
        <header class="bg-white dark:bg-slate-900 border-b dark:border-slate-800 h-16 flex items-center justify-between px-4 lg:px-8 flex-shrink-0 z-10">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = true" class="lg:hidden p-2 text-gray-500 dark:text-white">
                    <i class="fa-solid fa-bars text-lg"></i>
                </button>
                <h1 class="text-sm font-semibold text-gray-600 dark:text-white">Beranda</h1>
            </div>

            <div class="flex items-center gap-4">
                {{-- Search Bar --}}
                <div class="relative hidden md:block">
                    <input type="text" placeholder="Cari data..." class="w-64 pl-4 pr-10 py-2 border border-gray-200 dark:border-slate-700 rounded-lg text-xs bg-gray-50 dark:bg-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-blue-500/20">
                    <i class="fa-solid fa-magnifying-glass absolute right-3 top-2.5 text-gray-400 text-xs"></i>
                </div>

                {{-- Admin Profile --}}
                <div class="flex items-center gap-3 pl-4 border-l dark:border-slate-800">
                    <div class="">
                        @include('components.notif-superadmin')
                    </div>
                    <div class="text-right hidden sm:block">
                        <p class="text-xs font-bold text-gray-800 dark:text-white leading-tight">{{ ucfirst($role) }}</p>
                        <p class="text-[10px] text-gray-500 dark:text-gray-400 font-medium">{{ $role == 'admin' ? 'Administrator' : 'Pengajar Utama' }}</p>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-4 lg:p-8 custom-scrollbar">
            {{-- Welcome Section --}}
            <div class="mb-8">
                <h2 class="text-lg lg:text-xl font-bold text-gray-800 dark:text-white transition-colors">Selamat Datang, {{ ucfirst($role) }}!</h2>
                <p class="text-xs lg:text-sm text-gray-500 dark:text-gray-400 mt-1">Pantau statistik pelatihan dan aktivitas sistem Hospital LMS hari ini.</p>
            </div>

            {{-- Statistic Grid --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 lg:gap-4 mb-8">
                @php
                    $stats = [
                        ['label' => 'Total Pengguna', 'value' => '1,284', 'sub' => 'Karyawan terdaftar aktif', 'icon' => 'fa-users', 'color' => 'text-blue-600'],
                        ['label' => 'Total Unit', 'value' => '24', 'sub' => 'Departemen terintegrasi', 'icon' => 'fa-building', 'color' => 'text-emerald-600'],
                        ['label' => 'Jenis Tenaga', 'value' => '156', 'sub' => 'Jenis tenaga kerja tersedia', 'icon' => 'fa-id-card-clip', 'color' => 'text-indigo-600'],
                        ['label' => 'Total Modul', 'value' => '156', 'sub' => 'Modul pelatihan tersedia', 'icon' => 'fa-book-open', 'color' => 'text-purple-600'],
                        ['label' => 'Aktif', 'value' => '42', 'sub' => 'Sedang berjalan saat ini', 'icon' => 'fa-clock', 'color' => 'text-orange-600'],
                        ['label' => 'Selesai', 'value' => '114', 'sub' => 'Melewati batas waktu', 'icon' => 'fa-certificate', 'color' => 'text-pink-600'],
                    ];
                @endphp

                @foreach($stats as $stat)
                {{-- PERUBAHAN: Card stat kini mendukung Dark Mode (dark:bg-slate-900) --}}
                <div class="bg-white dark:bg-slate-900 p-4 lg:p-5 rounded-xl border border-gray-100 dark:border-slate-800 shadow-sm transition-all hover:shadow-md group">
                    <div class="flex justify-between items-start mb-3">
                        <p class="text-[10px] lg:text-[10px] font-bold text-gray-400 dark:text-white uppercase tracking-wider">{{ $stat['label'] }}</p>
                        <i class="fa-solid {{ $stat['icon'] }} {{ $stat['color'] }} opacity-20 dark:opacity-60 text-xs lg:text-sm group-hover:opacity-100 transition-opacity"></i>
                    </div>
                    <h3 class="text-lg lg:text-2xl font-bold text-gray-800 dark:text-white">{{ $stat['value'] }}</h3>
                    <p class="text-[9px] lg:text-[10px] text-gray-400 dark:text-gray-200 mt-1 font-medium leading-tight truncate">{{ $stat['sub'] }}</p>
                </div>
                @endforeach
            </div>

            {{-- Table Section --}}
            <div class="bg-white dark:bg-slate-900 rounded-xl border border-gray-100 dark:border-slate-800 shadow-sm overflow-hidden mb-10">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs min-w-[800px]">
                        <thead class="bg-gray-50/50 dark:bg-slate-800/50 text-gray-500 dark:text-gray-400 font-bold border-b dark:border-slate-800">
                            <tr>
                                <th class="py-4 px-6 uppercase tracking-wider">Nama Karyawan</th>
                                <th class="py-4 px-4 uppercase tracking-wider">Unit Kerja</th>
                                <th class="py-4 px-4 uppercase tracking-wider text-center">Pelatihan yang Telah Diikuti</th>
                                <th class="py-4 px-4 uppercase tracking-wider text-center">JPL</th>
                                <th class="py-4 px-6 uppercase tracking-wider text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-slate-800 text-gray-700 dark:text-white transition-colors">
                            @php
                                $users = [
                                    ['name' => 'dr. Ahmad Subarjo', 'nip' => '198103101', 'unit' => 'Unit Gawat Darurat', 'count' => 4, 'jpl' => 20, 'status' => 'Terpenuhi'],
                                    ['name' => 'Siti Aminah, S.Kep', 'nip' => '198203201', 'unit' => 'Keperawatan Rawat Inap', 'count' => 1, 'jpl' => 6, 'status' => 'Belum Terpenuhi'],
                                    ['name' => 'Budi Santoso', 'nip' => '198303301', 'unit' => 'Administrasi & Keuangan', 'count' => 7, 'jpl' => 35, 'status' => 'Terpenuhi'],
                                    ['name' => 'dr. Lilis Handayani', 'nip' => '198403401', 'unit' => 'Poliklinik Spesialis', 'count' => 2, 'jpl' => 9, 'status' => 'Belum Terpenuhi'],
                                    ['name' => 'Rahmat Hidayat', 'nip' => '198503501', 'unit' => 'Farmasi', 'count' => 5, 'jpl' => 25, 'status' => 'Terpenuhi'],
                                ];
                            @endphp

                            @foreach(array_merge($users, $users) as $index => $user) {{-- Duplicate data to match image length --}}
                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors">
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 bg-gray-100 dark:bg-slate-700 rounded-lg flex items-center justify-center font-bold text-gray-400 dark:text-white text-[10px] shrink-0 uppercase">
                                            {{ substr($user['name'], 4, 2) }}
                                        </div>
                                        <div class="truncate">
                                            <p class="font-bold text-gray-800 dark:text-white">{{ $user['name'] }}</p>
                                            <p class="text-[10px] text-gray-400 dark:text-gray-500 font-medium">NIP: {{ $user['nip'] }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-4 text-gray-500 dark:text-gray-400">{{ $user['unit'] }}</td>
                                <td class="py-4 px-4 text-center font-bold">{{ $user['count'] }}</td>
                                <td class="py-4 px-4 text-center font-bold">{{ $user['jpl'] }}</td>
                                <td class="py-4 px-6 text-center">
                                    <span class="px-3 py-1 rounded-full text-[9px] font-bold {{ $user['status'] == 'Terpenuhi' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100 dark:bg-emerald-900/20 dark:border-emerald-800' : 'bg-amber-50 text-amber-600 border border-amber-100 dark:bg-amber-900/20 dark:border-amber-800' }}">
                                        {{ $user['status'] }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Action Buttons Floating Right --}}
            <div class="flex justify-end gap-3 mb-10">
                <button class="flex items-center gap-2 px-4 py-2 border-2 border-blue-600 text-blue-600 rounded-lg text-xs font-bold hover:bg-blue-50 transition active:scale-95 shadow-sm">
                    <i class="fa-solid fa-file-excel"></i> Export Excel
                </button>
                <button class="flex items-center gap-2 px-4 py-2 border-2 border-red-500 text-red-500 rounded-lg text-xs font-bold hover:bg-red-50 transition active:scale-95 shadow-sm">
                    <i class="fa-solid fa-file-pdf"></i> Export PDF
                </button>
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