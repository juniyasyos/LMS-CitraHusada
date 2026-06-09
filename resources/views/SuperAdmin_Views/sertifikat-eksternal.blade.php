@extends('components.layout')
@section('title', 'Sertifikat Eksternal - ' . $user->nama)

@section('content')
    <!-- Flatpickr CSS & JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/dark.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <div class="flex h-screen overflow-hidden bg-slate-50 dark:bg-slate-950 transition-colors duration-300" 
         x-data="sertifikatEksternalData()" x-init="initData(); initFlatpickr()">

        <aside id="sidebar" :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-slate-900 border-r dark:border-slate-800 transition-transform duration-300 lg:translate-x-0 lg:static lg:inset-0 shrink-0 overflow-y-auto">
            @include('components.nav-superadmin')
        </aside>

        <div x-show="sidebarOpen" @click="sidebarOpen = false"
            x-transition:enter="transition opacity-100 ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:leave="transition opacity-100 ease-in duration-200" x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-40 bg-black/50 backdrop-blur-sm lg:hidden" x-cloak>
        </div>

        <div class="flex-1 flex flex-col min-w-0 transition-colors duration-300">

            @include('components.header-superadmin', ['title' => 'Sertifikat Eksternal'])

            <main class="flex-1 overflow-y-auto p-4 lg:p-8 custom-scrollbar">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
                    <div>
                        <div class="flex items-center gap-3 mb-1">
                            <a href="{{ route('laporan.monitoring') }}?tab=external"
                                class="flex items-center gap-2 text-xs font-bold text-blue-600 hover:text-blue-700 transition">
                                <i class="fa-solid fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                        <h2 class="text-lg lg:text-xl font-bold text-gray-800 dark:text-white transition-colors">
                            Sertifikat Eksternal: {{ $user->nama }}
                        </h2>
                        <p class="text-xs lg:text-sm text-gray-500 dark:text-gray-200 transition-colors leading-relaxed">
                            Daftar sertifikat eksternal yang dimiliki oleh <span class="font-bold">{{ $user->nama }}</span> ({{ $user->nik }})
                        </p>
                    </div>
                </div>

                {{-- Filter --}}
                <div
                    class="bg-white dark:bg-slate-900 p-4 lg:p-6 rounded-xl border border-gray-100 dark:border-slate-800 shadow-sm mb-8 transition-colors">
                    <form @submit.prevent="fetchData(1)"
                        class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 items-end">
                        <div>
                            <label
                                class="text-[11px] font-bold text-gray-500 dark:text-white uppercase mb-2 block tracking-tight">Rentang Waktu</label>
                            <input type="text" id="date_range_picker" x-model="filters.date_range"
                                placeholder="Pilih Tanggal..."
                                class="w-full border-gray-200 dark:border-slate-700 rounded-lg text-xs p-2.5 bg-gray-50 dark:bg-slate-800 text-gray-700 dark:text-white focus:outline-none transition-all cursor-pointer" readonly>
                        </div>
                        <div>
                            <label
                                class="text-[11px] font-bold text-gray-500 dark:text-white uppercase mb-2 block tracking-tight">Status</label>
                            <select x-model="filters.status"
                                class="w-full border-gray-200 dark:border-slate-700 rounded-lg text-xs p-2.5 bg-gray-50 dark:bg-slate-800 text-gray-700 dark:text-white">
                                <option value="">Semua Status</option>
                                <option value="Belum Disetujui">Belum Disetujui</option>
                                <option value="Disetujui">Disetujui</option>
                                <option value="Ditolak">Tidak Disetujui</option>
                            </select>
                        </div>
                        <button type="submit"
                            class="sm:col-span-2 bg-blue-700 hover:bg-blue-800 text-white py-2.5 rounded-lg text-xs font-bold transition flex items-center justify-center gap-2 active:scale-95 shadow-lg shadow-blue-100 dark:shadow-none">
                            <i class="fa-solid fa-filter text-[10px]"></i>
                            Terapkan Filter
                        </button>
                    </form>
                </div>

                {{-- Table --}}
                <div
                    class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 p-4 lg:p-8 mb-10 transition-colors duration-300">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                        <h3 class="font-bold text-gray-800 dark:text-white transition-colors">
                            Daftar Sertifikat Eksternal
                        </h3>
                    </div>

                    <div class="overflow-x-auto border dark:border-slate-800 rounded-lg transition-colors">
                        <table class="w-full text-left text-xs min-w-[700px]">
                            <thead
                                class="bg-gray-50 dark:bg-slate-800/50 text-gray-500 dark:text-white font-bold border-b dark:border-slate-800">
                                <tr>
                                    <th class="py-4 px-6 uppercase tracking-wider">No</th>
                                    <th class="py-4 px-4 uppercase tracking-wider">Nama Pelatihan</th>
                                    <th class="py-4 px-4 uppercase tracking-wider text-center">Tanggal</th>
                                    <th class="py-4 px-4 uppercase tracking-wider text-center">Status</th>
                                    <th class="py-4 px-6 uppercase tracking-wider text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody
                                class="divide-y divide-gray-100 dark:divide-slate-800 text-gray-700 dark:text-white transition-colors">
                                <template x-if="isLoading">
                                    <tr>
                                        <td colspan="5" class="py-10 text-center">
                                            <i class="fa-solid fa-spinner fa-spin text-3xl text-blue-500 mb-2"></i>
                                            <p class="text-xs text-gray-500">Memuat data...</p>
                                        </td>
                                    </tr>
                                </template>
                                <template x-if="!isLoading && sertifikats.length === 0">
                                    <tr>
                                        <td colspan="5" class="py-12 text-center text-gray-400 italic">Belum ada data sertifikat eksternal.</td>
                                    </tr>
                                </template>
                                <template x-if="!isLoading && sertifikats.length > 0">
                                    <template x-for="(item, index) in sertifikats" :key="item.sertifikat_eksternal_id">
                                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors">
                                            <td class="py-4 px-6 font-bold text-gray-400 dark:text-gray-300" x-text="((pagination.current_page - 1) * 10) + index + 1"></td>
                                            <td class="py-4 px-4">
                                                <div class="truncate max-w-[300px]">
                                                    <p class="font-bold text-gray-800 dark:text-white truncate" x-text="item.judul || '-'"></p>
                                                </div>
                                            </td>
                                            <td class="py-4 px-4 text-center text-gray-500 dark:text-gray-200" x-text="formatDate(item.created_at)"></td>
                                            <td class="py-4 px-4 text-center">
                                                <span
                                                    class="font-bold text-[10px] px-2.5 py-1 rounded-full border"
                                                    :class="{
                                                        'bg-emerald-50 text-emerald-600 border-emerald-100 dark:bg-emerald-900/30 dark:text-emerald-400 dark:border-emerald-800': item.status === 'Disetujui',
                                                        'bg-amber-50 text-amber-600 border-amber-100 dark:bg-amber-900/30 dark:text-amber-400 dark:border-amber-800': item.status === 'Belum Disetujui' || item.status === 'Menunggu',
                                                        'bg-red-50 text-red-600 border-red-100 dark:bg-red-900/30 dark:text-red-400 dark:border-red-800': item.status === 'Tidak Disetujui' || item.status === 'Ditolak',
                                                    }"
                                                    x-text="item.status === 'Ditolak' ? 'Tidak Disetujui' : item.status">
                                                </span>
                                            </td>
                                            <td class="py-4 px-6 text-center">
                                                <button @click="viewSertifikat(item)" class="text-blue-600 hover:text-blue-800 transition" title="Lihat Sertifikat">
                                                    <i class="fa-solid fa-eye"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </template>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-8 flex justify-center gap-2">
                        <template x-if="pagination.links">
                            <div class="flex flex-wrap items-center justify-center gap-1">
                                <template x-for="(link, index) in pagination.links" :key="index">
                                    <button @click="if(link.url) fetchData(new URL(link.url).searchParams.get('page'))"
                                        x-html="link.label"
                                        :disabled="!link.url || link.active"
                                        :class="[
                                            'px-3 py-1.5 text-[10px] sm:text-xs font-medium rounded-md transition-colors',
                                            link.active ? 'bg-blue-600 text-white shadow-md' : 'bg-white dark:bg-slate-800 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-700',
                                            !link.url ? 'opacity-50 cursor-not-allowed' : ''
                                        ]"></button>
                                </template>
                            </div>
                        </template>
                    </div>

                    <div class="flex flex-col xl:flex-row justify-between items-center mt-8 gap-6 transition-colors">
                        <p class="text-[10px] text-gray-400 dark:text-white font-medium italic order-3 xl:order-1">Sistem
                            Laporan Otomatis Citra Husada</p>
                        <div class="flex flex-col sm:flex-row items-center gap-4 order-1 xl:order-2 w-full sm:w-auto">
                            <div class="flex gap-2 w-full sm:w-auto transition-colors">
                                <a :href="'{{ route('sertifikat.eksternal.export.excel', $user->user_id) }}' + getQueryString()"
                                    class="flex-1 sm:flex-none flex items-center justify-center gap-2 px-4 py-2 border-2 border-blue-600 text-blue-600 rounded-lg text-[10px] lg:text-[11px] font-bold active:scale-95 transition-all">
                                    <i class="fa-solid fa-file-excel"></i> Export Excel
                                </a>
                                <a :href="'{{ route('sertifikat.eksternal.export.pdf', $user->user_id) }}' + getQueryString()"
                                    class="flex-1 sm:flex-none flex items-center justify-center gap-2 px-4 py-2 border-2 border-red-500 text-red-500 rounded-lg text-[10px] lg:text-[11px] font-bold active:scale-95 transition-all">
                                    <i class="fa-solid fa-file-pdf"></i> Export PDF
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>

        {{-- Modal PDF Viewer --}}
        <div x-show="openPdfViewer" class="fixed inset-0 z-[100] flex items-center justify-center p-4" x-cloak>
            <div x-show="openPdfViewer" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="openPdfViewer = false"></div>
            <div x-show="openPdfViewer" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="relative bg-white dark:bg-slate-900 w-full max-w-5xl max-h-[90vh] flex flex-col rounded-2xl shadow-2xl overflow-hidden border dark:border-slate-800 transition-all duration-300">
                <div class="flex items-center justify-between px-6 py-4 border-b dark:border-slate-800 shrink-0">
                    <h3 class="text-base font-bold text-slate-800 dark:text-white" x-text="'Sertifikat: ' + selectedTitle"></h3>
                    <div class="flex items-center gap-3">
                        <a :href="pdfUrl" target="_blank" class="text-blue-600 hover:text-blue-800 text-xs font-bold transition flex items-center gap-1">
                            <i class="fa-solid fa-external-link-alt"></i> Buka Tab Baru
                        </a>
                        <button @click="openPdfViewer = false"
                            class="text-slate-400 hover:text-slate-600 dark:hover:text-white transition"><i
                                class="fa-solid fa-xmark text-xl"></i></button>
                    </div>
                </div>
                <div class="flex-1 overflow-hidden bg-slate-50 dark:bg-slate-950/50">
                    <iframe :src="pdfUrl" class="w-full h-full min-h-[70vh]" frameborder="0"></iframe>
                </div>
            </div>
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

        [x-cloak] {
            display: none !important;
        }
    </style>

    <script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('sertifikatEksternalData', () => ({
            sidebarOpen: false, 
            darkMode: localStorage.getItem('theme') === 'dark', 
            openPdfViewer: false,
            pdfUrl: '',
            selectedTitle: '',
            isLoading: true,
            sertifikats: [],
            pagination: {},
            filters: {
                date_range: '',
                status: ''
            },

            async initData() {
                await this.fetchData();
            },

            initFlatpickr() {
                flatpickr("#date_range_picker", {
                    mode: 'range',
                    dateFormat: 'Y-m-d',
                    defaultDate: this.filters.date_range || null,
                    onChange: (selectedDates, dateStr) => {
                        this.filters.date_range = dateStr;
                    }
                });
            },

            async fetchData(page = 1) {
                this.isLoading = true;
                try {
                    const url = new URL('/api/admin/laporan-monitoring/sertifikat-eksternal/{{ $user->user_id }}', window.location.origin);
                    url.searchParams.append('page', page);
                    if (this.filters.date_range) {
                        const dates = this.filters.date_range.split(' to ');
                        if (dates.length === 2) {
                            url.searchParams.append('start_date', dates[0]);
                            url.searchParams.append('end_date', dates[1]);
                        } else if (dates.length === 1 && dates[0]) {
                            url.searchParams.append('start_date', dates[0]);
                            url.searchParams.append('end_date', dates[0]);
                        }
                    }
                    if (this.filters.status) url.searchParams.append('status', this.filters.status);

                    const response = await fetch(url.toString(), {
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    const result = await response.json();
                    
                    this.sertifikats = result.data.data;
                    this.pagination = {
                        current_page: result.data.current_page,
                        last_page: result.data.last_page,
                        links: result.data.links
                    };
                } catch (error) {
                    console.error('Error fetching sertifikat eksternal:', error);
                } finally {
                    this.isLoading = false;
                }
            },

            formatDate(dateStr) {
                if (!dateStr) return '-';
                return new Date(dateStr).toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' });
            },

            getQueryString() {
                const params = new URLSearchParams();
                if (this.filters.date_range) {
                    const dates = this.filters.date_range.split(' to ');
                    if (dates.length === 2) {
                        params.append('start_date', dates[0]);
                        params.append('end_date', dates[1]);
                    } else if (dates.length === 1 && dates[0]) {
                        params.append('start_date', dates[0]);
                        params.append('end_date', dates[0]);
                    }
                }
                if (this.filters.status) params.append('status', this.filters.status);
                const str = params.toString();
                return str ? '?' + str : '';
            },

            viewSertifikat(item) {
                if (!item.image_path) {
                    alert('File sertifikat tidak tersedia.');
                    return;
                }
                this.selectedTitle = item.judul || 'Sertifikat';
                this.pdfUrl = '/storage/' + item.image_path;
                this.openPdfViewer = true;
            }
        }));
    });
    </script>
@endsection
