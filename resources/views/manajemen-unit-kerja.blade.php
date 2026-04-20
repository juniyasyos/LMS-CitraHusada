@extends('components.layout')
@section('title', 'Manajemen Unit Kerja')

@section('content')
{{-- Menambahkan state viewMode untuk mengontrol tampilan antara Unit dan Tenaga Kerja --}}
<div class="flex h-screen overflow-hidden bg-slate-50 dark:bg-slate-950 transition-colors duration-300" 
     x-data="{ 
        openTambah: false, 
        openEdit: false, 
        sidebarOpen: false, 
        darkMode: localStorage.getItem('theme') === 'dark',
        viewMode: 'unit',
        openDropdown: false 
     }">
    
    {{-- SIDEBAR RESPONSIVE --}}
    <aside 
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-slate-900 border-r dark:border-slate-800 transition-transform duration-300 lg:translate-x-0 lg:static lg:inset-0 flex-shrink-0 overflow-y-auto">
        @include('components.nav-superadmin')
    </aside>

    {{-- OVERLAY MOBILE --}}
    <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-black/50 backdrop-blur-sm lg:hidden" x-cloak x-transition></div>

    <div class="flex-1 flex flex-col min-w-0 transition-colors duration-300">
        
        {{-- HEADER --}}
        <header class="bg-white dark:bg-slate-900 border-b dark:border-slate-800 h-16 flex items-center justify-between px-4 lg:px-8 flex-shrink-0 z-10">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = true" class="lg:hidden p-2 text-gray-500 dark:text-white">
                    <i class="fa-solid fa-bars text-lg"></i>
                </button>
                <h1 class="text-sm font-semibold text-gray-600 dark:text-white truncate">Manajemen Unit Kerja</h1>
            </div>

            <div class="flex items-center gap-4">
                @include('components.notif-superadmin')
                <div class="flex items-center gap-3 pl-4 border-l border-gray-100 dark:border-slate-800">
                    <div class="text-right hidden sm:block">
                        <p class="text-xs font-bold text-gray-800 dark:text-white leading-tight">Superadmin</p>
                        <p class="text-[10px] text-gray-500 dark:text-gray-300 font-medium">Administrator</p>
                    </div>
                    <div class="w-8 h-8 bg-gray-200 dark:bg-slate-700 rounded-full flex items-center justify-center border dark:border-slate-800">
                        <i class="fa-solid fa-user text-gray-500 dark:text-white text-xs"></i>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-4 lg:p-8 custom-scrollbar">
            {{-- TITLE SECTION DENGAN DROPDOWN --}}
            <div class="flex flex-col sm:flex-row justify-between items-start gap-4 mb-6">
                <div class="relative">
                    <button @click="openDropdown = !openDropdown" class="flex items-center gap-2 group">
                        <h2 class="text-lg font-bold text-gray-800 dark:text-white transition-colors" 
                            x-text="viewMode === 'unit' ? 'Daftar Unit Kerja' : 'Daftar Tenaga Kerja'"></h2>
                        <i class="fa-solid fa-chevron-down text-xs text-gray-400 group-hover:text-blue-500 transition-colors mt-1"></i>
                    </button>
                    
                    {{-- Dropdown Menu --}}
                    <div x-show="openDropdown" @click.away="openDropdown = false" x-cloak x-transition
                         class="absolute left-0 mt-2 w-56 bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-xl shadow-xl z-20 overflow-hidden">
                        <button @click="viewMode = 'unit'; openDropdown = false" 
                                class="w-full text-left px-4 py-3 text-sm font-bold text-gray-600 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:text-blue-600 transition-colors">
                            Daftar Unit Kerja
                        </button>
                        <button @click="viewMode = 'tenaga'; openDropdown = false" 
                                class="w-full text-left px-4 py-3 text-sm font-bold text-gray-600 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:text-blue-600 transition-colors">
                            Daftar Tenaga Kerja
                        </button>
                    </div>

                    <p class="text-xs lg:text-sm text-gray-500 dark:text-gray-300 mt-1" 
                       x-text="viewMode === 'unit' ? 'Kelola struktur organisasi untuk penugasan pelatihan.' : 'Kelola klasifikasi jenis tenaga medis dan staf hospital.'"></p>
                </div>

                <button @click="openTambah = true" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg flex items-center justify-center gap-2 text-sm font-bold transition shadow-sm active:scale-95">
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span x-text="viewMode === 'unit' ? 'Tambah Unit Kerja' : 'Tambah Tenaga Kerja'"></span>
                </button>
            </div>

            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <p class="text-xs lg:text-sm text-gray-500 dark:text-gray-200 italic">
                    <span class="font-bold text-gray-700 dark:text-white">Informasi:</span> Total 5 data terdaftar.
                </p>
                <div class="relative w-full sm:w-64">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </span>
                    <input type="text" 
                        class="block w-full pl-9 pr-3 py-2 border border-gray-200 dark:border-slate-700 rounded-lg bg-gray-50 dark:bg-slate-800 text-xs text-gray-700 dark:text-white outline-none focus:ring-2 focus:ring-blue-500/20" 
                        :placeholder="viewMode === 'unit' ? 'Cari unit kerja...' : 'Cari tenaga kerja...'">
                </div>
            </div>

            {{-- TABLE WRAPPER --}}
            <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 overflow-x-auto mb-8 transition-colors">
                
                {{-- TABLE DAFTAR UNIT KERJA --}}
                <table x-show="viewMode === 'unit'" class="w-full text-left text-xs min-w-[700px]">
                    <thead class="text-gray-500 dark:text-white font-bold border-b dark:border-slate-800 bg-gray-50/50 dark:bg-slate-800/50">
                        <tr>
                            <th class="py-4 px-6 uppercase tracking-wider">Nama Unit Kerja</th>
                            <th class="py-4 px-4 uppercase tracking-wider text-center">Jumlah Karyawan</th>
                            <th class="py-4 px-4 uppercase tracking-wider">Keterangan</th>
                            <th class="py-4 px-6 uppercase tracking-wider text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-800 text-gray-700 dark:text-white">
                        @php
                            $units = [
                                ['id' => 1, 'name' => 'Instalasi Gawat Darurat (IGD)', 'count' => '45 Orang', 'desc' => 'Unit pelayanan medis darurat 24 jam'],
                                ['id' => 2, 'name' => 'Intensive Care Unit (ICU)', 'count' => '28 Orang', 'desc' => 'Unit perawatan intensif'],
                                ['id' => 3, 'name' => 'Farmasi', 'count' => '15 Orang', 'desc' => 'Pengelolaan penyediaan obat'],
                                ['id' => 4, 'name' => 'Radiologi', 'count' => '12 Orang', 'desc' => 'Layanan diagnostik alat medis'],
                                ['id' => 5, 'name' => 'Poliklinik Anak', 'count' => '22 Orang', 'desc' => 'Layanan kesehatan rawat jalan'],
                            ];
                        @endphp
                        @foreach($units as $unit)
                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-800 transition">
                            <td class="py-5 px-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 bg-blue-50 dark:bg-blue-900/30 rounded-lg flex items-center justify-center flex-shrink-0 text-blue-500">
                                        <i class="fa-solid fa-building text-lg"></i>
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-800 dark:text-white leading-tight">{{ $unit['name'] }}</p>
                                        <p class="text-gray-400 dark:text-gray-400 mt-1 font-mono text-[10px]">ID: {{ $unit['id'] }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-5 px-4 text-center">
                                <span class="bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-gray-200 px-3 py-1 rounded-full font-bold text-[10px] whitespace-nowrap">{{ $unit['count'] }}</span>
                            </td>
                            <td class="py-5 px-4 text-gray-500 dark:text-gray-400 max-w-xs truncate italic">{{ $unit['desc'] }}</td>
                            <td class="py-5 px-6 text-right">
                                <div class="flex justify-end gap-5 text-gray-400 dark:text-white">
                                    <button @click="openEdit = true" class="hover:text-blue-600 transition-all p-1"><i class="fa-solid fa-pen"></i></button>
                                    <button class="hover:text-red-600 transition-all p-1"><i class="fa-solid fa-trash-can"></i></button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- TABLE DAFTAR TENAGA KERJA --}}
                <table x-show="viewMode === 'tenaga'" x-cloak class="w-full text-left text-xs min-w-[700px]">
                    <thead class="text-gray-500 dark:text-white font-bold border-b dark:border-slate-800 bg-gray-50/50 dark:bg-slate-800/50">
                        <tr>
                            <th class="py-4 px-6 uppercase tracking-wider">Nama Jenis Tenaga</th>
                            <th class="py-4 px-4 uppercase tracking-wider text-center">Jumlah Karyawan</th>
                            <th class="py-4 px-4 uppercase tracking-wider">Keterangan</th>
                            <th class="py-4 px-6 uppercase tracking-wider text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-800 text-gray-700 dark:text-white">
                        @php
                            $staffTypes = [
                                ['name' => 'Dokter Spesialis', 'count' => '120 Personel', 'qual' => 'Pendidikan Spesialis (Sp.X)'],
                                ['name' => 'Perawat Medis', 'count' => '350 Personel', 'qual' => 'S1 Keperawatan + Ners'],
                                ['name' => 'Apoteker', 'count' => '45 Personel', 'qual' => 'S1 Farmasi + Profesi'],
                                ['name' => 'Tenaga Administrasi', 'count' => '85 Personel', 'qual' => 'D3/S1 Umum'],
                                ['name' => 'Radiografer', 'count' => '15 Personel', 'qual' => 'D3/D4 Radiologi'],
                            ];
                        @endphp
                        @foreach($staffTypes as $staff)
                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-800 transition">
                            <td class="py-5 px-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 bg-emerald-50 dark:bg-emerald-900/30 rounded-lg flex items-center justify-center flex-shrink-0 text-emerald-500">
                                        <i class="fa-solid fa-user-doctor text-lg"></i>
                                    </div>
                                    <p class="font-bold text-gray-800 dark:text-white leading-tight">{{ $staff['name'] }}</p>
                                </div>
                            </td>
                            <td class="py-5 px-4 text-center">
                                <span class="bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-gray-200 px-3 py-1 rounded-full font-bold text-[10px]">{{ $staff['count'] }}</span>
                            </td>
                            <td class="py-5 px-4 text-gray-500 dark:text-gray-400 italic">{{ $staff['qual'] }}</td>
                            <td class="py-5 px-6 text-right">
                                <div class="flex justify-end gap-5 text-gray-400 dark:text-white">
                                    <button @click="openEdit = true" class="hover:text-blue-600 transition-all p-1"><i class="fa-solid fa-pen"></i></button>
                                    <button class="hover:text-red-600 transition-all p-1"><i class="fa-solid fa-trash-can"></i></button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    {{-- MODAL TAMBAH --}}
    <div x-show="openTambah" class="fixed inset-0 z-[60] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4" x-transition x-cloak>
        <div @click.away="openTambah = false" class="bg-white dark:bg-slate-900 w-full max-w-2xl rounded-2xl shadow-2xl overflow-hidden transition-all duration-300">
            <div class="flex justify-between items-center px-8 py-5 border-b dark:border-slate-800">
                <h2 class="text-base font-bold text-gray-800 dark:text-white" x-text="viewMode === 'unit' ? 'Tambah Unit Kerja' : 'Tambah Tenaga Kerja'"></h2>
                <button @click="openTambah = false" class="text-gray-400 hover:text-gray-800 dark:hover:text-white transition"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
            <div class="p-8">
                <form action="#" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-white mb-2 uppercase tracking-tight" x-text="viewMode === 'unit' ? 'Nama Unit Kerja' : 'Nama Jenis Tenaga'"></label>
                        <input type="text" :placeholder="viewMode === 'unit' ? 'Contoh: Unit Bedah' : 'Contoh: Perawat Spesialis'" class="w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg h-12 px-4 text-sm text-gray-700 dark:text-white outline-none focus:ring-2 focus:ring-blue-500/10">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-white mb-2 uppercase tracking-tight" x-text="viewMode === 'unit' ? 'Keterangan' : 'Keterangan'"></label>
                        <textarea rows="4" :placeholder="viewMode === 'unit' ? 'Keterangan...' : 'Keterangan...'" class="w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg p-4 text-sm text-gray-700 dark:text-white resize-none outline-none focus:ring-2 focus:ring-blue-500/10"></textarea>
                    </div>
                    <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4">
                        <button @click="openTambah = false" type="button" class="w-full sm:w-auto px-8 py-2.5 border dark:border-slate-700 text-gray-500 dark:text-white font-bold rounded-lg hover:bg-gray-50 dark:hover:bg-slate-800 transition text-xs">Batal</button>
                        <button type="submit" class="w-full sm:w-auto px-8 py-2.5 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition text-xs">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL EDIT --}}
    <div x-show="openEdit" class="fixed inset-0 z-[60] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4" x-transition x-cloak>
        <div @click.away="openEdit = false" class="bg-white dark:bg-slate-900 w-full max-w-2xl rounded-2xl shadow-2xl overflow-hidden transition-all duration-300">
            <div class="flex justify-between items-center px-8 py-5 border-b dark:border-slate-800">
                <h2 class="text-base font-bold text-gray-800 dark:text-white" x-text="viewMode === 'unit' ? 'Edit Unit Kerja' : 'Edit Tenaga Kerja'"></h2>
                <button @click="openEdit = false" class="text-gray-400 hover:text-gray-800 dark:hover:text-white transition"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
            <div class="p-8">
                <form action="#" method="POST" class="space-y-6">
                    @csrf @method('PUT')
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-white mb-2 uppercase" x-text="viewMode === 'unit' ? 'Nama Unit Kerja' : 'Jenis Tenaga Kerja'"></label>
                        <input type="text" class="w-full bg-white dark:bg-slate-800 border dark:border-slate-700 rounded-lg h-12 px-4 text-sm dark:text-white">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-white mb-2 uppercase tracking-tight" x-text="viewMode === 'unit' ? 'Keterangan' : 'Keterangan'"></label>
                        <textarea rows="4" :placeholder="viewMode === 'unit' ? 'Keterangan unit...' : 'Keterangan...'" class="w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg p-4 text-sm text-gray-700 dark:text-white resize-none outline-none focus:ring-2 focus:ring-blue-500/10"></textarea>
                    </div>
                    <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4">
                        <button @click="openEdit = false" type="button" class="w-full sm:w-auto px-8 py-2.5 border dark:border-slate-700 text-gray-500 dark:text-white font-bold rounded-lg text-xs">Batal</button>
                        <button type="submit" class="w-full sm:w-auto px-8 py-2.5 bg-blue-600 text-white font-bold rounded-lg text-xs">Simpan Pengeditan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 10px; }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; }
</style>
@endsection