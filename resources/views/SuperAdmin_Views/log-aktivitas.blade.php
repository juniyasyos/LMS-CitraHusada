@extends('components.layout')
@section('title', 'Log Aktivitas')
@section('content')

{{-- Root Container dengan state Alpine.js --}}
<div class="flex h-screen overflow-hidden bg-slate-50 dark:bg-slate-950 transition-colors duration-300" 
     x-data="logAktivitasData()">
    
    {{-- Sidebar Responsive --}}
    <aside id="sidebar"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-slate-900 border-r dark:border-slate-800 transition-transform duration-300 lg:translate-x-0 lg:static lg:inset-0 shrink-0 overflow-y-auto">
        @include('components.nav-superadmin')
    </aside>

    {{-- Overlay untuk mobile --}}
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
        @include('components.header-superadmin', ['title' => 'Log Aktivitas'])

        <main class="flex-1 overflow-y-auto p-4 lg:p-8 custom-scrollbar">
            
            {{-- Main Audit Card --}}
            <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 p-4 lg:p-8 mb-6 transition-all">
                
                <div class="mb-8">
                    <div class="flex items-center gap-2 text-blue-600 dark:text-blue-400 mb-1">
                        <i class="fa-solid fa-shield-halved text-xs"></i>
                        <span class="text-[10px] font-bold uppercase tracking-wider">Keamanan & Audit</span>
                    </div>
                    <h2 class="text-lg lg:text-xl font-bold text-gray-800 dark:text-white tracking-tight">Audit Log Real-time</h2>
                    <p class="text-xs lg:text-sm text-gray-500 dark:text-gray-400 mt-1 max-w-4xl leading-relaxed">
                        Pantau setiap tindakan administrator dan sistem untuk keperluan audit keamanan dan pelacakan perubahan data operasional secara transparan.
                    </p>
                </div>

                {{-- Toolbar Responsive --}}
                <div class="flex flex-col lg:flex-row lg:items-center gap-3 mb-8">
                    {{-- Search Input --}}
                    <div class="relative flex-1">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 dark:text-gray-500">
                            <i class="fa-solid fa-magnifying-glass text-xs"></i>
                        </span>
                        <input type="text" x-model="search" @input.debounce.500ms="applyFilter()"
                            class="w-full pl-9 pr-4 py-2 border border-gray-200 dark:border-slate-700 rounded-lg bg-gray-50 dark:bg-slate-800 text-xs text-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all placeholder:dark:text-gray-500" 
                            placeholder="Cari aksi, detail, atau IP...">
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        {{-- Filter Aksi Popout --}}
                        <div x-data="{ open: false }" class="relative flex-1 lg:flex-none">
                            <button type="button" @click="open = !open" 
                                class="w-full flex items-center justify-center gap-2 px-4 py-2 border border-gray-200 dark:border-slate-700 rounded-lg text-[11px] font-bold text-gray-600 dark:text-white bg-gray-50 dark:bg-slate-800 hover:bg-blue-600 hover:text-white dark:hover:bg-blue-600 transition active:scale-95">
                                <i class="fa-solid fa-filter"></i> Filter Aksi
                            </button>
                            <div x-show="open" @click.outside="open = false" x-cloak
                                 class="absolute z-50 right-0 lg:left-0 mt-2 p-4 bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-xl shadow-xl min-w-[200px]">
                                <label class="block text-xs font-bold text-gray-700 dark:text-white mb-3 uppercase">Pilih Aksi</label>
                                <div class="space-y-2 mb-4">
                                    <template x-for="t in ['Create', 'Update', 'Delete', 'Download']">
                                        <label class="flex items-center gap-3 text-xs text-gray-600 dark:text-gray-300 cursor-pointer hover:text-blue-600">
                                            <input type="checkbox" :value="t" x-model="tipe" @change="applyFilter()"
                                                   class="rounded border-gray-300 dark:border-slate-700 text-blue-600 focus:ring-blue-500">
                                            <span x-text="t"></span>
                                        </label>
                                    </template>
                                </div>
                            </div>
                        </div>

                        {{-- Tanggal Popout --}}
                        <div x-data="{ open: false }" class="relative flex-1 lg:flex-none">
                            <button type="button" @click="open = !open" 
                                class="w-full flex items-center justify-center gap-2 px-4 py-2 border border-gray-200 dark:border-slate-700 rounded-lg text-[11px] font-bold text-gray-600 dark:text-white bg-gray-50 dark:bg-slate-800 hover:bg-blue-600 hover:text-white dark:hover:bg-blue-600 transition active:scale-95">
                                <i class="fa-regular fa-calendar"></i>
                                <span x-text="tanggal ? tanggal : 'Pilih Tanggal'"></span>
                            </button>
                            <div x-show="open" @click.outside="open = false" x-cloak
                                 class="absolute z-50 right-0 mt-2 p-4 bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-xl shadow-xl min-w-[250px]">
                                <label class="block text-xs font-bold text-gray-700 dark:text-white mb-2 uppercase">Pilih Tanggal</label>
                                <input type="date" x-model="tanggal" @change="applyFilter()"
                                    class="w-full px-3 py-2 border border-gray-200 dark:border-slate-700 rounded-lg text-xs text-gray-600 dark:text-white bg-gray-50 dark:bg-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:outline-none mb-4 transition-all">
                                <button type="button" @click="tanggal = ''; applyFilter(); open = false" class="w-full py-2 bg-gray-200 text-gray-700 rounded-lg text-[10px] font-bold hover:bg-gray-300 transition">
                                    Reset Date
                                </button>
                            </div>
                        </div>

                        {{-- Unduh Button --}}
                        <a :href="getExportUrl()" @click="showExportNotification()"
                           class="flex-1 lg:flex-none flex items-center justify-center gap-2 px-4 py-2 bg-slate-100 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg text-[11px] font-bold text-gray-600 dark:text-white hover:bg-blue-600 hover:text-white dark:hover:bg-blue-600 transition active:scale-95">
                            <i class="fa-solid fa-download"></i> Unduh Log
                        </a>
                    </div>
                </div>

                {{-- Table Wrapper --}}
                <div class="relative overflow-x-auto border border-gray-100 dark:border-slate-800 rounded-xl mb-6 transition-colors min-h-[300px]">
                    
                    {{-- Loading overlay --}}
                    <div x-show="isLoading" class="absolute inset-0 z-10 bg-white/50 dark:bg-slate-900/50 backdrop-blur-sm flex items-center justify-center">
                        <i class="fa-solid fa-circle-notch fa-spin text-3xl text-blue-500"></i>
                    </div>

                    <table class="w-full text-left text-xs min-w-[850px]">
                        <thead class="bg-gray-50/80 dark:bg-slate-800/50 text-gray-500 dark:text-white font-bold border-b dark:border-slate-800 transition-colors uppercase tracking-wider">
                            <tr>
                                <th class="py-4 px-6">Tanggal & Waktu</th>
                                <th class="py-4 px-4">Nama Pengguna</th>
                                <th class="py-4 px-4 text-center">Aksi</th>
                                <th class="py-4 px-4">Detail Aktivitas</th>
                                <th class="py-4 px-6 text-right">IP Address</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-slate-800 text-gray-700 dark:text-white transition-colors">
                            <template x-for="log in logs" :key="log.log_id">
                                <tr class="hover:bg-gray-50 dark:hover:bg-slate-800 transition duration-150">
                                    <td class="py-5 px-6 leading-relaxed whitespace-nowrap">
                                        <p class="font-bold text-gray-800 dark:text-white" x-text="formatDate(log.created_at)"></p>
                                        <p class="text-gray-400 dark:text-gray-500 font-mono text-[10px]" x-text="formatTime(log.created_at)"></p>
                                    </td>
                                    <td class="py-5 px-4 leading-tight min-w-[200px]">
                                        <p class="font-bold text-gray-800 dark:text-white" x-text="log.user ? log.user.nama : 'System'"></p>
                                        <p class="text-[10px] text-gray-400 dark:text-gray-500 italic truncate" x-text="(log.user?.role?.role_name || 'Staff') + ' • ' + (log.user?.nip || 'N/A')"></p>
                                    </td>
                                    <td class="py-5 px-4 text-center">
                                        <div class="inline-flex items-center gap-2 px-3 py-1.5 border rounded-lg font-bold text-[9px] uppercase tracking-tighter transition-colors"
                                             :class="getStyleInfo(log.tipe).colorClass">
                                            <i class="fa-solid text-[10px]" :class="getStyleInfo(log.tipe).icon"></i>
                                            <span x-text="getStyleInfo(log.tipe).label"></span>
                                        </div>
                                    </td>
                                    <td class="py-5 px-4 text-gray-500 dark:text-gray-300 max-w-xs leading-relaxed italic" x-text="getDetail(log)">
                                    </td>
                                    <td class="py-5 px-6 text-right text-gray-400 dark:text-gray-500 font-mono font-medium" x-text="log.ip_address || '0.0.0.0'">
                                    </td>
                                </tr>
                            </template>
                            
                            <template x-if="!isLoading && logs.length === 0">
                                <tr>
                                    <td colspan="5" class="py-12 text-center text-gray-400 dark:text-gray-500 italic font-medium transition-colors">
                                        <div class="flex flex-col items-center gap-2">
                                            <i class="fa-solid fa-inbox text-4xl opacity-20"></i>
                                            <p>Belum ada log aktivitas yang tercatat sesuai kriteria.</p>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                {{-- Pagination Responsive --}}
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4 transition-colors" x-show="pagination.total > 0">
                    <p class="text-[10px] text-gray-400 dark:text-gray-500 font-medium italic order-2 sm:order-1 uppercase tracking-widest">
                        Menampilkan <span class="text-gray-700 dark:text-white font-bold" x-text="(pagination.from || 0) + '-' + (pagination.to || 0)"></span> dari <span x-text="pagination.total"></span> log
                    </p>
                    
                    <div class="flex items-center gap-1 order-1 sm:order-2 custom-pagination">
                        <template x-for="(link, index) in pagination.links" :key="index">
                            <button @click="if(link.url) fetchLogs(link.url)" 
                                    x-html="link.label" 
                                    :disabled="!link.url"
                                    class="relative inline-flex items-center px-4 py-2 border text-sm font-medium rounded-md transition-colors"
                                    :class="{
                                        'bg-blue-50 dark:bg-blue-900 border-blue-500 text-blue-600 dark:text-white z-10': link.active,
                                        'bg-white dark:bg-slate-800 border-gray-300 dark:border-slate-700 text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-slate-700': !link.active && link.url,
                                        'bg-gray-100 dark:bg-slate-900 border-gray-200 dark:border-slate-800 text-gray-400 cursor-not-allowed': !link.url
                                    }">
                            </button>
                        </template>
                    </div>
                </div>
            </div>

            {{-- Footer Info Responsive --}}
            <div class="bg-white dark:bg-slate-900 rounded-xl border border-gray-100 dark:border-slate-800 p-6 flex items-start gap-4 shadow-sm mb-10 transition-all hover:shadow-md">
                <div class="w-10 h-10 bg-blue-50 dark:bg-blue-900/30 rounded-full flex items-center justify-center shrink-0 shadow-inner">
                    <i class="fa-solid fa-circle-info text-blue-600 dark:text-blue-400"></i>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-gray-800 dark:text-white transition-colors uppercase tracking-tight">Informasi Retensi Data</h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 leading-relaxed transition-colors">
                        Data log aktivitas disimpan otomatis selama <span class="font-bold text-gray-700 dark:text-white">365 hari</span> kerja. Untuk akses data historis lebih lama, silakan hubungi <span class="font-bold text-gray-700 dark:text-white underline decoration-blue-200">Tim IT Infrastruktur Rumah Sakit</span>.
                    </p>
                </div>
            </div>
        </main>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 10px; }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; }
    
    .custom-pagination button { font-size: 0.75rem; padding: 0.35rem 0.75rem; margin: 0 0.125rem; border-radius: 0.375rem; }
    .custom-pagination button svg { width: 1rem; height: 1rem; }
</style>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('logAktivitasData', () => ({
            sidebarOpen: false, 
            darkMode: localStorage.getItem('theme') === 'dark',
            search: '',
            tanggal: '',
            tipe: [],
            logs: [],
            pagination: { links: [], total: 0, from: 0, to: 0 },
            isLoading: false,

            init() {
                this.fetchLogs();
            },

            async fetchLogs(url = null) {
                this.isLoading = true;
                try {
                    const baseUrl = url || '/api/admin/log-aktivitas';
                    const urlObj = new URL(baseUrl, window.location.origin);
                    
                    if (this.search) urlObj.searchParams.set('search', this.search);
                    if (this.tanggal) urlObj.searchParams.set('tanggal', this.tanggal);
                    if (this.tipe.length > 0) urlObj.searchParams.set('tipe', this.tipe.join(','));

                    const response = await fetch(urlObj.toString(), {
                        headers: { 'Accept': 'application/json' }
                    });
                    const result = await response.json();
                    
                    if (result.success) {
                        this.logs = result.data.data;
                        this.pagination = result.data;
                    }
                } catch (error) {
                    console.error('Error fetching logs:', error);
                } finally {
                    this.isLoading = false;
                }
            },

            applyFilter() {
                this.fetchLogs();
            },

            formatDate(dateStr) {
                if (!dateStr) return '';
                const d = new Date(dateStr);
                return d.toLocaleDateString('id-ID', {day: '2-digit', month: '2-digit', year: 'numeric'}).replace(/\//g, '-');
            },

            formatTime(dateStr) {
                if (!dateStr) return '';
                const d = new Date(dateStr);
                return d.toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit', second: '2-digit'});
            },

            getDetail(log) {
                let detail = log.perubahan;
                if (log.tipe === 'Update' && detail.includes('|')) {
                    const parts = detail.split('|');
                    if (parts.length === 2) {
                        detail = "Mengubah " + log.tabel + ": \"" + parts[0] + "\" menjadi \"" + parts[1] + "\"";
                    }
                }
                return detail;
            },

            getStyleInfo(tipe) {
                switch(tipe) {
                    case 'Create':
                        return { icon: 'fa-user-plus', label: 'Tambah', colorClass: 'bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 border-blue-100 dark:border-blue-900/50' };
                    case 'Update':
                        return { icon: 'fa-pen-to-square', label: 'Ubah', colorClass: 'bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 border-amber-100 dark:border-amber-900/50' };
                    case 'Delete':
                        return { icon: 'fa-trash-can', label: 'Hapus', colorClass: 'bg-red-500 text-white border-red-500 dark:bg-red-900/50 dark:text-red-200 dark:border-red-900' };
                    case 'Download':
                        return { icon: 'fa-file-export', label: 'Ekspor', colorClass: 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 border-indigo-100 dark:border-indigo-900/50' };
                    default:
                        return { icon: 'fa-circle-info', label: tipe, colorClass: 'border-gray-200 dark:border-slate-700 text-gray-600 dark:text-gray-300' };
                }
            },

            getExportUrl() {
                const urlObj = new URL('/log-aktivitas/export', window.location.origin);
                if (this.search) urlObj.searchParams.set('search', this.search);
                if (this.tanggal) urlObj.searchParams.set('tanggal', this.tanggal);
                if (this.tipe.length > 0) urlObj.searchParams.set('tipe', this.tipe.join(','));
                return urlObj.toString();
            }
        }));
    });
</script>
@endsection