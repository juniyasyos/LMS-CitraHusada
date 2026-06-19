@extends('components.layout')
@section('title', 'Cadangan')
@section('content')

{{-- Menambahkan state untuk dropdown pengaturan, modal reset, mode hapus, dan toggle status --}}
<div class="flex h-screen overflow-hidden bg-slate-50 dark:bg-slate-950 transition-colors duration-300" 
    x-data="backupData()"
    x-init="initData()">
    
    {{-- Script SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <aside id="sidebar"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-slate-900 border-r dark:border-slate-800 transition-transform duration-300 lg:translate-x-0 lg:static lg:inset-0 shrink-0 overflow-y-auto">
        @include('components.nav-superadmin')
    </aside>

    <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-black/50 backdrop-blur-sm lg:hidden" x-cloak></div>

    <div class="flex-1 flex flex-col min-w-0 transition-colors duration-300">
        
        @include('components.header-superadmin', ['title' => 'Cadangan'])

        <main class="flex-1 overflow-y-auto p-4 lg:p-8 custom-scrollbar">
            <div class="flex flex-col sm:flex-row justify-between items-start gap-4 mb-6">
                <div>
                    <h2 class="text-lg lg:text-xl font-bold text-gray-800 dark:text-white transition-colors">Cadangan Sistem</h2>
                    <p class="text-xs lg:text-sm text-gray-500 dark:text-gray-300 mt-1 transition-colors leading-relaxed">Lakukan pencadangan basis data secara berkala untuk menjaga integritas data.</p>
                </div>
                <div class="flex flex-wrap gap-2 w-full sm:w-auto relative">
                    <button @click="runBackup()" 
                        :disabled="isBackingUp"
                        :class="isBackingUp ? 'opacity-70 cursor-not-allowed' : ''"
                        class="flex-1 sm:flex-none bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg flex items-center justify-center gap-2 text-xs font-bold transition shadow-sm active:scale-95 shadow-blue-100 dark:shadow-none">
                        <template x-if="!isBackingUp">
                            <span><i class="fa-solid fa-cloud-arrow-up text-[10px]"></i> Lakukan Pencadangan Sekarang</span>
                        </template>
                        <template x-if="isBackingUp">
                            <span class="flex items-center gap-2">
                                <i class="fa-solid fa-spinner fa-spin text-[10px]"></i>
                                <span x-text="backupElapsedText"></span>
                            </span>
                        </template>
                    </button>
                    
                    {{-- Dropdown Pengaturan Cadangan --}}
                    <div class="relative flex-1 sm:flex-none">
                        <button @click="openPengaturan = !openPengaturan" class="w-full bg-white dark:bg-slate-800 hover:bg-gray-50 dark:hover:bg-slate-700 text-gray-600 dark:text-white border border-gray-200 dark:border-slate-700 px-5 py-2.5 rounded-lg flex items-center justify-center gap-2 text-xs font-bold transition shadow-sm active:scale-95">
                            <i class="fa-solid fa-gear text-[10px]"></i>
                            Pengaturan Cadangan
                        </button>

                        {{-- Kotak Menggantung (Dropdown) --}}
                        <div x-show="openPengaturan" @click.away="openPengaturan = false" x-cloak x-transition
                            class="absolute right-0 mt-2 w-72 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl shadow-xl z-50 p-5 text-left">
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-gray-700 dark:text-white uppercase tracking-tight">Pencadangan Otomatis</span>
                                    {{-- Toggle Switch --}}
                                    <button @click="backupActive = !backupActive" 
                                        :class="backupActive ? 'bg-blue-600' : 'bg-gray-300 dark:bg-slate-600'"
                                        class="relative inline-flex h-5 w-10 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out">
                                        <span :class="backupActive ? 'translate-x-5' : 'translate-x-0'"
                                            class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                                    </button>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase">Frekuensi</label>
                                    <select x-model="frequency" class="w-full bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-lg p-2 text-xs dark:text-white outline-none focus:ring-1 focus:ring-blue-500">
                                        <option value="daily">Harian</option>
                                        <option value="weekly">Mingguan</option>
                                        <option value="monthly">Bulanan</option>
                                        <option value="yearly">Tahunan</option>
                                    </select>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase">Waktu Eksekusi</label>
                                    <input type="time" x-model="executionTime" class="w-full bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-lg p-2 text-xs dark:text-white outline-none focus:ring-1 focus:ring-blue-500">
                                </div>
                                <button @click="saveSettings()" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg text-xs font-bold transition">Atur Pencadangan</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 lg:gap-6 mb-8">
                <div class="bg-white dark:bg-slate-900 p-4 lg:p-5 rounded-xl border border-gray-100 dark:border-slate-800 shadow-sm transition-all hover:shadow-md">
                    <div class="flex items-center gap-2 lg:gap-3 text-gray-400 dark:text-gray-300 mb-2 transition-colors">
                        <i class="fa-solid fa-clock text-xs"></i>
                        <span class="text-[9px] lg:text-[11px] font-bold uppercase tracking-wider">Total Riwayat</span>
                    </div>
                    <p class="text-lg lg:text-xl font-bold text-gray-800 dark:text-white transition-colors" x-text="stats.total"><i class="fa-solid fa-spinner fa-spin text-sm"></i></p>
                </div>
                <div class="bg-white dark:bg-slate-900 p-4 lg:p-5 rounded-xl border border-gray-100 dark:border-slate-800 shadow-sm transition-all hover:shadow-md">
                    <div class="flex items-center gap-2 lg:gap-3 text-gray-400 dark:text-gray-300 mb-2 transition-colors">
                        <i class="fa-solid fa-circle-check text-xs"></i>
                        <span class="text-[9px] lg:text-[11px] font-bold uppercase tracking-wider">Berhasil</span>
                    </div>
                    <p class="text-lg lg:text-xl font-bold text-emerald-600 dark:text-emerald-400 transition-colors" x-text="stats.success"><i class="fa-solid fa-spinner fa-spin text-sm"></i></p>
                </div>
                <div class="bg-white dark:bg-slate-900 p-4 lg:p-5 rounded-xl border border-gray-100 dark:border-slate-800 shadow-sm transition-all hover:shadow-md">
                    <div class="flex items-center gap-2 lg:gap-3 text-gray-400 dark:text-gray-300 mb-2 transition-colors">
                        <i class="fa-solid fa-circle-xmark text-xs"></i>
                        <span class="text-[9px] lg:text-[11px] font-bold uppercase tracking-wider">Gagal</span>
                    </div>
                    <p class="text-lg lg:text-xl font-bold text-red-600 dark:text-red-400 transition-colors" x-text="stats.failed"><i class="fa-solid fa-spinner fa-spin text-sm"></i></p>
                </div>
                <div class="bg-white dark:bg-slate-900 p-4 lg:p-5 rounded-xl border border-gray-100 dark:border-slate-800 shadow-sm transition-all hover:shadow-md">
                    <div class="flex items-center gap-2 lg:gap-3 text-gray-400 dark:text-gray-300 mb-2 transition-colors">
                        <i class="fa-solid fa-database text-xs"></i>
                        <span class="text-[9px] lg:text-[11px] font-bold uppercase tracking-wider">Sisa Disk</span>
                    </div>
                    <p class="text-sm font-bold text-gray-800 dark:text-white transition-colors truncate" x-text="stats.free_space"><i class="fa-solid fa-spinner fa-spin text-sm"></i></p>
                </div>
            </div>

            {{-- ============================================ --}}
            {{-- TABEL DAFTAR CADANGAN (BACKUP LOG) --}}
            {{-- ============================================ --}}
            <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 min-h-[400px] flex flex-col mb-10 transition-colors duration-300 overflow-hidden">
                
                <div class="p-6 border-b dark:border-slate-800 flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-lg flex items-center justify-center">
                            <i class="fa-solid fa-database text-sm"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800 dark:text-white transition-colors">Daftar Cadangan</h3>
                            <p class="text-[10px] text-gray-400 dark:text-gray-500">Pantau dan kelola tugas cadangan Anda secara real-time.</p>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button @click="openModalReset = true" class="flex items-center gap-2 px-3 py-1.5 border-2 border-red-400 text-red-500 bg-red-50 dark:bg-red-950/20 rounded-lg text-[10px] lg:text-[11px] font-bold hover:bg-red-100 transition active:scale-95">
                            <i class="fa-solid fa-rotate-left"></i> Reset
                        </button>
                        {{-- Tombol Bersihkan mengubah mode seleksi --}}
                        <button @click="isSelectionMode = !isSelectionMode; if(!isSelectionMode) selectedIds = []" :class="isSelectionMode ? 'bg-amber-500 text-white border-amber-500' : 'border-2 border-amber-300 text-amber-600 bg-amber-50 dark:bg-amber-950/20'" class="flex items-center gap-2 px-3 py-1.5 rounded-lg text-[10px] lg:text-[11px] font-bold transition active:scale-95">
                            <i class="fa-solid fa-trash-can"></i> <span x-text="isSelectionMode ? 'Batal Pilih' : 'Bersihkan'"></span>
                        </button>
                        <button @click="fetchData()" class="flex items-center gap-2 px-3 py-1.5 border-2 border-emerald-400 text-emerald-600 bg-emerald-50 dark:bg-emerald-950/20 rounded-lg text-[10px] lg:text-[11px] font-bold hover:bg-emerald-100 transition active:scale-95">
                            <i class="fa-solid fa-arrows-rotate" :class="isLoading ? 'fa-spin' : ''"></i> Segarkan
                        </button>
                    </div>
                </div>

                <div class="flex-1 transition-colors relative overflow-x-auto">
                    <template x-if="isLoading">
                        <div class="flex flex-col items-center justify-center p-8 lg:p-12 h-full">
                            <i class="fa-solid fa-spinner fa-spin text-3xl text-blue-500 mb-4"></i>
                            <p class="text-xs text-gray-500">Memuat data...</p>
                        </div>
                    </template>
                    <template x-if="!isLoading && logs.length === 0">
                        <div class="flex flex-col items-center justify-center p-8 lg:p-12 h-full">
                            <div class="text-gray-200 dark:text-gray-700 mb-4 transition-colors">
                                <i class="fa-solid fa-folder-open text-7xl lg:text-8xl"></i>
                            </div>
                            <p class="text-xs lg:text-sm text-gray-400 dark:text-gray-400 font-medium tracking-wide transition-colors">Belum ada riwayat cadangan</p>
                        </div>
                    </template>
                    <template x-if="!isLoading && logs.length > 0">
                        <table class="w-full text-left border-collapse min-w-[600px]">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-slate-800/50 text-[10px] lg:text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                                    <th x-show="isSelectionMode" class="px-6 py-4 w-10" x-transition>
                                        <input type="checkbox" @change="if($el.checked) selectedIds = logs.map(l => l.id); else selectedIds = []" class="rounded dark:bg-slate-900 border-gray-300 dark:border-slate-700 text-blue-600">
                                    </th>
                                    <th class="px-6 py-4">Nama File</th>
                                    <th class="px-6 py-4">Tanggal</th>
                                    <th class="px-6 py-4">Ukuran</th>
                                    <th class="px-6 py-4 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y dark:divide-slate-800">
                                <template x-for="log in logs" :key="log.id">
                                    <tr class="text-xs text-gray-600 dark:text-gray-300 hover:bg-gray-50/50 dark:hover:bg-slate-800/30 transition-colors">
                                        <td x-show="isSelectionMode" class="px-6 py-4" x-transition>
                                            <input type="checkbox" :value="log.id" x-model="selectedIds" class="rounded dark:bg-slate-900 border-gray-300 dark:border-slate-700 text-blue-600">
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <i class="fa-solid fa-file-zipper text-blue-500"></i>
                                                <span class="font-medium max-w-[200px] truncate" x-text="log.filename ? log.filename.split('/').pop() : 'N/A'"></span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 tabular-nums text-gray-400 dark:text-gray-500" x-text="log.date"></td>
                                        <td class="px-6 py-4 font-medium" x-text="log.size ? (log.size / 1024 / 1024).toFixed(2) + ' MB' : '-'"></td>
                                        <td class="px-6 py-4 text-center">
                                            <template x-if="log.status === 'success'">
                                                <span class="px-2 py-1 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded-full text-[10px] font-bold uppercase">Berhasil</span>
                                            </template>
                                            <template x-if="log.status === 'in_progress'">
                                                <span class="px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-full text-[10px] font-bold uppercase"><i class="fa-solid fa-spinner fa-spin mr-1"></i>Proses</span>
                                            </template>
                                            <template x-if="log.status === 'failed'">
                                                <span class="px-2 py-1 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-full text-[10px] font-bold uppercase">Gagal</span>
                                            </template>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </template>
                </div>

                {{-- Footer tombol hapus saat mode seleksi --}}
                <div x-show="isSelectionMode && selectedIds.length > 0" x-cloak x-transition class="p-4 bg-gray-100 dark:bg-slate-800 border-t dark:border-slate-700 flex justify-between items-center">
                    <span class="text-xs font-bold text-gray-500 dark:text-gray-400">Terpilih: <span x-text="selectedIds.length"></span> data</span>
                    <button @click="deleteSelected()" class="bg-red-600 text-white px-6 py-2 rounded-lg text-xs font-bold shadow-lg shadow-red-200 dark:shadow-none hover:bg-red-700 active:scale-95 transition">
                        Hapus Data Terpilih
                    </button>
                </div>
            </div>

            {{-- ============================================ --}}
            {{-- TABEL RESTORE BACKUP --}}
            {{-- ============================================ --}}
            <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 min-h-[300px] flex flex-col mb-10 transition-colors duration-300 overflow-hidden">
                <div class="p-6 border-b dark:border-slate-800 flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-orange-100 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400 rounded-lg flex items-center justify-center">
                            <i class="fa-solid fa-clock-rotate-left text-sm"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800 dark:text-white transition-colors">Restore Backup</h3>
                            <p class="text-[10px] text-gray-400 dark:text-gray-500">Pilih file backup untuk mengembalikan sistem ke kondisi sebelumnya</p>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button @click="showRestoreLogsModal = true; fetchRestoreLogs()" class="flex items-center gap-2 px-3 py-1.5 border-2 border-purple-400 text-purple-600 bg-purple-50 dark:bg-purple-950/20 rounded-lg text-[10px] lg:text-[11px] font-bold hover:bg-purple-100 transition active:scale-95">
                            <i class="fa-solid fa-list-check"></i> Riwayat Restore
                        </button>
                        <button @click="fetchBackupFiles()" class="flex items-center gap-2 px-3 py-1.5 border-2 border-emerald-400 text-emerald-600 bg-emerald-50 dark:bg-emerald-950/20 rounded-lg text-[10px] lg:text-[11px] font-bold hover:bg-emerald-100 transition active:scale-95">
                            <i class="fa-solid fa-arrows-rotate" :class="isLoadingBackupFiles ? 'fa-spin' : ''"></i> Segarkan
                        </button>
                    </div>
                </div>

                <div class="flex-1 transition-colors relative overflow-x-auto">
                    <template x-if="isLoadingBackupFiles">
                        <div class="flex flex-col items-center justify-center p-8 lg:p-12 h-full">
                            <i class="fa-solid fa-spinner fa-spin text-3xl text-orange-500 mb-4"></i>
                            <p class="text-xs text-gray-500">Memuat file backup...</p>
                        </div>
                    </template>
                    <template x-if="!isLoadingBackupFiles && backupFiles.length === 0">
                        <div class="flex flex-col items-center justify-center p-8 lg:p-12 h-full">
                            <div class="text-gray-200 dark:text-gray-700 mb-4 transition-colors">
                                <i class="fa-solid fa-box-open text-7xl lg:text-8xl"></i>
                            </div>
                            <p class="text-xs lg:text-sm text-gray-400 dark:text-gray-400 font-medium tracking-wide transition-colors">Belum ada file backup yang tersedia</p>
                        </div>
                    </template>
                    <template x-if="!isLoadingBackupFiles && backupFiles.length > 0">
                        <table class="w-full text-left border-collapse min-w-[700px]">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-slate-800/50 text-[10px] lg:text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                                    <th class="px-6 py-4">Nama File</th>
                                    <th class="px-6 py-4">Tanggal</th>
                                    <th class="px-6 py-4">Ukuran</th>
                                    <th class="px-6 py-4 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y dark:divide-slate-800">
                                <template x-for="file in backupFiles" :key="file.path">
                                    <tr class="text-xs text-gray-600 dark:text-gray-300 hover:bg-gray-50/50 dark:hover:bg-slate-800/30 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <i class="fa-solid fa-file-zipper text-orange-500"></i>
                                                <span class="font-medium" x-text="file.filename"></span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div>
                                                <span x-text="file.date"></span>
                                                {{-- <span class="block text-[10px] text-gray-400" x-text="file.time_ago"></span> --}}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 font-medium" x-text="file.size_formatted"></td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <a :href="'/api/admin/restore/download?file=' + encodeURIComponent(file.path)"
                                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 dark:bg-blue-950/20 text-blue-600 dark:text-blue-400 border border-blue-200 dark:border-blue-800 rounded-lg text-[10px] font-bold hover:bg-blue-100 dark:hover:bg-blue-900/30 transition active:scale-95">
                                                    <i class="fa-solid fa-download"></i> Download
                                                </a>
                                                <button @click="openRestoreModal(file)"
                                                    :disabled="isRestoring"
                                                    :class="isRestoring ? 'opacity-50 cursor-not-allowed' : ''"
                                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-orange-50 dark:bg-orange-950/20 text-orange-600 dark:text-orange-400 border border-orange-200 dark:border-orange-800 rounded-lg text-[10px] font-bold hover:bg-orange-100 dark:hover:bg-orange-900/30 transition active:scale-95">
                                                    <i class="fa-solid fa-clock-rotate-left"></i> Restore
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </template>
                </div>
            </div>

        </main>
    </div>

    {{-- ============================================ --}}
    {{-- MODAL KONFIRMASI RESET --}}
    {{-- ============================================ --}}
    <div x-show="openModalReset" class="fixed inset-0 z-100 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" x-cloak x-transition>
        <div class="bg-white dark:bg-slate-900 w-full max-w-sm rounded-2xl shadow-2xl p-6 text-center border dark:border-slate-800">
            <div class="w-16 h-16 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-triangle-exclamation text-2xl"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-2">Konfirmasi Reset</h3>
            <p class="text-xs text-gray-500 dark:text-gray-400 mb-6 leading-relaxed">Apakah Anda yakin ingin melakukan reset data cadangan? Seluruh file backup fisik dan riwayat log akan dihapus secara permanen.</p>
            <div class="flex gap-3">
                <button @click="openModalReset = false" class="flex-1 py-2 text-xs font-bold text-gray-500 hover:bg-gray-100 dark:hover:bg-slate-800 rounded-lg transition">Batal</button>
                <button @click="confirmReset()" class="flex-1 py-2 bg-red-600 text-white text-xs font-bold rounded-lg hover:bg-red-700 transition">Ya, Reset Data</button>
            </div>
        </div>
    </div>

    {{-- ============================================ --}}
    {{-- MODAL KONFIRMASI RESTORE --}}
    {{-- ============================================ --}}
    <div x-show="showRestoreModal" class="fixed inset-0 z-100 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" x-cloak x-transition>
        <div class="bg-white dark:bg-slate-900 w-full max-w-md rounded-2xl shadow-2xl border dark:border-slate-800 overflow-hidden">
            {{-- Header --}}
            <div class="bg-gradient-to-r from-orange-500 to-amber-500 p-5 text-white">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                        <i class="fa-solid fa-triangle-exclamation text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold">Konfirmasi Restore</h3>
                        <p class="text-white/80 text-[10px]">Tindakan ini tidak dapat dibatalkan</p>
                    </div>
                </div>
            </div>

            {{-- Body --}}
            <div class="p-6">
                {{-- Info file yang akan direstore --}}
                <div class="bg-orange-50 dark:bg-orange-950/20 border border-orange-200 dark:border-orange-800 rounded-xl p-4 mb-4">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fa-solid fa-file-zipper text-orange-500 text-sm"></i>
                        <span class="text-xs font-bold text-orange-700 dark:text-orange-400" x-text="restoreTarget ? restoreTarget.filename : ''"></span>
                    </div>
                    <div class="grid grid-cols-2 gap-2 text-[10px]">
                        <div class="text-gray-500 dark:text-gray-400">Ukuran: <span class="font-bold text-gray-700 dark:text-gray-300" x-text="restoreTarget ? restoreTarget.size_formatted : ''"></span></div>
                        <div class="text-gray-500 dark:text-gray-400">Tanggal: <span class="font-bold text-gray-700 dark:text-gray-300" x-text="restoreTarget ? restoreTarget.date : ''"></span></div>
                    </div>
                </div>

                {{-- Peringatan --}}
                <div class="bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-800 rounded-xl p-4 mb-4">
                    <div class="flex items-start gap-2">
                        <i class="fa-solid fa-exclamation-circle text-red-500 mt-0.5"></i>
                        <div class="text-xs text-red-700 dark:text-red-400 leading-relaxed">
                            <p class="font-bold mb-1">PERINGATAN</p>
                            <p>Restore akan mengganti:</p>
                            <ul class="list-disc ml-4 mt-1 space-y-0.5">
                                <li>Database saat ini</li>
                                <li>File storage saat ini</li>
                            </ul>
                            <p class="mt-2 font-semibold">Data terbaru setelah tanggal backup akan hilang.</p>
                        </div>
                    </div>
                </div>

                {{-- Info Backup Otomatis --}}
                <div class="bg-blue-50 dark:bg-blue-950/20 border border-blue-200 dark:border-blue-800 rounded-xl p-3 mb-4">
                    <div class="flex items-center gap-2 text-xs text-blue-700 dark:text-blue-400">
                        <i class="fa-solid fa-shield-halved"></i>
                        <span>Backup otomatis akan dibuat sebelum restore dimulai untuk keamanan.</span>
                    </div>
                </div>

                {{-- Input Password --}}
                <div class="mb-5">
                    <label class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider block mb-2">
                        <i class="fa-solid fa-lock mr-1"></i> Konfirmasi Password Anda
                    </label>
                    <input type="password" x-model="restorePassword" 
                        @keydown.enter="confirmRestore()"
                        placeholder="Masukkan password akun Anda"
                        class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg px-4 py-2.5 text-xs dark:text-white outline-none focus:ring-2 focus:ring-orange-500/30 focus:border-orange-500 transition placeholder:text-gray-400">
                    <template x-if="restoreErrors.password">
                        <p class="text-red-500 text-[10px] mt-1" x-text="restoreErrors.password"></p>
                    </template>
                </div>

                {{-- Tombol --}}
                <div class="flex gap-3">
                    <button @click="closeRestoreModal()" 
                        :disabled="isRestoring"
                        class="flex-1 py-2.5 text-xs font-bold text-gray-500 hover:bg-gray-100 dark:hover:bg-slate-800 rounded-lg transition border border-gray-200 dark:border-slate-700">
                        Batal
                    </button>
                    <button @click="confirmRestore()" 
                        :disabled="isRestoring || !restorePassword"
                        :class="(isRestoring || !restorePassword) ? 'opacity-50 cursor-not-allowed' : 'hover:from-orange-600 hover:to-red-600 active:scale-95'"
                        class="flex-1 py-2.5 bg-gradient-to-r from-orange-500 to-red-500 text-white text-xs font-bold rounded-lg transition shadow-lg shadow-orange-200 dark:shadow-none">
                        <template x-if="!isRestoring">
                            <span><i class="fa-solid fa-clock-rotate-left mr-1"></i> Ya, Restore</span>
                        </template>
                        <template x-if="isRestoring">
                            <span><i class="fa-solid fa-spinner fa-spin mr-1"></i> Memproses...</span>
                        </template>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================ --}}
    {{-- MODAL PROSES RESTORE (PROGRESS) --}}
    {{-- ============================================ --}}
    <div x-show="showRestoreProgressModal" class="fixed inset-0 z-100 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" x-cloak x-transition>
        <div class="bg-white dark:bg-slate-900 w-full max-w-sm rounded-2xl shadow-2xl p-6 text-center border dark:border-slate-800">
            <template x-if="restoreProgressStatus === 'processing'">
                <div>
                    <div class="w-20 h-20 mx-auto mb-4 relative">
                        <div class="w-20 h-20 border-4 border-orange-200 dark:border-orange-900/30 rounded-full"></div>
                        <div class="w-20 h-20 border-4 border-orange-500 border-t-transparent rounded-full absolute top-0 left-0 animate-spin"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <i class="fa-solid fa-database text-orange-500 text-xl"></i>
                        </div>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-2">Sedang Memproses Restore</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed mb-4">Mohon tunggu dan jangan menutup halaman ini. Proses ini dapat memakan waktu beberapa menit.</p>
                    <div class="bg-gray-50 dark:bg-slate-800 rounded-lg p-3">
                        <p class="text-[10px] text-gray-400 dark:text-gray-500 uppercase font-bold mb-1">Status</p>
                        <p class="text-xs text-orange-600 dark:text-orange-400 font-medium" x-text="restoreProgressMessage"></p>
                    </div>
                </div>
            </template>
            <template x-if="restoreProgressStatus === 'success'">
                <div>
                    <div class="w-20 h-20 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fa-solid fa-check text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-2">Restore Berhasil!</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed mb-4" x-text="restoreProgressMessage"></p>
                    <button @click="finishRestore()" class="w-full py-2.5 bg-emerald-600 text-white text-xs font-bold rounded-lg hover:bg-emerald-700 transition">
                        <i class="fa-solid fa-arrows-rotate mr-1"></i> Muat Ulang Halaman
                    </button>
                </div>
            </template>
            <template x-if="restoreProgressStatus === 'failed'">
                <div>
                    <div class="w-20 h-20 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fa-solid fa-xmark text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-2">Restore Gagal</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed mb-4" x-text="restoreProgressMessage"></p>
                    <button @click="showRestoreProgressModal = false" class="w-full py-2.5 bg-gray-600 text-white text-xs font-bold rounded-lg hover:bg-gray-700 transition">
                        Tutup
                    </button>
                </div>
            </template>
        </div>
    </div>

    {{-- ============================================ --}}
    {{-- MODAL RIWAYAT RESTORE --}}
    {{-- ============================================ --}}
    <div x-show="showRestoreLogsModal" class="fixed inset-0 z-100 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" x-cloak x-transition>
        <div class="bg-white dark:bg-slate-900 w-full max-w-3xl max-h-[80vh] rounded-2xl shadow-2xl border dark:border-slate-800 flex flex-col overflow-hidden">
            {{-- Header --}}
            <div class="p-5 border-b dark:border-slate-800 flex items-center justify-between shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-list-check text-sm"></i>
                    </div>
                    <h3 class="font-bold text-gray-800 dark:text-white">Riwayat Restore</h3>
                </div>
                <button @click="showRestoreLogsModal = false" class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-gray-600 dark:hover:text-white rounded-lg hover:bg-gray-100 dark:hover:bg-slate-800 transition">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            {{-- Stats --}}
            <div class="grid grid-cols-4 gap-3 p-5 border-b dark:border-slate-800 shrink-0">
                <div class="bg-gray-50 dark:bg-slate-800 rounded-lg p-3 text-center">
                    <p class="text-[10px] font-bold text-gray-400 uppercase">Total</p>
                    <p class="text-lg font-bold text-gray-800 dark:text-white" x-text="restoreStats.total"></p>
                </div>
                <div class="bg-emerald-50 dark:bg-emerald-950/20 rounded-lg p-3 text-center">
                    <p class="text-[10px] font-bold text-emerald-500 uppercase">Berhasil</p>
                    <p class="text-lg font-bold text-emerald-600 dark:text-emerald-400" x-text="restoreStats.success"></p>
                </div>
                <div class="bg-red-50 dark:bg-red-950/20 rounded-lg p-3 text-center">
                    <p class="text-[10px] font-bold text-red-500 uppercase">Gagal</p>
                    <p class="text-lg font-bold text-red-600 dark:text-red-400" x-text="restoreStats.failed"></p>
                </div>
                <div class="bg-amber-50 dark:bg-amber-950/20 rounded-lg p-3 text-center">
                    <p class="text-[10px] font-bold text-amber-500 uppercase">Rollback</p>
                    <p class="text-lg font-bold text-amber-600 dark:text-amber-400" x-text="restoreStats.rolled_back"></p>
                </div>
            </div>

            {{-- Table --}}
            <div class="flex-1 overflow-y-auto custom-scrollbar">
                <template x-if="isLoadingRestoreLogs">
                    <div class="flex flex-col items-center justify-center p-8">
                        <i class="fa-solid fa-spinner fa-spin text-2xl text-purple-500 mb-3"></i>
                        <p class="text-xs text-gray-500">Memuat riwayat...</p>
                    </div>
                </template>
                <template x-if="!isLoadingRestoreLogs && restoreLogs.length === 0">
                    <div class="flex flex-col items-center justify-center p-8">
                        <i class="fa-solid fa-inbox text-5xl text-gray-200 dark:text-gray-700 mb-3"></i>
                        <p class="text-xs text-gray-400">Belum ada riwayat restore</p>
                    </div>
                </template>
                <template x-if="!isLoadingRestoreLogs && restoreLogs.length > 0">
                    <table class="w-full text-left border-collapse min-w-[600px]">
                        <thead class="sticky top-0 bg-white dark:bg-slate-900">
                            <tr class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider border-b dark:border-slate-800">
                                <th class="px-5 py-3">File Backup</th>
                                <th class="px-5 py-3">Oleh</th>
                                <th class="px-5 py-3">Status</th>
                                <th class="px-5 py-3">Durasi</th>
                                <th class="px-5 py-3">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y dark:divide-slate-800">
                            <template x-for="rlog in restoreLogs" :key="rlog.id">
                                <tr class="text-xs text-gray-600 dark:text-gray-300 hover:bg-gray-50/50 dark:hover:bg-slate-800/30 transition-colors cursor-pointer"
                                    @click="rlog._expanded = !rlog._expanded">
                                    <td class="px-5 py-3 font-medium" x-text="rlog.backup_file"></td>
                                    <td class="px-5 py-3" x-text="rlog.restored_by"></td>
                                    <td class="px-5 py-3">
                                        <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase"
                                            :class="{
                                                'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400': rlog.status === 'success',
                                                'bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400': rlog.status === 'failed',
                                                'bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400': rlog.status === 'rolled_back',
                                                'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400': rlog.status === 'in_progress',
                                            }"
                                            x-text="rlog.status === 'success' ? 'Berhasil' : rlog.status === 'failed' ? 'Gagal' : rlog.status === 'rolled_back' ? 'Rollback' : 'Proses'"></span>
                                    </td>
                                    <td class="px-5 py-3 tabular-nums" x-text="rlog.duration"></td>
                                    <td class="px-5 py-3 tabular-nums text-gray-400 dark:text-gray-500" x-text="rlog.date"></td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </template>
            </div>
        </div>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 10px; }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; }
    [x-cloak] { display: none !important; }
</style>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('backupData', () => ({
        sidebarOpen: false, 
        darkMode: localStorage.getItem('theme') === 'dark',
        openPengaturan: false,
        openModalReset: false,
        isSelectionMode: false,
        backupActive: false,
        frequency: 'daily',
        executionTime: '00:00',
        selectedIds: [],
        isBackingUp: false,
        backupElapsedText: 'Mencadangkan...',
        backupElapsedTimer: null,
        backupStartTime: null,
        isLoading: true,
        logs: [],
        stats: { total: 0, success: 0, failed: 0, free_space: '0 B' },
        searchQuery: '',

        // === Restore State ===
        backupFiles: [],
        isLoadingBackupFiles: true,
        restoreTarget: null,
        restorePassword: '',
        restoreErrors: {},
        showRestoreModal: false,
        showRestoreProgressModal: false,
        restoreProgressStatus: 'processing', // processing, success, failed
        restoreProgressMessage: 'Mempersiapkan proses restore...',
        isRestoring: false,
        
        // === Restore Logs State ===
        restoreLogs: [],
        restoreStats: { total: 0, success: 0, failed: 0, rolled_back: 0 },
        isLoadingRestoreLogs: false,
        showRestoreLogsModal: false,

        async initData() {
            await Promise.all([
                this.fetchData(),
                this.fetchBackupFiles(),
            ]);
        },

        // ============================================
        // BACKUP FUNCTIONS (EXISTING)
        // ============================================

        async fetchData() {
            this.isLoading = true;
            try {
                const url = new URL('/api/admin/backup/data', window.location.origin);
                if (this.searchQuery) url.searchParams.append('search', this.searchQuery);
                
                const response = await fetch(url.toString(), {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await response.json();
                this.logs = data.logs || [];
                this.stats = data.stats || { total: 0, success: 0, failed: 0, free_space: '0 B' };
                if (data.settings) {
                    this.backupActive = !!data.settings.is_active;
                    this.frequency = data.settings.frequency;
                    this.executionTime = data.settings.execution_time;
                }
            } catch (error) {
                console.error(error);
            } finally {
                this.isLoading = false;
            }
        },

        startElapsedTimer() {
            this.backupStartTime = Date.now();
            this.backupElapsedText = 'Mencadangkan... 00:00';
            this.backupElapsedTimer = setInterval(() => {
                const elapsed = Math.floor((Date.now() - this.backupStartTime) / 1000);
                const mins = String(Math.floor(elapsed / 60)).padStart(2, '0');
                const secs = String(elapsed % 60).padStart(2, '0');
                this.backupElapsedText = `Mencadangkan... ${mins}:${secs}`;
            }, 1000);
        },

        stopElapsedTimer() {
            if (this.backupElapsedTimer) {
                clearInterval(this.backupElapsedTimer);
                this.backupElapsedTimer = null;
            }
            this.backupElapsedText = 'Mencadangkan...';
        },

        async runBackup() {
            if (this.isBackingUp) return;
            
            const confirm = await Swal.fire({
                title: 'Mulai Pencadangan?',
                text: 'Proses pencadangan akan dimulai. Ini mungkin memakan waktu beberapa menit.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2563EB',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Ya, Mulai!',
                cancelButtonText: 'Batal'
            });

            if (!confirm.isConfirmed) return;

            this.isBackingUp = true;
            this.startElapsedTimer();
            
            try {
                const response = await fetch('/api/admin/backup/run', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'Accept': 'application/json'
                    }
                });
                const data = await response.json();
                
                if (data.status === 'success') {
                    const sizeMB = data.data && data.data.size ? (data.data.size / 1024 / 1024).toFixed(2) + ' MB' : '';
                    Swal.fire({
                        icon: 'success',
                        title: 'Pencadangan Berhasil!',
                        html: `<p class="text-sm text-gray-600">${data.message}</p>` +
                              (sizeMB ? `<p class="text-xs text-gray-400 mt-2">Ukuran file: <strong>${sizeMB}</strong></p>` : ''),
                        confirmButtonColor: '#2563EB',
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Pencadangan Gagal',
                        html: `<p class="text-sm text-gray-600">${data.message || 'Terjadi kesalahan saat proses pencadangan.'}</p>`,
                        confirmButtonColor: '#DC2626',
                    });
                }
            } catch (error) {
                console.error('[Backup] Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan Koneksi',
                    text: 'Tidak dapat terhubung ke server. Silakan cek koneksi atau coba lagi.',
                    confirmButtonColor: '#DC2626',
                });
            } finally {
                this.stopElapsedTimer();
                this.isBackingUp = false;
                this.fetchData();
                this.fetchBackupFiles();
            }
        },

        async saveSettings() {
            try {
                const response = await fetch('/api/admin/backup/settings', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        is_active: this.backupActive ? 1 : 0,
                        frequency: this.frequency,
                        execution_time: this.executionTime
                    })
                });
                const data = await response.json();
                if (data.status === 'success') {
                    Toast.fire({
                        icon: 'success',
                        title: 'Tersimpan',
                        text: data.message
                    });
                    this.openPengaturan = false;
                }
            } catch (error) {
                console.error(error);
            }
        },

        async deleteSelected() {
            if (this.selectedIds.length === 0) return;
            
            const result = await Swal.fire({
                title: 'Hapus Terpilih?',
                text: `Anda akan menghapus ${this.selectedIds.length} riwayat cadangan.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            });

            if (result.isConfirmed) {
                try {
                    const response = await fetch('/api/admin/backup/delete-selected', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ ids: this.selectedIds })
                    });
                    const data = await response.json();
                    if (data.status === 'success') {
                        this.selectedIds = [];
                        this.fetchData();
                    }
                } catch (error) {
                    console.error(error);
                }
            }
        },

        async confirmReset() {
            try {
                const response = await fetch('/api/admin/backup/reset', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'Accept': 'application/json'
                    }
                });
                const data = await response.json();
                if (data.status === 'success') {
                    this.openModalReset = false;
                    this.fetchData();
                }
            } catch (error) {
                console.error(error);
            }
        },

        // ============================================
        // RESTORE FUNCTIONS (NEW)
        // ============================================

        async fetchBackupFiles() {
            this.isLoadingBackupFiles = true;
            try {
                const response = await fetch('/api/admin/restore/backups', {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await response.json();
                this.backupFiles = data.data || [];
            } catch (error) {
                console.error('[Restore] Gagal mengambil daftar backup:', error);
            } finally {
                this.isLoadingBackupFiles = false;
            }
        },

        openRestoreModal(file) {
            this.restoreTarget = file;
            this.restorePassword = '';
            this.restoreErrors = {};
            this.showRestoreModal = true;
        },

        closeRestoreModal() {
            this.showRestoreModal = false;
            this.restoreTarget = null;
            this.restorePassword = '';
            this.restoreErrors = {};
        },

        async confirmRestore() {
            if (!this.restorePassword || !this.restoreTarget || this.isRestoring) return;
            
            this.restoreErrors = {};
            this.isRestoring = true;

            // Tutup modal konfirmasi, buka modal progress
            this.showRestoreModal = false;
            this.showRestoreProgressModal = true;
            this.restoreProgressStatus = 'processing';
            this.restoreProgressMessage = 'Mempersiapkan proses restore... Membuat backup otomatis terlebih dahulu.';

            try {
                const response = await fetch('/api/admin/restore/run', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        backup_file: this.restoreTarget.path,
                        password: this.restorePassword
                    })
                });

                const data = await response.json();

                if (response.status === 422) {
                    // Validation errors
                    this.showRestoreProgressModal = false;
                    this.showRestoreModal = true;
                    if (data.errors) {
                        this.restoreErrors = {};
                        Object.keys(data.errors).forEach(key => {
                            this.restoreErrors[key] = data.errors[key][0];
                        });
                    }
                    return;
                }

                if (data.status === 'success') {
                    this.restoreProgressStatus = 'success';
                    this.restoreProgressMessage = data.message;
                } else {
                    this.restoreProgressStatus = 'failed';
                    this.restoreProgressMessage = data.message || 'Terjadi kesalahan saat proses restore.';
                }
            } catch (error) {
                console.error('[Restore] Error:', error);
                this.restoreProgressStatus = 'failed';
                this.restoreProgressMessage = 'Terjadi kesalahan koneksi. Silakan cek status server.';
            } finally {
                this.isRestoring = false;
                this.restorePassword = '';
            }
        },

        finishRestore() {
            window.location.reload();
        },

        // ============================================
        // RESTORE LOGS FUNCTIONS (NEW)
        // ============================================

        async fetchRestoreLogs() {
            this.isLoadingRestoreLogs = true;
            try {
                const response = await fetch('/api/admin/restore/logs', {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await response.json();
                this.restoreLogs = (data.data || []).map(log => ({ ...log, _expanded: false }));
                this.restoreStats = data.stats || { total: 0, success: 0, failed: 0, rolled_back: 0 };
            } catch (error) {
                console.error('[Restore] Gagal mengambil log restore:', error);
            } finally {
                this.isLoadingRestoreLogs = false;
            }
        }
    }));
});
</script>
@endsection