@extends('components.layout')
@section('title', 'Laporan & Monitoring (Admin)')

@section('content')
@php
    $role = 'admin'; 
@endphp

<!-- Flatpickr CSS & JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/dark.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<div class="flex h-screen overflow-hidden bg-slate-50 dark:bg-slate-950 transition-colors duration-300" 
     x-data="monitoringData()" x-init="initData(); initFlatpickr()">
    
    {{-- Sidebar --}}
    <aside id="sidebar"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-slate-900 border-r dark:border-slate-800 transition-transform duration-300 lg:translate-x-0 lg:static lg:inset-0 shrink-0 overflow-y-auto">
        @include('components.nav-superadmin', ['role' => $role])
    </aside>

    <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-black/50 backdrop-blur-sm lg:hidden" x-cloak></div>

    <div class="flex-1 flex flex-col min-w-0 transition-colors duration-300">
        
        {{-- Header --}}
        @include('components.header-superadmin', ['title' => 'LAPORAN & MONITORING'])

        <main class="flex-1 overflow-y-auto p-4 lg:p-8 custom-scrollbar">
            {{-- Title Section & Conditional Admin Button --}}
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
                <div>
                    <h2 class="text-lg lg:text-xl font-bold text-gray-800 dark:text-white transition-colors" 
                        x-text="activeTab === 'internal' ? 'Monitoring Pelatihan Karyawan' : (showDetail ? 'Detail Sertifikat: ' + selectedUser : 'Sertifikat Eksternal')"></h2>
                    <p class="text-xs lg:text-sm text-gray-500 dark:text-gray-200 transition-colors leading-relaxed">Pantau kemajuan dan sertifikasi pelatihan seluruh staf rumah sakit secara real-time.</p>
                </div>
                
                <a href="/kelola-ttd" class="bg-amber-400 hover:bg-amber-500 text-amber-950 px-5 py-2.5 rounded-xl flex items-center gap-2 text-xs font-bold shadow-sm transition active:scale-95 inline-flex">
                    <i class="fa-solid fa-file-signature text-sm"></i>
                    Kelola Tanda Tangan
                </a>
            </div>

            {{-- Stat Cards --}}
            <div x-show="activeTab === 'internal'" x-transition:enter="transition ease-out duration-300" class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-8">
                <div class="bg-white dark:bg-slate-900 p-4 lg:p-5 rounded-xl border border-gray-100 dark:border-slate-800 shadow-sm flex items-center gap-4 transition hover:shadow-md">
                    <div class="w-10 h-10 lg:w-12 lg:h-12 bg-blue-600 rounded-lg flex items-center justify-center text-white shadow-lg shrink-0"><i class="fa-solid fa-users text-sm"></i></div>
                    <div class="min-w-0">
                        <p class="text-[9px] lg:text-[10px] font-bold text-gray-400 uppercase tracking-wider truncate">Total Peserta</p>
                        <p class="text-base lg:text-lg font-bold text-gray-800 dark:text-white" x-text="stats.total_peserta"><i class="fa-solid fa-spinner fa-spin text-xs"></i></p>
                    </div>
                </div>
                <div class="bg-white dark:bg-slate-900 p-4 lg:p-5 rounded-xl border border-gray-100 dark:border-slate-800 shadow-sm flex items-center gap-4 transition hover:shadow-md">
                    <div class="w-10 h-10 lg:w-12 lg:h-12 bg-emerald-500 rounded-lg flex items-center justify-center text-white shadow-lg shrink-0"><i class="fa-solid fa-check-double text-sm"></i></div>
                    <div class="min-w-0">
                        <p class="text-[9px] lg:text-[10px] font-bold text-gray-400 uppercase tracking-wider truncate">Penyelesaian</p>
                        <p class="text-base lg:text-lg font-bold text-gray-800 dark:text-white" x-text="stats.penyelesaian_percent + '%'"><i class="fa-solid fa-spinner fa-spin text-xs"></i></p>
                    </div>
                </div>
                <div class="bg-white dark:bg-slate-900 p-4 lg:p-5 rounded-xl border border-gray-100 dark:border-slate-800 shadow-sm flex items-center gap-4 transition hover:shadow-md">
                    <div class="w-10 h-10 lg:w-12 lg:h-12 bg-indigo-500 rounded-lg flex items-center justify-center text-white shadow-lg shrink-0"><i class="fa-solid fa-certificate text-sm"></i></div>
                    <div class="min-w-0">
                        <p class="text-[9px] lg:text-[10px] font-bold text-gray-400 uppercase tracking-wider truncate">Sertifikat</p>
                        <p class="text-base lg:text-lg font-bold text-gray-800 dark:text-white" x-text="stats.total_sertifikat"><i class="fa-solid fa-spinner fa-spin text-xs"></i></p>
                    </div>
                </div>
                <div class="bg-white dark:bg-slate-900 p-4 lg:p-5 rounded-xl border border-gray-100 dark:border-slate-800 shadow-sm flex items-center gap-4 transition hover:shadow-md">
                    <div class="w-10 h-10 lg:w-12 lg:h-12 bg-amber-500 rounded-lg flex items-center justify-center text-white shadow-lg shrink-0"><i class="fa-solid fa-star text-sm"></i></div>
                    <div class="min-w-0">
                        <p class="text-[9px] lg:text-[10px] font-bold text-gray-400 uppercase tracking-wider truncate">Rata-rata Nilai</p>
                        <p class="text-base lg:text-lg font-bold text-gray-800 dark:text-white" x-text="stats.rata_rata_nilai"><i class="fa-solid fa-spinner fa-spin text-xs"></i></p>
                    </div>
                </div>
            </div>

            {{-- Toggle Navigasi --}}
            <div class="w-full">
                <div class="flex p-1 bg-gray-100 dark:bg-slate-800 rounded-xl mb-6 border dark:border-slate-700">
                    <button @click="activeTab = 'internal'; showDetail = false"
                        :class="activeTab === 'internal' ? 'bg-emerald-500 text-white shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700'"
                        class="flex-1 py-2.5 text-xs font-bold rounded-lg transition-all duration-300">
                        Sertifikat Internal
                    </button>
                    <button @click="activeTab = 'external'; showDetail = false"
                        :class="activeTab === 'external' ? 'bg-emerald-500 text-white shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700'"
                        class="flex-1 py-2.5 text-xs font-bold rounded-lg transition-all duration-300">
                        Sertifikat Eksternal
                    </button>
                </div>
            </div>

            {{-- Filter Section --}}
            <div class="bg-white dark:bg-slate-900 p-4 lg:p-6 rounded-xl border border-gray-100 dark:border-slate-800 shadow-sm mb-8 transition-colors">
                <form @submit.prevent="fetchData(1)" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 items-end">
                    <div>
                        <label class="text-[11px] font-bold text-gray-500 dark:text-white uppercase mb-2 block tracking-tight">Rentang Waktu</label>
                        <input type="text" id="date_range_picker" x-model="filters.date_range" placeholder="Pilih Tanggal..." class="w-full border-gray-200 dark:border-slate-700 rounded-lg text-xs p-2.5 bg-gray-50 dark:bg-slate-800 text-gray-700 dark:text-white focus:outline-none transition-all cursor-pointer" readonly>
                    </div>
                    <div>
                        <label class="text-[11px] font-bold text-gray-500 dark:text-white uppercase mb-2 block tracking-tight">Unit Kerja</label>
                        <select x-model="filters.unit_kerja" class="w-full border-gray-200 dark:border-slate-700 rounded-lg text-xs p-2.5 bg-gray-50 dark:bg-slate-800 text-gray-700 dark:text-white">
                            <option value="">Semua Unit</option>
                            @foreach($unitKerjas as $unit)
                                <option value="{{ $unit->unit_kerja_id }}">{{ $unit->unit_kerja }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div x-show="activeTab === 'internal'">
                        <label class="text-[11px] font-bold text-gray-500 dark:text-white uppercase mb-2 block tracking-tight">Status Pelatihan</label>
                        <select x-model="filters.status" class="w-full border-gray-200 dark:border-slate-700 rounded-lg text-xs p-2.5 bg-gray-50 dark:bg-slate-800 text-gray-700 dark:text-white">
                            <option value="">Semua Status</option>
                            <option value="Selesai">Selesai</option>
                            <option value="Progres">Progres</option>
                            <option value="Belum Dimulai">Belum Dimulai</option>
                        </select>
                    </div>

                    <button type="submit" :class="activeTab === 'external' ? 'md:col-span-2' : ''" 
                        class="bg-blue-700 hover:bg-blue-800 text-white py-2.5 rounded-lg text-xs font-bold transition flex items-center justify-center gap-2 active:scale-95 shadow-lg shadow-blue-100 dark:shadow-none">
                        <i class="fa-solid fa-filter text-[10px]"></i> Terapkan Filter
                    </button>
                </form>
            </div>

            {{-- Table List --}}
            <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 p-4 lg:p-8 mb-10 transition-colors duration-300">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                    <h3 class="font-bold text-gray-800 dark:text-white transition-colors" 
                        x-text="activeTab === 'internal' ? 'Daftar Laporan Pelatihan' : 'Daftar Sertifikat Eksternal'"></h3>
                    <div class="relative w-full sm:w-64">
                        <input type="text" x-model="filters.search" @input.debounce.500ms="fetchData(1)" placeholder="Cari..." class="w-full pl-3 pr-8 py-2 border-gray-200 dark:border-slate-700 rounded-lg text-xs bg-gray-50 dark:bg-slate-800 text-gray-700 dark:text-white outline-none">
                        <i class="fa-solid fa-magnifying-glass absolute right-3 top-2.5 text-gray-400 dark:text-white text-xs"></i>
                    </div>
                </div>

                <div class="overflow-x-auto border dark:border-slate-800 rounded-lg transition-colors">
                    {{-- ================= TABEL SERTIFIKAT INTERNAL ================= --}}
                    <table x-show="activeTab === 'internal'" class="w-full text-left text-xs min-w-[950px]">
                        <thead class="bg-gray-50 dark:bg-slate-800/50 text-gray-500 dark:text-white font-bold border-b dark:border-slate-800">
                            <tr>
                                <th class="py-4 px-6 uppercase tracking-wider">Nama Karyawan</th>
                                <th class="py-4 px-4 uppercase tracking-wider">Unit Kerja</th>
                                <th class="py-4 px-4 uppercase tracking-wider">Nama Pelatihan</th>
                                <th class="py-4 px-4 uppercase tracking-wider text-center">Kemajuan</th>
                                <th class="py-4 px-4 uppercase tracking-wider text-center">Status</th>
                                <th class="py-4 px-4 uppercase tracking-wider text-center">Nilai</th>
                                <th class="py-4 px-4 uppercase tracking-wider text-center">Sertifikat</th>
                                <th class="py-4 px-6 uppercase tracking-wider text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-slate-800 text-gray-700 dark:text-white transition-colors">
                            <template x-if="isLoading">
                                <tr>
                                    <td colspan="8" class="py-10 text-center">
                                        <i class="fa-solid fa-spinner fa-spin text-3xl text-blue-500 mb-2"></i>
                                        <p class="text-xs text-gray-500">Memuat data...</p>
                                    </td>
                                </tr>
                            </template>
                            <template x-if="!isLoading && reports.length === 0">
                                <tr>
                                    <td colspan="8" class="py-12 text-center text-gray-400 italic">Data monitoring belum tersedia.</td>
                                </tr>
                            </template>
                            <template x-if="!isLoading && reports.length > 0">
                                <template x-for="report in reports" :key="report.progress_id">
                                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors">
                                        <td class="py-4 px-6 shrink-0">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 bg-gray-100 dark:bg-slate-700 rounded-lg flex items-center justify-center font-bold text-gray-400 dark:text-white text-[10px] border border-gray-200 dark:border-slate-600 uppercase" x-text="report.user && report.user.nama ? report.user.nama.substring(0, 2) : '??'"></div>
                                                <div class="truncate max-w-[120px]">
                                                    <p class="font-bold truncate" x-text="report.user ? report.user.nama : '-'"></p>
                                                    <p class="text-[10px] text-gray-400 dark:text-gray-300" x-text="report.user ? 'NIK: ' + report.user.nik : '-'"></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-4 px-4 text-gray-500 dark:text-gray-200 leading-tight" x-text="report.user && report.user.unit_kerja ? report.user.unit_kerja.unit_kerja : '-'"></td>
                                        <td class="py-4 px-4 font-bold" x-text="report.materi ? report.materi.judul : '-'"></td>
                                        <td class="py-4 px-4">
                                            <div class="flex items-center gap-2 justify-center">
                                                <p class="text-[10px] text-gray-400 dark:text-white w-8 font-bold" x-text="getPercent(report) + '%'"></p>
                                                <div class="w-20 bg-gray-100 dark:bg-slate-800 rounded-full h-1.5 overflow-hidden">
                                                    <div class="bg-blue-600 h-1.5 rounded-full transition-all duration-1000" :style="'width: ' + getPercent(report) + '%'"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-4 px-4 text-center">
                                            <span class="font-bold text-[10px] px-2.5 py-1 rounded-full border" 
                                                :class="report.status === 'Selesai' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-blue-50 text-blue-600 border-blue-100'"
                                                x-text="report.status"></span>
                                        </td>
                                        <td class="py-4 px-4 text-center font-bold" x-text="report.skor_total !== null ? parseFloat(report.skor_total).toFixed(1) : '-'"></td>
                                        <td class="py-4 px-4 text-center">
                                            <span class="px-2 py-1 rounded-full text-[9px] font-bold border" 
                                                :class="{
                                                    'bg-emerald-50 text-emerald-600 border-emerald-100 dark:bg-emerald-900/30 dark:text-emerald-400 dark:border-emerald-800': report.sertifikat_status === 'Disetujui',
                                                    'bg-amber-50 text-amber-600 border-amber-100 dark:bg-amber-900/30 dark:text-amber-400 dark:border-amber-800': !report.sertifikat_status || report.sertifikat_status === 'Belum Disetujui' || report.sertifikat_status === 'Menunggu',
                                                    'bg-red-50 text-red-600 border-red-100 dark:bg-red-900/30 dark:text-red-400 dark:border-red-800': report.sertifikat_status === 'Tidak Disetujui' || report.sertifikat_status === 'Ditolak'
                                                }"
                                                x-text="report.sertifikat_status === 'Ditolak' ? 'Tidak Disetujui' : (report.sertifikat_status || 'Belum Disetujui')"></span>
                                        </td>
                                        <td class="py-4 px-6 text-center text-gray-400 dark:text-white">
                                            <template x-if="report.status === 'Selesai'">
                                                <div class="flex flex-col items-center gap-1">
                                                    <template x-if="report.sertifikat_status !== 'Tidak Disetujui' && report.sertifikat_status !== 'Ditolak'">
                                                        <button
                                                            @click="viewUserSertifikat(report)"
                                                            class="hover:text-blue-600 transition">
                                                            <i class="fa-solid fa-eye"></i>
                                                        </button>
                                                    </template>

                                                    <a
                                                        :href="`verifikasi-pelatihan/${report.user_id}/${report.materi_id}`"
                                                        class="hover:text-emerald-600 transition">
                                                        <i class="fa-solid fa-file-circle-check"></i>
                                                    </a>
                                                </div>
                                            </template>

                                            <template x-if="report.status !== 'Selesai'">
                                                <span class="text-xs text-gray-400 italic">
                                                    Belum Selesai
                                                </span>
                                            </template>
                                        </td>
                                    </tr>
                                </template>
                            </template>
                        </tbody>
                    </table>

                    {{-- ================= TABEL DAFTAR KARYAWAN (EKSTERNAL) ================= --}}
                    <table x-show="activeTab === 'external'" x-cloak class="w-full text-left text-xs min-w-[850px]">
                        <thead class="bg-gray-50 dark:bg-slate-800/50 text-gray-500 dark:text-white font-bold border-b dark:border-slate-800">
                            <tr>
                                <th class="py-4 px-6 uppercase tracking-wider">Nama Karyawan</th>
                                <th class="py-4 px-4 uppercase tracking-wider">Unit Kerja</th>
                                <th class="py-4 px-4 uppercase tracking-wider">Judul Sertifikat</th>
                                <th class="py-4 px-4 uppercase tracking-wider text-center">Persetujuan</th>
                                <th class="py-4 px-6 uppercase tracking-wider text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-slate-800 text-gray-700 dark:text-white transition-colors">
                            <template x-if="isLoadingEksternal">
                                <tr>
                                    <td colspan="5" class="py-10 text-center">
                                        <i class="fa-solid fa-spinner fa-spin text-3xl text-blue-500 mb-2"></i>
                                        <p class="text-xs text-gray-500">Memuat data...</p>
                                    </td>
                                </tr>
                            </template>
                            <template x-if="!isLoadingEksternal && eksternalReports.length === 0">
                                <tr>
                                    <td colspan="5" class="py-12 text-center text-gray-400 italic">Belum ada data sertifikat eksternal.</td>
                                </tr>
                            </template>
                            <template x-if="!isLoadingEksternal && eksternalReports.length > 0">
                                <template x-for="item in eksternalReports" :key="item.sertifikat_eksternal_id">
                                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors">
                                        <td class="py-4 px-6 shrink-0">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 bg-gray-100 dark:bg-slate-700 rounded-lg flex items-center justify-center font-bold text-gray-400 text-[10px] border border-gray-200 uppercase" x-text="item.nama ? item.nama.substring(0, 2) : '??'"></div>
                                                <div>
                                                    <p class="font-bold" x-text="item.nama"></p>
                                                    <p class="text-[10px] text-gray-400" x-text="'NIK: ' + item.nik"></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-4 px-4 text-gray-500" x-text="item.unit_kerja"></td>
                                        <td class="py-4 px-4 font-bold" x-text="item.judul"></td>
                                        <td class="py-4 px-4 text-center">
                                            <span class="font-bold text-[10px] px-2.5 py-1 rounded-full border" 
                                                :class="{
                                                    'bg-emerald-50 text-emerald-600 border-emerald-100 dark:bg-emerald-900/30 dark:text-emerald-400 dark:border-emerald-800': item.status === 'Disetujui',
                                                    'bg-amber-50 text-amber-600 border-amber-100 dark:bg-amber-900/30 dark:text-amber-400 dark:border-amber-800': item.status === 'Belum Disetujui' || item.status === 'Menunggu',
                                                    'bg-red-50 text-red-600 border-red-100 dark:bg-red-900/30 dark:text-red-400 dark:border-red-800': item.status === 'Tidak Disetujui' || item.status === 'Ditolak'
                                                }"
                                                x-text="item.status === 'Ditolak' ? 'Tidak Disetujui' : item.status"></span>
                                        </td>
                                        <td class="py-4 px-6 text-center text-gray-400 dark:text-white">
                                            <div class="flex justify-center items-center gap-2">
                                                <button @click="viewSertifikatFile(item.image_path)" class="text-blue-600 hover:text-blue-800 transition p-1" title="Lihat Sertifikat"><i class="fa-solid fa-eye text-lg"></i></button>
                                                <a :href="'/validasi-pelatihan/' + item.sertifikat_eksternal_id" class="hover:text-emerald-600 transition p-1" title="Review Pelatihan"><i class="fa-solid fa-file-circle-check text-lg"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                            </template>
                        </tbody>
                    </table>
                </div>

                {{-- Pagination Internal --}}
                <div x-show="activeTab === 'internal'" class="mt-8 flex justify-center gap-2">
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

                {{-- Pagination Eksternal --}}
                <div x-show="activeTab === 'external'" class="mt-8 flex justify-center gap-2">
                    <template x-if="eksternalPagination.links">
                        <div class="flex flex-wrap items-center justify-center gap-1">
                            <template x-for="(link, index) in eksternalPagination.links" :key="'ext-'+index">
                                <button @click="if(link.url) fetchSertifikatEksternal(new URL(link.url).searchParams.get('page'))"
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

                {{-- Export Buttons --}}
                <div class="flex flex-col xl:flex-row justify-between items-center mt-8 gap-6 transition-colors">
                    <p class="text-[10px] text-gray-400 dark:text-white font-medium italic order-3 xl:order-1">Sistem Laporan Otomatis Citra Husada</p>
                    <div class="flex flex-col sm:flex-row items-center gap-4 order-1 xl:order-2 w-full sm:w-auto">
                        <div class="flex gap-2 w-full sm:w-auto transition-colors">
                            <a :href="'/laporan-monitoring/export/excel' + getQueryString()" class="flex-1 sm:flex-none flex items-center justify-center gap-2 px-4 py-2 border-2 border-blue-600 text-blue-600 rounded-lg text-[10px] lg:text-[11px] font-bold active:scale-95 transition-all">
                                <i class="fa-solid fa-file-excel"></i> Export Excel
                            </a>
                            <a :href="'/laporan-monitoring/export/pdf' + getQueryString()" class="flex-1 sm:flex-none flex items-center justify-center gap-2 px-4 py-2 border-2 border-red-500 text-red-500 rounded-lg text-[10px] lg:text-[11px] font-bold active:scale-95 transition-all">
                                <i class="fa-solid fa-file-pdf"></i> Export PDF
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    {{-- Modal Pop-Up Sertifikat Internal --}}
    <div x-show="openSertifikat" class="fixed inset-0 z-[100] flex items-center justify-center p-4" x-data="{ currentSlide: 'depan' }" x-effect="if(!openSertifikat) { currentSlide = 'depan'; sertifikatPdfUrl = null; }" x-cloak>
        <div x-show="openSertifikat" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="openSertifikat = false"></div>
        <div x-show="openSertifikat" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="relative bg-white dark:bg-slate-900 w-full max-w-4xl max-h-[90vh] rounded-2xl shadow-2xl overflow-hidden border dark:border-slate-800 flex flex-col transition-all duration-300">
            <div class="flex items-center justify-between px-6 py-4 border-b dark:border-slate-800 shrink-0">
                <h3 class="text-base font-bold text-slate-800 dark:text-white">Pratinjau Sertifikat</h3>
                <button @click="openSertifikat = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-white transition"><i class="fa-solid fa-xmark text-xl"></i></button>
            </div>
            
            <div class="p-6 overflow-y-auto custom-scrollbar flex-1 bg-slate-50 dark:bg-slate-950/50">
                <div x-show="sertifikatLoading" class="flex flex-col items-center justify-center py-20">
                    <i class="fa-solid fa-circle-notch fa-spin text-4xl text-blue-500 mb-4"></i>
                    <p class="text-sm text-gray-500 dark:text-gray-400 font-bold">Membuat sertifikat...</p>
                </div>
                
                <div x-show="!sertifikatLoading" class="w-full">
                    <!-- PDF Viewer if sertifikatPdfUrl is set -->
                    <template x-if="sertifikatPdfUrl">
                        <div class="w-full h-[65vh] rounded-xl overflow-hidden border dark:border-slate-800 bg-white dark:bg-slate-900">
                            <iframe :src="sertifikatPdfUrl" class="w-full h-full" frameborder="0"></iframe>
                        </div>
                    </template>

                    <!-- Slider view if it's dynamic generation -->
                    <template x-if="!sertifikatPdfUrl">
                        <div class="relative max-w-3xl mx-auto group">
                            <!-- Slide Container -->
                            <div class="relative overflow-hidden w-full">
                                <!-- Slide Bagian Depan -->
                                <div x-show="currentSlide === 'depan'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-8" x-transition:enter-end="opacity-100 transform translate-x-0" class="w-full">
                                    <div class="flex items-center justify-between mb-3">
                                        <h4 class="text-xs font-bold text-gray-600 dark:text-gray-400 uppercase">Bagian Depan</h4>
                                        <span class="text-xs font-bold text-gray-400">Halaman 1 dari 2</span>
                                    </div>
                                    <img :src="sertifikatDepanUrl" class="w-full h-auto rounded-lg shadow-lg border border-gray-200 dark:border-slate-700" alt="Sertifikat Depan">
                                </div>
                                
                                <!-- Slide Bagian Belakang -->
                                <div x-show="currentSlide === 'belakang'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-x-8" x-transition:enter-end="opacity-100 transform translate-x-0" class="w-full" x-cloak>
                                    <div class="flex items-center justify-between mb-3">
                                        <h4 class="text-xs font-bold text-gray-600 dark:text-gray-400 uppercase">Bagian Belakang</h4>
                                        <span class="text-xs font-bold text-gray-400">Halaman 2 dari 2</span>
                                    </div>
                                    <img :src="sertifikatBelakangUrl" class="w-full h-auto rounded-lg shadow-lg border border-gray-200 dark:border-slate-700" alt="Sertifikat Belakang">
                                </div>
                            </div>

                            <!-- Tombol Navigasi Kiri -->
                            <button type="button" 
                                x-show="currentSlide === 'belakang'"
                                @click="currentSlide = 'depan'"
                                class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white dark:bg-slate-900/80 dark:hover:bg-slate-900 text-gray-800 dark:text-white w-10 h-10 rounded-full flex items-center justify-center shadow-lg transition active:scale-95 border dark:border-slate-700">
                                <i class="fa-solid fa-chevron-left text-sm"></i>
                            </button>

                            <!-- Tombol Navigasi Kanan -->
                            <button type="button" 
                                x-show="currentSlide === 'depan'"
                                @click="currentSlide = 'belakang'"
                                class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white dark:bg-slate-900/80 dark:hover:bg-slate-900 text-gray-800 dark:text-white w-10 h-10 rounded-full flex items-center justify-center shadow-lg transition active:scale-95 border dark:border-slate-700">
                                <i class="fa-solid fa-chevron-right text-sm"></i>
                            </button>
                            
                            <!-- Indicator dots under the slide -->
                            <div class="flex justify-center gap-2 mt-4">
                                <button type="button" @click="currentSlide = 'depan'" :class="currentSlide === 'depan' ? 'bg-blue-600 w-6' : 'bg-gray-300 dark:bg-slate-700 w-2'" class="h-2 rounded-full transition-all duration-300"></button>
                                <button type="button" @click="currentSlide = 'belakang'" :class="currentSlide === 'belakang' ? 'bg-blue-600 w-6' : 'bg-gray-300 dark:bg-slate-700 w-2'" class="h-2 rounded-full transition-all duration-300"></button>
                            </div>
                        </div>
                    </template>
                </div>

    {{-- Modal PDF Viewer untuk Sertifikat Eksternal --}}
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
                <h3 class="text-base font-bold text-slate-800 dark:text-white">Pratinjau Sertifikat Eksternal</h3>
                <div class="flex items-center gap-3">
                    <a :href="pdfUrl" target="_blank" class="text-blue-600 hover:text-blue-800 text-xs font-bold transition flex items-center gap-1">
                        <i class="fa-solid fa-external-link-alt"></i> Buka Tab Baru
                    </a>
                    <button @click="openPdfViewer = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-white transition"><i class="fa-solid fa-xmark text-xl"></i></button>
                </div>
            </div>
            <div class="flex-1 overflow-hidden bg-slate-50 dark:bg-slate-950/50">
                <iframe :src="pdfUrl" class="w-full h-full min-h-[70vh]" frameborder="0"></iframe>
            </div>
        </div>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; height: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 10px; }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; }
    [x-cloak] { display: none !important; }
</style>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('monitoringData', () => ({
            sidebarOpen: false, 
            darkMode: localStorage.getItem('theme') === 'dark', 
            openSertifikat: false,
            openPdfViewer: false,
            pdfUrl: '',
            activeTab: 'internal',
            isLoading: true,
            isLoadingEksternal: false,
            
            sertifikatDepanUrl: null,
            sertifikatBelakangUrl: null,
            sertifikatPdfUrl: null,
            sertifikatLoading: false,

            stats: {
                total_peserta: 0,
                penyelesaian_percent: 0,
                total_sertifikat: 0,
                rata_rata_nilai: 0
            },
            reports: [],
            pagination: {},
            eksternalReports: [],
            eksternalPagination: {},

            filters: {
                date_range: '',
                unit_kerja: '',
                status: '',
                search: ''
            },

            async initData() {
                await this.fetchData();
                await this.fetchSertifikatEksternal();
            },

            initFlatpickr() {
                flatpickr("#date_range_picker", {
                    mode: 'range',
                    dateFormat: 'Y-m-d',
                    onChange: (selectedDates, dateStr) => {
                        this.filters.date_range = dateStr;
                    }
                });
            },

            async fetchData(page = 1) {
                if (this.activeTab === 'external') {
                    return this.fetchSertifikatEksternal(page);
                }
                this.isLoading = true;
                try {
                    const url = new URL('/api/admin/laporan-monitoring/data', window.location.origin);
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
                    if (this.filters.unit_kerja) url.searchParams.append('unit_kerja', this.filters.unit_kerja);
                    if (this.filters.status) url.searchParams.append('status', this.filters.status);
                    if (this.filters.search) url.searchParams.append('search', this.filters.search);

                    const response = await fetch(url.toString(), {
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    const data = await response.json();
                    
                    this.stats = data.stats;
                    this.reports = data.reports.data;
                    this.pagination = {
                        current_page: data.reports.current_page,
                        last_page: data.reports.last_page,
                        links: data.reports.links
                    };
                } catch (error) {
                    console.error(error);
                } finally {
                    this.isLoading = false;
                }
            },

            async fetchSertifikatEksternal(page = 1) {
                this.isLoadingEksternal = true;
                try {
                    const url = new URL('/api/admin/laporan-monitoring/sertifikat-eksternal/list', window.location.origin);
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
                    if (this.filters.unit_kerja) url.searchParams.append('unit_kerja', this.filters.unit_kerja);
                    if (this.filters.search) url.searchParams.append('search', this.filters.search);

                    const response = await fetch(url.toString(), {
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    const result = await response.json();
                    
                    this.eksternalReports = result.data.data;
                    this.eksternalPagination = {
                        current_page: result.data.current_page,
                        last_page: result.data.last_page,
                        links: result.data.links
                    };
                } catch (error) {
                    console.error('Error fetching sertifikat eksternal:', error);
                } finally {
                    this.isLoadingEksternal = false;
                }
            },

            getPercent(report) {
                if (!report.materi) return 0;
                const totalSteps = (report.materi.sub_materis_count || 0) + (report.materi.post_tests_count || 0);
                if (totalSteps > 0) return Math.round((report.urutan_selesai / totalSteps) * 100);
                return 0;
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
                if (this.filters.unit_kerja) params.append('unit_kerja', this.filters.unit_kerja);
                if (this.filters.status) params.append('status', this.filters.status);
                if (this.filters.search) params.append('search', this.filters.search);
                const str = params.toString();
                return str ? '?' + str : '';
            },

            viewSertifikatFile(imagePath) {
                if (!imagePath) {
                    alert('File sertifikat tidak tersedia.');
                    return;
                }
                this.pdfUrl = '/storage/' + imagePath;
                this.openPdfViewer = true;
            },

            async viewUserSertifikat(report) {
                this.openSertifikat = true;
                this.sertifikatPdfUrl = null;

                if (report.sertifikat_status === 'Disetujui' && report.sertifikat_image_path) {
                    this.sertifikatLoading = false;
                    this.sertifikatPdfUrl = '/storage/' + report.sertifikat_image_path;
                    return;
                }

                this.sertifikatLoading = true;
                this.sertifikatDepanUrl = null;
                this.sertifikatBelakangUrl = null;
                const userId = report.user_id;
                const materiId = report.materi_id;

                try {
                    const token = localStorage.getItem('access_token') || '';
                    const headers = token ? { 'Authorization': `Bearer ${token}` } : {};

                    // Fetch Depan
                    const resDepan = await fetch(`/api/admin/sertifikat/generate/${userId}/${materiId}?type=depan`, { headers });
                    if(resDepan.ok) {
                        const blobDepan = await resDepan.blob();
                        if(this.sertifikatDepanUrl) URL.revokeObjectURL(this.sertifikatDepanUrl);
                        this.sertifikatDepanUrl = URL.createObjectURL(blobDepan);
                    }

                    // Fetch Belakang
                    const resBelakang = await fetch(`/api/admin/sertifikat/generate/${userId}/${materiId}?type=belakang`, { headers });
                    if(resBelakang.ok) {
                        const blobBelakang = await resBelakang.blob();
                        if(this.sertifikatBelakangUrl) URL.revokeObjectURL(this.sertifikatBelakangUrl);
                        this.sertifikatBelakangUrl = URL.createObjectURL(blobBelakang);
                    }
                } catch(e) {
                    console.error('Error fetching certificate:', e);
                } finally {
                    this.sertifikatLoading = false;
                }
            }
        }));
    });
</script>
@endsection