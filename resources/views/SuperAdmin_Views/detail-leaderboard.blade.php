@extends('components.layout')
@section('title', 'Detail Leaderboard')

@section('content')
    {{-- Menambahkan state sidebarOpen untuk kontrol menu mobile --}}
    <div class="flex h-screen overflow-hidden bg-slate-50 dark:bg-slate-950 transition-colors duration-300"
        x-data="leaderboardData()" x-init="initData()">

        {{-- Sidebar Responsive Logic --}}
        <aside id="sidebar" :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-slate-900 border-r dark:border-slate-800 transition-transform duration-300 lg:translate-x-0 lg:static lg:inset-0 shrink-0 overflow-y-auto">
            @include('components.nav-superadmin', ['hideSideMenu' => true])
        </aside>

        {{-- Overlay untuk menutup sidebar di mobile --}}
        <div x-show="sidebarOpen" @click="sidebarOpen = false"
            x-transition:enter="transition opacity-100 ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:leave="transition opacity-100 ease-in duration-200" x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-40 bg-black/50 backdrop-blur-sm lg:hidden" x-cloak>
        </div>

        <div class="flex-1 flex flex-col min-w-0 transition-colors duration-300">

            @include('components.header-superadmin', ['title' => 'Detail Leaderboard'])

            <main class="flex-1 overflow-y-auto p-4 lg:p-8 custom-scrollbar">

                <nav class="mb-6 text-[13px] lg:text-[14px] font-medium">
                    <ol class="flex items-center gap-2 text-gray-500 dark:text-gray-400">
                        <li>
                            <a href="{{ route('beranda-superadmin') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Beranda</a>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-gray-300 dark:text-gray-600"> > </span>
                            <span class="text-gray-800 dark:text-white font-semibold">Detail Leaderboard</span>
                        </li>
                    </ol>
                </nav>
                
                <div
                    class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 overflow-hidden mb-6 transition-colors duration-300">
                    <div
                        class="p-4 lg:p-6 border-b dark:border-slate-800 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white dark:bg-slate-900 transition-colors">
                        <h3 class="font-bold text-gray-800 dark:text-white text-sm lg:text-base leading-tight">Leaderboard
                            Jam Pembelajaran Per Unit Kerja</h3>

                        {{-- Dropdown Filter --}}
                        <div class="relative inline-block text-left w-full sm:w-auto" x-data="{ open: false }">
                            <button @click="open = !open"
                                class="flex items-center justify-between sm:justify-start gap-2 w-full sm:w-auto px-4 py-2 border border-gray-200 dark:border-slate-700 rounded-lg text-xs font-bold text-gray-500 dark:text-white hover:bg-gray-50 dark:hover:bg-slate-800 transition active:scale-95 bg-white dark:bg-slate-900">
                                <span>Terapkan Filter</span>
                                <i class="fa-solid fa-sliders text-[10px]"></i>
                            </button>

                            <div x-show="open" @click.away="open = false" x-cloak x-transition
                                class="absolute right-0 mt-2 w-48 bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 rounded-xl shadow-xl z-50 overflow-hidden">

                                <div class="p-2 space-y-1">
                                    <div class="px-3 py-1 mb-1">
                                        <p
                                            class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest">
                                            Status JPL</p>
                                    </div>
                                    <button @click.prevent="setStatusFilter('terpenuhi')"
                                        class="flex items-center justify-between w-full px-3 py-2 text-[11px] font-bold rounded-lg hover:bg-emerald-50 dark:hover:bg-emerald-900/30 hover:text-emerald-600 transition group"
                                        :class="filters.status == 'terpenuhi' ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400' : 'text-gray-600 dark:text-gray-300'">
                                        Terpenuhi
                                        <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                                    </button>
                                    <button @click.prevent="setStatusFilter('belum_terpenuhi')"
                                        class="flex items-center justify-between w-full px-3 py-2 text-[11px] font-bold rounded-lg hover:bg-amber-50 dark:hover:bg-amber-900/30 hover:text-amber-600 transition group"
                                        :class="filters.status == 'belum_terpenuhi' ? 'bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400' : 'text-gray-600 dark:text-gray-300'">
                                        Belum Terpenuhi
                                        <div class="w-2 h-2 rounded-full bg-amber-500"></div>
                                    </button>
                                </div>
                                <div class="border-t border-gray-50 dark:border-slate-700 p-2">
                                    <button @click.prevent="setStatusFilter('')"
                                        class="block text-center w-full py-1 text-[10px] font-bold text-red-400 hover:text-red-600 transition">Reset
                                        Filter</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Table Wrapper: Penting untuk scroll horizontal di mobile --}}
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs min-w-[700px]">
                            <thead
                                class="bg-gray-50/50 dark:bg-slate-800/50 text-gray-500 dark:text-white font-bold border-b dark:border-slate-800">
                                <tr>
                                    <th class="py-4 px-6 uppercase tracking-wider">Nama Karyawan</th>
                                    <th class="py-4 px-4 uppercase tracking-wider">Unit Kerja</th>
                                    <th class="py-4 px-4 uppercase tracking-wider text-center">Pelatihan</th>
                                    <th class="py-4 px-4 uppercase tracking-wider text-center">JPL</th>
                                    <th class="py-4 px-6 uppercase tracking-wider text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-slate-800 text-gray-700 dark:text-white font-medium">
                                <template x-if="isLoading">
                                    <tr>
                                        <td colspan="5" class="py-10 text-center">
                                            <i class="fa-solid fa-spinner fa-spin text-3xl text-blue-500 mb-2"></i>
                                            <p class="text-xs text-gray-500">Memuat data...</p>
                                        </td>
                                    </tr>
                                </template>
                                <template x-if="!isLoading && users.length === 0">
                                    <tr>
                                        <td colspan="5" class="py-8 text-center text-gray-500 dark:text-gray-400 text-xs">Belum ada data pelatihan karyawan.</td>
                                    </tr>
                                </template>
                                <template x-if="!isLoading && users.length > 0">
                                    <template x-for="user in users" :key="user.user_id">
                                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-800 transition border-b border-gray-50 dark:border-slate-800 last:border-0">
                                            <td class="py-5 px-6">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-8 h-8 bg-gray-200 dark:bg-slate-700 rounded flex items-center justify-center font-bold text-gray-500 dark:text-white text-[10px] uppercase transition-colors shrink-0" x-text="getInitials(user.name)"></div>
                                                    <div class="truncate">
                                                        <p class="font-bold text-gray-800 dark:text-white transition-colors truncate" x-text="user.name"></p>
                                                        <p class="text-[10px] text-gray-400 dark:text-gray-300">NIP: <span x-text="user.nip"></span></p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-5 px-4 text-gray-500 dark:text-gray-300 leading-tight transition-colors" x-text="(user.unit_kerjas && user.unit_kerjas.length > 0) ? user.unit_kerjas.map(u => u.unit_name).join(', ') : '-'"></td>
                                            <td class="py-5 px-4 text-center font-bold text-gray-800 dark:text-white transition-colors" x-text="user.pelatihan_selesai"></td>
                                            <td class="py-5 px-4 text-center font-bold text-gray-800 dark:text-white transition-colors" x-text="user.total_jpl || 0"></td>
                                            <td class="py-5 px-6 text-center">
                                                <span class="px-3 py-1 rounded-full text-[9px] font-bold transition-colors"
                                                    :class="(user.total_jpl || 0) >= 20 ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-500 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-800' : 'bg-amber-50 dark:bg-amber-900/30 text-amber-500 dark:text-amber-400 border border-amber-100 dark:border-amber-800'"
                                                    x-text="(user.total_jpl || 0) >= 20 ? 'TERPENUHI' : 'BELUM TERPENUHI'">
                                                </span>
                                            </td>
                                        </tr>
                                    </template>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    <div class="p-4 lg:p-6 border-t dark:border-slate-800 flex flex-col sm:flex-row justify-between items-center gap-4">
                        <div class="flex items-center gap-4 w-full sm:w-auto">
                            <div class="flex items-center gap-2">
                                <label for="per_page" class="text-xs font-bold text-gray-500 dark:text-gray-400">Tampilkan</label>
                                <select id="per_page" x-model="filters.per_page" @change="fetchData(1)"
                                    :disabled="filters.all === 'true'"
                                    class="text-xs font-bold text-gray-600 dark:text-gray-300 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg px-2 py-1 focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all disabled:opacity-50">
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </div>
                            
                            <div class="flex items-center gap-2">
                                <input type="checkbox" id="show_all" 
                                    x-model="filters.all" true-value="true" false-value="false" @change="fetchData(1)"
                                    class="w-3.5 h-3.5 text-blue-600 bg-white dark:bg-slate-800 border-gray-200 dark:border-slate-700 rounded focus:ring-blue-500/20 transition-all cursor-pointer">
                                <label for="show_all" class="text-xs font-bold text-gray-500 dark:text-gray-400 cursor-pointer">Tampilkan Semua</label>
                            </div>
                        </div>

                        <div class="w-full sm:w-auto" x-show="pagination.links && filters.all !== 'true'">
                            <div class="flex flex-wrap items-center justify-center gap-1">
                                <template x-for="(link, index) in pagination.links" :key="index">
                                    <button @click.prevent="if(link.url) fetchData(new URL(link.url).searchParams.get('page'))"
                                        x-html="link.label"
                                        :disabled="!link.url || link.active"
                                        :class="[
                                            'px-3 py-1.5 text-[10px] sm:text-xs font-medium rounded-md transition-colors',
                                            link.active ? 'bg-blue-600 text-white shadow-md' : 'bg-white dark:bg-slate-800 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-700',
                                            !link.url ? 'opacity-50 cursor-not-allowed' : ''
                                        ]"></button>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row justify-end gap-3 mb-8 transition-colors">
                    <a :href="'{{ route('leaderboard.export.excel') }}' + getQueryString()" onclick="showExportNotification && typeof showExportNotification === 'function' ? showExportNotification() : null"
                        class="flex items-center justify-center gap-2 px-5 py-2.5 border-2 border-blue-600 text-blue-600 bg-white dark:bg-slate-900 rounded-lg text-[11px] font-bold hover:bg-blue-50 dark:hover:bg-blue-900/20 transition active:scale-95">
                        <i class="fa-solid fa-file-excel"></i> Export Excel
                    </a>
                    <a :href="'{{ route('leaderboard.export.pdf') }}' + getQueryString()" onclick="showExportNotification && typeof showExportNotification === 'function' ? showExportNotification() : null"
                        class="flex items-center justify-center gap-2 px-5 py-2.5 border-2 border-red-500 text-red-500 bg-white dark:bg-slate-900 rounded-lg text-[11px] font-bold hover:bg-red-50 dark:hover:bg-red-900/20 transition active:scale-95">
                        <i class="fa-solid fa-file-pdf"></i> Export PDF
                    </a>
                </div>

                {{-- Information Card --}}
                <div
                    class="bg-white dark:bg-slate-900 rounded-xl border border-gray-100 dark:border-slate-800 p-6 flex items-start gap-4 shadow-sm mb-10 transition-colors">
                    <div
                        class="w-8 h-8 bg-gray-100 dark:bg-slate-800 rounded-full shrink-0 flex items-center justify-center transition-colors">
                        <i class="fa-solid fa-check text-gray-600 dark:text-white text-xs"></i>
                    </div>
                    <div>
                        <h4
                            class="text-sm font-bold text-gray-800 dark:text-white transition-colors uppercase tracking-tight">
                            Informasi Status</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-300 mt-1 leading-relaxed transition-colors">
                            Status <span
                                class="font-bold text-gray-700 dark:text-white underline decoration-emerald-500/30 decoration-2 underline-offset-4">"Terpenuhi"</span>
                            berarti karyawan telah mencapai ambang batas minimum Jam Pembelajaran (JPL) yang diwajibkan oleh
                            administrasi rumah sakit.
                        </p>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
            height: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #E2E8F0;
            border-radius: 10px;
        }

        .dark .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #334155;
        }

        @media (max-width: 480px) {
            .xs\:hidden {
                display: none;
            }

            .xs\:block {
                display: block;
            }
        }

        [x-cloak] {
            display: none !important;
        }
    </style>

    <script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('leaderboardData', () => ({
            sidebarOpen: false, 
            darkMode: localStorage.getItem('theme') === 'dark',
            isLoading: true,
            users: [],
            pagination: {},
            filters: {
                status: new URLSearchParams(window.location.search).get('status') || '',
                per_page: new URLSearchParams(window.location.search).get('per_page') || '10',
                all: new URLSearchParams(window.location.search).get('all') || 'false'
            },

            async initData() {
                await this.fetchData();
            },

            async fetchData(page = 1) {
                this.isLoading = true;
                try {
                    const url = new URL('/api/admin/leaderboard/data', window.location.origin);
                    url.searchParams.append('page', page);
                    if (this.filters.status) url.searchParams.append('status', this.filters.status);
                    url.searchParams.append('per_page', this.filters.per_page);
                    if (this.filters.all === 'true') url.searchParams.append('all', 'true');

                    // Update url state safely
                    const params = new URLSearchParams(url.search);
                    params.delete('page');
                    window.history.replaceState({}, '', `${window.location.pathname}?${params}`);

                    const response = await fetch(url.toString(), {
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    const data = await response.json();
                    
                    this.users = data.data;
                    this.pagination = {
                        current_page: data.current_page,
                        last_page: data.last_page,
                        links: data.links
                    };
                } catch (error) {
                    console.error(error);
                } finally {
                    this.isLoading = false;
                }
            },

            setStatusFilter(status) {
                this.filters.status = status;
                this.fetchData(1);
            },

            getInitials(name) {
                if (!name) return '';
                const parts = name.trim().split(' ');
                if (parts.length === 1) return parts[0].substring(0, 2).toUpperCase();
                return (parts[0].charAt(0) + parts[parts.length - 1].charAt(0)).toUpperCase();
            },

            getQueryString() {
                const params = new URLSearchParams();
                if (this.filters.status) params.append('status', this.filters.status);
                if (this.filters.all === 'true') params.append('all', 'true');
                const str = params.toString();
                return str ? '?' + str : '';
            }
        }));
    });
    </script>
@endsection