@extends('components.layout')
@section('title', 'Beranda')

@section('content')
    @php
        // Simulasi Role: ganti ke 'teacher' untuk melihat tampilan teacher
        $role = $role ?? 'admin'; 
    @endphp

    <div class="flex h-screen overflow-hidden bg-slate-50 dark:bg-slate-950 transition-colors duration-300"
        x-data="dashboardData">

        {{-- Sidebar --}}
        <aside id="sidebar" :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-slate-900 border-r dark:border-slate-800 transition-transform duration-300 lg:translate-x-0 lg:static lg:inset-0 shrink-0 overflow-y-auto">

            @include('components.nav-superadmin', ['role' => $role])
        </aside>

        {{-- Overlay Mobile --}}
        <div x-show="sidebarOpen" @click="sidebarOpen = false"
            class="fixed inset-0 z-40 bg-black/50 backdrop-blur-sm lg:hidden" x-cloak></div>

        <div class="flex-1 flex flex-col min-w-0 transition-colors duration-300">

            @include('components.header-superadmin', ['title' => 'DASHBOARD'])

            <main class="flex-1 overflow-y-auto p-4 lg:p-8 custom-scrollbar">
                {{-- Welcome Section --}}
                <div class="mb-8">
                    <h2 class="text-lg lg:text-xl font-bold text-gray-800 dark:text-white transition-colors">Selamat Datang,
                        {{ auth()->user()->nama }}!
                    </h2>
                    <p class="text-xs lg:text-sm text-gray-500 dark:text-gray-400 mt-1">Pantau statistik pelatihan dan
                        aktivitas sistem Hospital LMS hari ini.</p>
                </div>

                {{-- Statistic Grid --}}
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 lg:gap-4 mb-8">
                    {{-- Card 1 --}}
                    <div
                        class="bg-white dark:bg-slate-900 p-4 lg:p-5 rounded-xl border border-gray-100 dark:border-slate-800 shadow-sm transition-all hover:shadow-md group">
                        <div class="flex justify-between items-start mb-3">
                            <p
                                class="text-[10px] lg:text-[10px] font-bold text-gray-400 dark:text-white uppercase tracking-wider">
                                Total Pengguna</p>
                            <i
                                class="fa-solid fa-users text-blue-600 opacity-20 dark:opacity-60 text-xs lg:text-sm group-hover:opacity-100 transition-opacity"></i>
                        </div>
                        <h3 class="text-lg lg:text-2xl font-bold text-gray-800 dark:text-white"
                            x-text="statistics.total_pengguna">0</h3>
                        <p
                            class="text-[9px] lg:text-[10px] text-gray-400 dark:text-gray-200 mt-1 font-medium leading-tight truncate">
                            Karyawan terdaftar aktif</p>
                    </div>

                    {{-- Card 2 --}}
                    <div
                        class="bg-white dark:bg-slate-900 p-4 lg:p-5 rounded-xl border border-gray-100 dark:border-slate-800 shadow-sm transition-all hover:shadow-md group">
                        <div class="flex justify-between items-start mb-3">
                            <p
                                class="text-[10px] lg:text-[10px] font-bold text-gray-400 dark:text-white uppercase tracking-wider">
                                Total Unit Kerja</p>
                            <i
                                class="fa-solid fa-building text-emerald-600 opacity-20 dark:opacity-60 text-xs lg:text-sm group-hover:opacity-100 transition-opacity"></i>
                        </div>
                        <h3 class="text-lg lg:text-2xl font-bold text-gray-800 dark:text-white"
                            x-text="statistics.total_unit_kerja">0</h3>
                        <p
                            class="text-[9px] lg:text-[10px] text-gray-400 dark:text-gray-200 mt-1 font-medium leading-tight truncate">
                            Departemen terintegrasi</p>
                    </div>

                    {{-- Card 3 --}}
                    <div
                        class="bg-white dark:bg-slate-900 p-4 lg:p-5 rounded-xl border border-gray-100 dark:border-slate-800 shadow-sm transition-all hover:shadow-md group">
                        <div class="flex justify-between items-start mb-3">
                            <p
                                class="text-[10px] lg:text-[10px] font-bold text-gray-400 dark:text-white uppercase tracking-wider">
                                Total Jenis Tenaga</p>
                            <i
                                class="fa-solid fa-id-card-clip text-indigo-600 opacity-20 dark:opacity-60 text-xs lg:text-sm group-hover:opacity-100 transition-opacity"></i>
                        </div>
                        <h3 class="text-lg lg:text-2xl font-bold text-gray-800 dark:text-white"
                            x-text="statistics.total_jenis_tenaga">0</h3>
                        <p
                            class="text-[9px] lg:text-[10px] text-gray-400 dark:text-gray-200 mt-1 font-medium leading-tight truncate">
                            Kategori Profesi</p>
                    </div>

                    {{-- Card 4 --}}
                    <div
                        class="bg-white dark:bg-slate-900 p-4 lg:p-5 rounded-xl border border-gray-100 dark:border-slate-800 shadow-sm transition-all hover:shadow-md group">
                        <div class="flex justify-between items-start mb-3">
                            <p
                                class="text-[10px] lg:text-[10px] font-bold text-gray-400 dark:text-white uppercase tracking-wider">
                                Total Pelatihan</p>
                            <i
                                class="fa-solid fa-book-open text-purple-600 opacity-20 dark:opacity-60 text-xs lg:text-sm group-hover:opacity-100 transition-opacity"></i>
                        </div>
                        <h3 class="text-lg lg:text-2xl font-bold text-gray-800 dark:text-white"
                            x-text="statistics.total_pelatihan">0</h3>
                        <p
                            class="text-[9px] lg:text-[10px] text-gray-400 dark:text-gray-200 mt-1 font-medium leading-tight truncate">
                            Modul pelatihan tersedia</p>
                    </div>

                    {{-- Card 5 --}}
                    <div
                        class="bg-white dark:bg-slate-900 p-4 lg:p-5 rounded-xl border border-gray-100 dark:border-slate-800 shadow-sm transition-all hover:shadow-md group">
                        <div class="flex justify-between items-start mb-3">
                            <p
                                class="text-[10px] lg:text-[10px] font-bold text-gray-400 dark:text-white uppercase tracking-wider">
                                Pelatihan Aktif</p>
                            <i
                                class="fa-solid fa-clock text-orange-600 opacity-20 dark:opacity-60 text-xs lg:text-sm group-hover:opacity-100 transition-opacity"></i>
                        </div>
                        <h3 class="text-lg lg:text-2xl font-bold text-gray-800 dark:text-white" x-text="statistics.aktif">0
                        </h3>
                        <p
                            class="text-[9px] lg:text-[10px] text-gray-400 dark:text-gray-200 mt-1 font-medium leading-tight truncate">
                            Sedang berjalan saat ini</p>
                    </div>

                    {{-- Card 6 --}}
                    <div
                        class="bg-white dark:bg-slate-900 p-4 lg:p-5 rounded-xl border border-gray-100 dark:border-slate-800 shadow-sm transition-all hover:shadow-md group">
                        <div class="flex justify-between items-start mb-3">
                            <p
                                class="text-[10px] lg:text-[10px] font-bold text-gray-400 dark:text-white uppercase tracking-wider">
                                Pelatihan Selesai</p>
                            <i
                                class="fa-solid fa-certificate text-pink-600 opacity-20 dark:opacity-60 text-xs lg:text-sm group-hover:opacity-100 transition-opacity"></i>
                        </div>
                        <h3 class="text-lg lg:text-2xl font-bold text-gray-800 dark:text-white" x-text="statistics.selesai">
                            0</h3>
                        <p
                            class="text-[9px] lg:text-[10px] text-gray-400 dark:text-gray-200 mt-1 font-medium leading-tight truncate">
                            Melewati batas waktu</p>
                    </div>
                </div>

                {{-- Table Controls --}}
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-4">
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-2">
                            <label class="text-xs font-bold text-gray-600 dark:text-gray-400">Tampilkan:</label>
                            <select x-model="filters.per_page" @change="fetchTableData(1)"
                                class="bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg text-xs px-2 py-1.5 focus:outline-none focus:ring-2 focus:ring-blue-500/20 text-gray-700 dark:text-white transition-all cursor-pointer">
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>

                        <div class="flex items-center gap-2">
                            <input type="checkbox" id="show_all" x-model="filters.all" @change="fetchTableData(1)"
                                class="w-4 h-4 text-blue-600 bg-gray-100 dark:bg-slate-800 border-gray-300 dark:border-slate-700 rounded focus:ring-blue-500">
                            <label for="show_all"
                                class="text-xs font-bold text-gray-600 dark:text-gray-400 cursor-pointer">Tampilkan Semua
                                Data</label>
                        </div>
                    </div>
                </div>

                {{-- Table Section --}}
                <div
                    class="bg-white dark:bg-slate-900 rounded-xl border border-gray-100 dark:border-slate-800 shadow-sm overflow-hidden mb-10">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs min-w-[800px]">
                            <thead
                                class="bg-gray-50/50 dark:bg-slate-800/50 text-gray-500 dark:text-gray-400 font-bold border-b dark:border-slate-800">
                                <tr>
                                    <th class="py-4 px-6 uppercase tracking-wider">Nama Karyawan</th>
                                    <th class="py-4 px-4 uppercase tracking-wider">Unit Kerja</th>
                                    <th class="py-4 px-4 uppercase tracking-wider text-center">Pelatihan yang Telah Diikuti
                                    </th>
                                    <th class="py-4 px-4 uppercase tracking-wider text-center">JPL</th>
                                    <th class="py-4 px-6 uppercase tracking-wider text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody
                                class="divide-y divide-gray-100 dark:divide-slate-800 text-gray-700 dark:text-white transition-colors">
                                <template x-for="user in users" :key="user.user_id">
                                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors">
                                        <td class="py-4 px-6">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 bg-gray-100 dark:bg-slate-700 rounded-lg flex items-center justify-center font-bold text-gray-400 dark:text-white text-[10px] shrink-0 uppercase"
                                                    x-text="user.nama.substring(0, 2)">
                                                </div>
                                                <div class="truncate">
                                                    <p class="font-bold text-gray-800 dark:text-white" x-text="user.nama">
                                                    </p>
                                                    <p class="text-[10px] text-gray-400 dark:text-gray-500 font-medium">NIP:
                                                        <span x-text="user.nik"></span>
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-4 px-4 text-gray-500 dark:text-gray-400"
                                            x-text="user.unit_kerja ? user.unit_kerja.unit_kerja : '-'"></td>
                                        <td class="py-4 px-4 text-center font-bold" x-text="user.progresses_count"></td>
                                        <td class="py-4 px-4 text-center font-bold" x-text="user.total_jpl"></td>
                                        <td class="py-4 px-6 text-center">
                                            <span class="px-3 py-1 rounded-full text-[9px] font-bold"
                                                :class="user.total_jpl >= 20 ? 'bg-emerald-50 text-emerald-600 border border-emerald-100 dark:bg-emerald-900/20 dark:border-emerald-800' : 'bg-amber-50 text-amber-600 border border-amber-100 dark:bg-amber-900/20 dark:border-amber-800'"
                                                x-text="user.total_jpl >= 20 ? 'Terpenuhi' : 'Belum Terpenuhi'">
                                            </span>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Pagination --}}
                <div class="mt-4 px-4">
                    <template x-if="pagination.links && !filters.all">
                        <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-8">
                            <p class="text-[10px] text-gray-400 dark:text-white font-medium uppercase tracking-wider">
                                Menampilkan <span x-text="pagination.from || 0"></span>-<span
                                    x-text="pagination.to || 0"></span> dari <span x-text="pagination.total || 0"></span>
                                pengguna
                            </p>
                            <div class="flex flex-wrap items-center justify-center gap-1">
                                <template x-for="(link, index) in pagination.links" :key="index">
                                    <button
                                        @click.prevent="if(link.url) fetchTableData(new URL(link.url).searchParams.get('page'))"
                                        x-html="link.label" :disabled="!link.url || link.active" :class="[
                                                            'px-3 py-1.5 text-[10px] sm:text-xs font-medium rounded-md transition-colors',
                                                            link.active ? 'bg-blue-600 text-white shadow-md' : 'bg-white dark:bg-slate-800 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-700',
                                                            !link.url ? 'opacity-50 cursor-not-allowed' : ''
                                                        ]"></button>
                                </template>
                            </div>
                        </div>
                    </template>
                    <template x-if="filters.all">
                        <div class="flex justify-between items-center mb-8 mt-4">
                            <p class="text-[10px] text-gray-400 dark:text-white font-medium uppercase tracking-wider">
                                Menampilkan semua <span class="font-bold text-gray-600 dark:text-white"
                                    x-text="users.length"></span> pengguna
                            </p>
                        </div>
                    </template>
                </div>

                {{-- Action Buttons Floating Right --}}
                <div class="flex justify-end gap-3 mb-10">
                    <a :href="'{{ route('admin.dashboard.export.excel') }}' + getQueryString()"
                        class="flex items-center gap-2 px-4 py-2 border-2 border-blue-600 text-blue-600 bg-white dark:bg-slate-900 rounded-lg text-xs font-bold hover:bg-blue-50 dark:hover:bg-blue-900/20 transition active:scale-95 shadow-sm">
                        <i class="fa-solid fa-file-excel"></i> Export Excel
                    </a>
                    <a :href="'{{ route('admin.dashboard.export.pdf') }}' + getQueryString()"
                        class="flex items-center gap-2 px-4 py-2 border-2 border-red-500 text-red-500 bg-white dark:bg-slate-900 rounded-lg text-xs font-bold hover:bg-red-50 dark:hover:bg-red-900/20 transition active:scale-95 shadow-sm">
                        <i class="fa-solid fa-file-pdf"></i> Export PDF
                    </a>
                </div>
            </main>
        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #E2E8F0;
            border-radius: 10px;
        }

        .dark .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #334155;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('dashboardData', () => ({
                sidebarOpen: false,
                darkMode: localStorage.getItem('theme') === 'dark',
                isLoading: true,
                users: [],
                statistics: {
                    total_pengguna: 0,
                    total_unit_kerja: 0,
                    total_jenis_tenaga: 0,
                    total_pelatihan: 0,
                    aktif: 0,
                    selesai: 0
                },
                pagination: {},
                filters: {
                    search: '',
                    per_page: 10,
                    all: false
                },

                init() {
                    this.fetchStatistics();
                    this.fetchTableData();
                },

                async fetchStatistics() {
                    try {
                        const response = await fetch('/api/admin/dashboard');
                        const result = await response.json();
                        if (result.success) {
                            this.statistics.total_pengguna = result.data.statistik_utama.total_pengguna;
                            this.statistics.total_unit_kerja = result.data.statistik_utama.total_unit_kerja;
                            this.statistics.total_jenis_tenaga = result.data.statistik_utama.total_jenis_tenaga;
                            this.statistics.total_pelatihan = result.data.statistik_utama.total_pelatihan;
                            this.statistics.aktif = result.data.status_pelatihan.aktif;
                            this.statistics.selesai = result.data.status_pelatihan.selesai;
                        }
                    } catch (error) {
                        console.error('Error fetching statistics:', error);
                    }
                },

                async fetchTableData(page = 1) {
                    this.isLoading = true;
                    try {
                        const url = new URL('/api/admin/karyawan-progress', window.location.origin);
                        url.searchParams.append('page', page);
                        if (this.filters.search) url.searchParams.append('search', this.filters.search);
                        url.searchParams.append('per_page', this.filters.per_page);
                        if (this.filters.all) url.searchParams.append('all', 'true');

                        const response = await fetch(url);
                        const result = await response.json();

                        this.users = result.data; // result is paginated object
                        this.pagination = {
                            current_page: result.current_page,
                            last_page: result.last_page,
                            links: result.links,
                            from: result.from,
                            to: result.to,
                            total: result.total
                        };
                    } catch (error) {
                        console.error('Error fetching table data:', error);
                    } finally {
                        this.isLoading = false;
                    }
                },

                getQueryString() {
                    const params = new URLSearchParams();
                    if (this.filters.search) params.append('search', this.filters.search);
                    if (this.filters.all) params.append('all', 'true');
                    const str = params.toString();
                    return str ? '?' + str : '';
                }
            }));
        });
    </script>
@endsection