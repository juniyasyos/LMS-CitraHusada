@extends('components.layout')
@section('title', 'Laporan & Monitoring')

@section('content')
    <div class="flex h-screen overflow-hidden bg-slate-50 dark:bg-slate-950 transition-colors duration-300" x-data="{ 
            sidebarOpen: false, 
            darkMode: localStorage.getItem('theme') === 'dark', 
            openSertifikat: false,
            activeTab: 'internal',
            showDetail: false,
            selectedUser: '',
            selectedCourse: '',
            selectedDate: '',
            selectedCertId: ''
        }">

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

            <header
                class="bg-white dark:bg-slate-900 border-b dark:border-slate-800 h-16 flex items-center justify-between px-4 lg:px-8 shrink-0 z-10 transition-colors duration-300">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = true" class="lg:hidden p-2 text-gray-500 dark:text-white">
                        <i class="fa-solid fa-bars text-lg"></i>
                    </button>
                    <h1 class="text-sm font-semibold text-gray-600 dark:text-white truncate">Laporan & Monitoring</h1>
                </div>

                <div class="flex items-center gap-3 lg:gap-4">
                    @include('components.notif-superadmin')
                    <div class="flex items-center gap-3 pl-2 lg:pl-4 border-l border-gray-100 dark:border-slate-800">
                        <div class="text-right hidden sm:block">
                            <p class="text-xs font-bold text-gray-800 dark:text-white leading-tight">Superadmin</p>
                            <p class="text-[10px] text-gray-500 dark:text-gray-300 font-medium italic">Utama</p>
                        </div>
                        <div
                            class="w-8 h-8 bg-gray-200 dark:bg-slate-700 rounded-full overflow-hidden border border-gray-100 dark:border-slate-800 flex items-center justify-center">
                            <i class="fa-solid fa-user text-gray-500 dark:text-white text-xs"></i>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-4 lg:p-8 custom-scrollbar">
                <div class="mb-8">
                    <h2 class="text-lg lg:text-xl font-bold text-gray-800 dark:text-white transition-colors"
                        x-text="activeTab === 'internal' ? 'Monitoring Pelatihan Karyawan' : (showDetail ? 'Detail Sertifikat: ' + selectedUser : 'Sertifikat Eksternal')">
                    </h2>
                    <p class="text-xs lg:text-sm text-gray-500 dark:text-gray-200 transition-colors leading-relaxed">Pantau
                        kemajuan dan sertifikasi pelatihan seluruh staf rumah sakit secara real-time.</p>
                </div>

                <div x-show="activeTab === 'internal'" x-transition:enter="transition ease-out duration-300"
                    class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-8">
                    <div
                        class="bg-white dark:bg-slate-900 p-4 rounded-xl border border-gray-100 dark:border-slate-800 shadow-sm flex items-center gap-3 lg:gap-4 transition hover:shadow-md">
                        <div
                            class="w-10 h-10 lg:w-12 lg:h-12 bg-blue-600 rounded-lg flex items-center justify-center text-white shadow-lg shrink-0">
                            <i class="fa-solid fa-users text-sm lg:text-base"></i>
                        </div>
                        <div class="min-w-0">
                            <p
                                class="text-[9px] lg:text-[10px] font-bold text-gray-400 dark:text-white uppercase tracking-wider transition-colors truncate">
                                Total Peserta</p>
                            <p class="text-base lg:text-lg font-bold text-gray-800 dark:text-white">
                                {{ number_format($totalPeserta) }}</p>
                        </div>
                    </div>
                    <div
                        class="bg-white dark:bg-slate-900 p-4 rounded-xl border border-gray-100 dark:border-slate-800 shadow-sm flex items-center gap-3 lg:gap-4 transition hover:shadow-md">
                        <div
                            class="w-10 h-10 lg:w-12 lg:h-12 bg-emerald-500 rounded-lg flex items-center justify-center text-white shadow-lg shrink-0">
                            <i class="fa-solid fa-check-double text-sm lg:text-base"></i>
                        </div>
                        <div class="min-w-0">
                            <p
                                class="text-[9px] lg:text-[10px] font-bold text-gray-400 dark:text-white uppercase tracking-wider transition-colors truncate">
                                Penyelesaian</p>
                            <p class="text-base lg:text-lg font-bold text-gray-800 dark:text-white">
                                {{ $penyelesaianPercent }}%</p>
                        </div>
                    </div>
                    <div
                        class="bg-white dark:bg-slate-900 p-4 rounded-xl border border-gray-100 dark:border-slate-800 shadow-sm flex items-center gap-3 lg:gap-4 transition hover:shadow-md">
                        <div
                            class="w-10 h-10 lg:w-12 lg:h-12 bg-indigo-500 rounded-lg flex items-center justify-center text-white shadow-lg shrink-0">
                            <i class="fa-solid fa-certificate text-sm lg:text-base"></i>
                        </div>
                        <div class="min-w-0">
                            <p
                                class="text-[9px] lg:text-[10px] font-bold text-gray-400 dark:text-white uppercase tracking-wider transition-colors truncate">
                                Sertifikat</p>
                            <p class="text-base lg:text-lg font-bold text-gray-800 dark:text-white">
                                {{ number_format($totalSertifikat) }}</p>
                        </div>
                    </div>
                    <div
                        class="bg-white dark:bg-slate-900 p-4 rounded-xl border border-gray-100 dark:border-slate-800 shadow-sm flex items-center gap-3 lg:gap-4 transition hover:shadow-md">
                        <div
                            class="w-10 h-10 lg:w-12 lg:h-12 bg-amber-500 rounded-lg flex items-center justify-center text-white shadow-lg shrink-0">
                            <i class="fa-solid fa-star text-sm lg:text-base"></i>
                        </div>
                        <div class="min-w-0">
                            <p
                                class="text-[9px] lg:text-[10px] font-bold text-gray-400 dark:text-white uppercase tracking-wider transition-colors truncate">
                                Rata-rata Nilai</p>
                            <p class="text-base lg:text-lg font-bold text-gray-800 dark:text-white">{{ $rataRataNilai }}</p>
                        </div>
                    </div>
                </div>

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

                <div x-show="activeTab === 'external' && showDetail" class="mb-4" x-cloak>
                    <button @click="showDetail = false"
                        class="flex items-center gap-2 text-xs font-bold text-blue-600 hover:text-blue-700 transition">
                        <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Sertifikat Eksternal
                    </button>
                </div>

                <div
                    class="bg-white dark:bg-slate-900 p-4 lg:p-6 rounded-xl border border-gray-100 dark:border-slate-800 shadow-sm mb-8 transition-colors">
                    <form action="{{ route('laporan.monitoring') }}" method="GET"
                        class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 items-end">
                        <div>
                            <label
                                class="text-[11px] font-bold text-gray-500 dark:text-white uppercase mb-2 block tracking-tight">Cari
                                Karyawan/Pelatihan</label>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Nama, NIP, atau Pelatihan..."
                                class="w-full border-gray-200 dark:border-slate-700 rounded-lg text-xs p-2.5 bg-gray-50 dark:bg-slate-800 text-gray-700 dark:text-white focus:outline-none transition-all">
                        </div>
                        <div>
                            <label
                                class="text-[11px] font-bold text-gray-500 dark:text-white uppercase mb-2 block tracking-tight">Unit
                                Kerja</label>
                            <select name="unit_kerja"
                                class="w-full border-gray-200 dark:border-slate-700 rounded-lg text-xs p-2.5 bg-gray-50 dark:bg-slate-800 text-gray-700 dark:text-white">
                                <option value="">Semua Unit</option>
                                @foreach($unitKerjas as $unit)
                                    <option value="{{ $unit->unit_kerja_id }}" {{ request('unit_kerja') == $unit->unit_kerja_id ? 'selected' : '' }}>{{ $unit->unit_kerja }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label
                                class="text-[11px] font-bold text-gray-500 dark:text-white uppercase mb-2 block tracking-tight">Status
                                Pelatihan</label>
                            <select name="status"
                                class="w-full border-gray-200 dark:border-slate-700 rounded-lg text-xs p-2.5 bg-gray-50 dark:bg-slate-800 text-gray-700 dark:text-white">
                                <option value="">Semua Status</option>
                                <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai
                                </option>
                                <option value="Progres" {{ request('status') == 'Progres' ? 'selected' : '' }}>Progres
                                </option>
                                <option value="Belum Dimulai" {{ request('status') == 'Belum Dimulai' ? 'selected' : '' }}>
                                    Belum Dimulai</option>
                            </select>
                        </div>
                        <button type="submit"
                            class="bg-blue-700 hover:bg-blue-800 text-white py-2.5 rounded-lg text-xs font-bold transition flex items-center justify-center gap-2 active:scale-95 shadow-lg shadow-blue-100 dark:shadow-none">
                            <i class="fa-solid fa-filter text-[10px]"></i>
                            Terapkan Filter
                        </button>
                    </form>
                </div>

                <div
                    class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 p-4 lg:p-8 mb-10 transition-colors duration-300">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                        <h3 class="font-bold text-gray-800 dark:text-white transition-colors"
                            x-text="activeTab === 'internal' ? 'Daftar Laporan Pelatihan' : (showDetail ? 'Daftar Pelatihan Eksternal' : 'Daftar Sertifikat Eksternal')">
                        </h3>
                    </div>

                    <div class="overflow-x-auto border dark:border-slate-800 rounded-lg transition-colors">
                        <table x-show="activeTab === 'internal'" class="w-full text-left text-xs min-w-[900px]">
                            <thead
                                class="bg-gray-50 dark:bg-slate-800/50 text-gray-500 dark:text-white font-bold border-b dark:border-slate-800">
                                <tr>
                                    <th class="py-4 px-6 uppercase tracking-wider">Nama Karyawan</th>
                                    <th class="py-4 px-4 uppercase tracking-wider">Unit Kerja</th>
                                    <th class="py-4 px-4 uppercase tracking-wider">Nama Pelatihan</th>
                                    <th class="py-4 px-4 uppercase tracking-wider text-center">Progres</th>
                                    <th class="py-4 px-4 uppercase tracking-wider text-center">Status</th>
                                    <th class="py-4 px-4 uppercase tracking-wider text-center">Nilai</th>
                                    <th class="py-4 px-6 uppercase tracking-wider text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody
                                class="divide-y divide-gray-100 dark:divide-slate-800 text-gray-700 dark:text-white transition-colors">
                                @forelse($internalReports as $report)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors">
                                        <td class="py-4 px-6">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-8 h-8 bg-gray-100 dark:bg-slate-700 rounded-lg flex items-center justify-center font-bold text-gray-400 dark:text-white text-[10px] border border-gray-200 dark:border-slate-600 uppercase">
                                                    {{ substr($report->user->nama, 0, 2) }}
                                                </div>
                                                <div class="truncate max-w-[150px]">
                                                    <p class="font-bold text-gray-800 dark:text-white truncate">
                                                        {{ $report->user->nama }}</p>
                                                    <p class="text-[10px] text-gray-400 dark:text-gray-300 italic">
                                                        {{ $report->user->nik }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-4 px-4 text-gray-500 dark:text-gray-200">
                                            {{ $report->user->unitKerja->unit_kerja ?? '-' }}</td>
                                        <td class="py-4 px-4 font-bold text-gray-800 dark:text-white truncate max-w-[200px]">
                                            {{ $report->materi->judul }}</td>
                                        <td class="py-4 px-4">
                                            @php
                                                $totalSteps = $report->materi->subMateris->count() + $report->materi->postTests->count();
                                                $percent = $totalSteps > 0 ? round(($report->urutan_selesai / $totalSteps) * 100) : 0;
                                            @endphp
                                            <div class="flex items-center gap-2 justify-center">
                                                <p class="text-[10px] text-gray-400 dark:text-white w-8 font-bold">
                                                    {{ $percent }}%</p>
                                                <div
                                                    class="w-20 bg-gray-100 dark:bg-slate-800 rounded-full h-1.5 overflow-hidden">
                                                    <div class="bg-blue-600 h-1.5 rounded-full transition-all duration-1000"
                                                        style="width: {{ $percent }}%"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-4 px-4 text-center">
                                            <span
                                                class="font-bold text-[10px] px-2 py-1 rounded-full border
                                                {{ $report->status == 'Selesai' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-blue-50 text-blue-600 border-blue-100' }}">
                                                {{ $report->status }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-4 text-center font-bold dark:text-white italic">
                                            {{ $report->skor_total !== null ? round($report->skor_total, 1) : '-' }}
                                        </td>
                                        <td class="py-4 px-6 text-center">
                                            @if($report->status == 'Selesai')
                                                <button @click="
                                                        openSertifikat = true; 
                                                        selectedUser = '{{ $report->user->name }}'; 
                                                        selectedCourse = '{{ $report->materi->judul }}';
                                                        selectedDate = '{{ $report->updated_at->format('M d, Y') }}';
                                                    " class="text-blue-600 hover:text-blue-800 transition">
                                                    <i class="fa-solid fa-eye"></i>
                                                </button>
                                            @else
                                                <span class="text-gray-300 italic text-[9px]">Dalam Progres</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-12 text-center text-gray-400 italic">Data monitoring belum
                                            tersedia.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        {{-- Tabel Sertifikat Eksternal (Placeholder Design Tetap Dipertahankan) --}}
                        <table x-show="activeTab === 'external' && !showDetail" x-cloak
                            class="w-full text-left text-xs min-w-[700px]">
                            <thead
                                class="bg-gray-50 dark:bg-slate-800/50 text-gray-500 dark:text-white font-bold border-b dark:border-slate-800">
                                <tr>
                                    <th class="py-4 px-6 uppercase tracking-wider">Nama Karyawan</th>
                                    <th class="py-4 px-4 uppercase tracking-wider">Unit Kerja</th>
                                    <th class="py-4 px-4 uppercase tracking-wider text-center">Jumlah Sertifikat</th>
                                    <th class="py-4 px-6 uppercase tracking-wider text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody
                                class="divide-y divide-gray-100 dark:divide-slate-800 text-gray-700 dark:text-white transition-colors">
                                <tr class="hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors">
                                    <td colspan="4" class="py-12 text-center text-gray-400 italic">Modul Sertifikat
                                        Eksternal sedang dalam tahap integrasi.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-8">
                        {{ $internalReports->links() }}
                    </div>

                    <div class="flex flex-col xl:flex-row justify-between items-center mt-8 gap-6 transition-colors">
                        <p class="text-[10px] text-gray-400 dark:text-white font-medium italic order-3 xl:order-1">Sistem
                            Laporan Otomatis Citra Husada</p>
                        <div class="flex flex-col sm:flex-row items-center gap-4 order-1 xl:order-2 w-full sm:w-auto">
                            <div class="flex gap-2 w-full sm:w-auto transition-colors">
                                <a href="{{ route('laporan.monitoring.excel', request()->all()) }}"
                                    class="flex-1 sm:flex-none flex items-center justify-center gap-2 px-4 py-2 border-2 border-blue-600 text-blue-600 rounded-lg text-[10px] lg:text-[11px] font-bold active:scale-95 transition-all">
                                    <i class="fa-solid fa-file-excel"></i> Export Excel
                                </a>
                                <a href="{{ route('laporan.monitoring.pdf', request()->all()) }}"
                                    class="flex-1 sm:flex-none flex items-center justify-center gap-2 px-4 py-2 border-2 border-red-500 text-red-500 rounded-lg text-[10px] lg:text-[11px] font-bold active:scale-95 transition-all">
                                    <i class="fa-solid fa-file-pdf"></i> Export PDF
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>

        {{-- Modal Pop-Up Sertifikat --}}
        <div x-show="openSertifikat" class="fixed inset-0 z-100 flex items-center justify-center p-4" x-cloak>
            <div x-show="openSertifikat" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="openSertifikat = false"></div>
            <div x-show="openSertifikat" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="relative bg-white dark:bg-slate-900 w-full max-w-4xl rounded-2xl shadow-2xl overflow-hidden border dark:border-slate-800">
                <div class="flex items-center justify-between px-6 py-4 border-b dark:border-slate-800">
                    <h3 class="text-base font-bold text-slate-800 dark:text-white">Preview Sertifikat</h3>
                    <button @click="openSertifikat = false"
                        class="text-slate-400 hover:text-slate-600 dark:hover:text-white transition"><i
                            class="fa-solid fa-xmark text-xl"></i></button>
                </div>
                <div class="p-4 sm:p-6 lg:p-12 flex justify-center bg-slate-50 dark:bg-slate-950/50 overflow-hidden">
                    <div class="certificate-scaler-wrapper w-full flex items-center justify-center">
                        <div
                            class="certificate-content relative w-[800px] aspect-[1.414/1] bg-white shadow-lg border-[12px] border-blue-100 flex flex-col items-center p-8 lg:p-12 text-center shrink-0">
                            <div
                                class="absolute top-0 left-0 w-24 h-24 border-t-4 border-l-4 border-blue-400 rounded-tl-lg">
                            </div>
                            <div
                                class="absolute bottom-0 right-0 w-24 h-24 border-b-4 border-r-4 border-blue-400 rounded-br-lg">
                            </div>
                            <div class="mb-6 text-center">
                                <h4 class="text-red-600 font-bold text-xs leading-none uppercase tracking-tighter">Citra
                                    Husada</h4>
                                <p class="text-green-600 font-bold text-[10px]">Learning Management System</p>
                            </div>
                            <h2 class="text-2xl lg:text-3xl font-serif font-bold text-slate-800 mb-2">Certificate of
                                Completion</h2>
                            <h1 class="text-3xl lg:text-4xl font-bold text-blue-600 border-b-2 border-blue-50 px-4 mt-8"
                                x-text="selectedUser"></h1>
                            <p class="text-xs text-slate-500 mt-12">Has successfully completed the training module</p>
                            <h3 class="text-sm lg:text-base font-bold text-slate-800 max-w-md uppercase mt-2"
                                x-text="selectedCourse"></h3>
                            <div class="mt-auto w-full flex justify-between items-end px-4">
                                <div class="text-left">
                                    <p class="text-[10px] text-slate-400 uppercase tracking-widest">Date Issued</p>
                                    <p class="text-xs font-bold text-slate-700" x-text="selectedDate"></p>
                                </div>
                                <div
                                    class="w-20 h-20 bg-blue-50/50 rounded-full flex items-center justify-center border-2 border-blue-100">
                                    <i class="fa-solid fa-stamp text-blue-200 text-3xl"></i>
                                </div>
                            </div>
                        </div>
                    </div>
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

        .certificate-scaler-wrapper {
            min-height: 250px;
        }

        @media (max-width: 850px) {
            .certificate-content {
                transform: scale(0.8);
                transform-origin: center center;
            }
        }

        @media (max-width: 640px) {
            .certificate-content {
                transform: scale(0.55);
            }

            .certificate-scaler-wrapper {
                height: 320px;
            }
        }

        @media (max-width: 480px) {
            .certificate-content {
                transform: scale(0.42);
            }

            .certificate-scaler-wrapper {
                height: 250px;
            }
        }
    </style>
@endsection