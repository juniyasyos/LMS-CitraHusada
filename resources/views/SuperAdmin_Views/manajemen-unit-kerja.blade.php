@extends('components.layout')
@section('title', 'Manajemen Master Data')

@section('content')
{{-- State Management dengan Alpine.js --}}
<div class="flex h-screen overflow-hidden bg-slate-50 dark:bg-slate-950 transition-colors duration-300" 
     x-data="{ 
        openTambah: false, 
        openEdit: false, 
        sidebarOpen: false, 
        darkMode: localStorage.getItem('theme') === 'dark',
        openDropdown: false,
        editData: { id: '', name: '', desc: '' }
     }">
    
    {{-- SIDEBAR RESPONSIVE --}}
    <aside 
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-slate-900 border-r dark:border-slate-800 transition-transform duration-300 lg:translate-x-0 lg:static lg:inset-0 shrink-0 overflow-y-auto">
        @include('components.nav-superadmin')
    </aside>

    {{-- OVERLAY MOBILE --}}
    <div x-show="sidebarOpen" 
         @click="sidebarOpen = false" 
         x-transition:enter="transition opacity-100 ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:leave="transition opacity-100 ease-in duration-200"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-40 bg-black/50 backdrop-blur-sm lg:hidden" x-cloak></div>

    <div class="flex-1 flex flex-col min-w-0 transition-colors duration-300">
        
        {{-- HEADER --}}
        @include('components.header-superadmin', ['title' => 'Manajemen Unit Kerja'])

        <main class="flex-1 overflow-y-auto p-4 lg:p-8 custom-scrollbar">
            


            {{-- TITLE SECTION DENGAN SWITCHER --}}
            <div class="flex flex-col sm:flex-row justify-between items-start gap-4 mb-8">
                <div class="relative">
                    <button @click="openDropdown = !openDropdown" class="flex items-center gap-2 group">
                        <h2 class="text-lg lg:text-xl font-bold text-gray-800 dark:text-white transition-colors">
                            {{ $title }}
                        </h2>
                        <i class="fa-solid fa-chevron-down text-xs text-gray-400 group-hover:text-blue-500 transition-colors mt-1"></i>
                    </button>
                    
                    {{-- Dropdown Menu Switcher --}}
                    <div x-show="openDropdown" @click.away="openDropdown = false" x-cloak 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         class="absolute left-0 mt-2 w-56 bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-xl shadow-xl z-20 overflow-hidden">
                        <a href="{{ route('manajemen-unit-kerja', ['type' => 'unit']) }}" 
                           class="block w-full text-left px-4 py-3 text-xs font-bold text-gray-600 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:text-blue-600 transition-colors {{ $type === 'unit' ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600' : '' }}">
                            Daftar Unit Kerja
                        </a>
                        <a href="{{ route('manajemen-unit-kerja', ['type' => 'tenaga']) }}" 
                           class="block w-full text-left px-4 py-3 text-xs font-bold text-gray-600 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:text-blue-600 transition-colors {{ $type === 'tenaga' ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600' : '' }}">
                            Daftar Jenis Tenaga
                        </a>
                    </div>

                    <p class="text-xs lg:text-sm text-gray-500 dark:text-gray-300 mt-1 leading-relaxed max-w-2xl">
                        @if($type === 'unit')
                            Kelola struktur organisasi rumah sakit untuk penugasan pelatihan yang tepat secara sistematis.
                        @else
                            Kelola klasifikasi jenis tenaga medis dan staf rumah sakit untuk pemetaan kompetensi.
                        @endif
                    </p>
                </div>

                <button @click="openTambah = true" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg flex items-center justify-center gap-2 text-sm font-bold transition shadow-sm active:scale-95">
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>{{ $type === 'unit' ? 'Tambah Unit' : 'Tambah Tenaga' }}</span>
                </button>
            </div>

            {{-- TOOLBAR & SEARCH --}}
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <p class="text-xs lg:text-sm text-gray-500 dark:text-gray-200 italic font-medium transition-colors">
                    <span class="font-bold text-gray-700 dark:text-white transition-colors">Informasi {{ $type === 'unit' ? 'Unit' : 'Tenaga' }}:</span> 
                    Total <span class="font-bold text-blue-600">{{ $total }}</span> data terdaftar.
                </p>
                <div class="relative w-full sm:w-72">
                    <form action="{{ route('manajemen-unit-kerja') }}" method="GET" id="searchForm">
                        <input type="hidden" name="type" value="{{ $type }}">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 dark:text-gray-500">
                            <i class="fa-solid fa-magnifying-glass text-xs"></i>
                        </span>
                        <input type="text" name="search" id="searchInput" value="{{ request('search') }}"
                            class="block w-full pl-9 pr-3 py-2.5 border border-gray-200 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-xs text-gray-700 dark:text-white outline-none focus:ring-2 focus:ring-blue-500/20 transition-all placeholder:dark:text-gray-500" 
                            placeholder="Cari data...">
                    </form>
                </div>
            </div>

            {{-- TABLE WRAPPER --}}
            <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 overflow-x-auto mb-8 transition-colors duration-300">
                <table class="w-full text-left text-xs min-w-[800px]">
                    <thead class="text-gray-500 dark:text-white font-bold border-b dark:border-slate-800 bg-gray-50/50 dark:bg-slate-800/50 transition-colors uppercase tracking-wider">
                        <tr>
                            <th class="py-4 px-6">{{ $type === 'unit' ? 'Nama Unit Kerja' : 'Jenis Tenaga' }}</th>
                            <th class="py-4 px-4 text-center">Jumlah Karyawan</th>
                            <th class="py-4 px-4">Keterangan</th>
                            <th class="py-4 px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-800 text-gray-700 dark:text-white transition-colors">
                        @forelse($data as $item)
                        @php
                            $id = $type === 'unit' ? $item->unit_kerja_id : $item->jenis_tenaga_id;
                            $name = $type === 'unit' ? $item->unit_kerja : $item->jenis_tenaga;
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-800 transition duration-150">
                            <td class="py-5 px-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 {{ $type === 'unit' ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-500' : 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-500' }} rounded-lg flex items-center justify-center shrink-0 shadow-inner">
                                        <i class="fa-solid {{ $type === 'unit' ? 'fa-building' : 'fa-user-doctor' }} text-lg"></i>
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-800 dark:text-white leading-tight text-sm tracking-tight">{{ $name }}</p>
                                        <p class="text-gray-400 dark:text-gray-500 mt-1 font-mono text-[10px] italic">ID: {{ $id }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-5 px-4 text-center">
                                <span class="bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-gray-200 px-3 py-1.5 rounded-full font-bold text-[10px] whitespace-nowrap shadow-sm">
                                    {{ $item->users_count }} Personel
                                </span>
                            </td>
                            <td class="py-5 px-4 text-gray-500 dark:text-gray-400 max-w-xs truncate italic">
                                {{ $item->deskripsi ?? 'Tidak ada deskripsi tambahan.' }}
                            </td>
                            <td class="py-5 px-6 text-right">
                                <div class="flex justify-end gap-5 text-gray-400 dark:text-white">
                                    {{-- Edit Button --}}
                                    <button @click="openEdit = true; editData = { id: '{{ $id }}', name: '{{ $name }}', desc: '{{ $item->deskripsi }}' }" 
                                            class="hover:bg-blue-50 dark:hover:bg-blue-900/20 text-blue-500 rounded-lg transition-all active:scale-90" title="Edit Data">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>

                                    {{-- Delete Button --}}
                                    <form action="{{ route('manajemen-unit-kerja.destroy', $id) }}" method="POST" class="inline-block delete-form">
                                        @csrf @method('DELETE')
                                        <input type="hidden" name="type" value="{{ $type }}">
                                        <button type="button" onclick="confirmDelete(this)" class="hover:bg-red-50 dark:hover:bg-red-900/20 text-red-500 rounded-lg transition-all active:scale-90" title="Hapus Data">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-12 text-center text-gray-400 dark:text-gray-500 italic font-medium">
                                <div class="flex flex-col items-center gap-2">
                                    <i class="fa-solid fa-inbox text-4xl opacity-20"></i>
                                    <p>Data tidak ditemukan</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            <div class="mt-4 mb-8">
                {{ $data->links('vendor.pagination.custom-tailwind') }}
            </div>

            {{-- TIPS SECTION --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-gray-100 dark:border-slate-800 shadow-sm flex gap-4 transition-all hover:shadow-md">
                    <div class="w-10 h-10 bg-blue-50 dark:bg-blue-900/30 rounded-full shrink-0 flex items-center justify-center">
                        <i class="fa-solid fa-circle-info text-blue-600"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-gray-800 dark:text-white uppercase tracking-tight">Integrasi Sistem</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 leading-relaxed transition-colors">Data ini disinkronkan langsung dengan modul Manajemen Pengguna dan Pelatihan.</p>
                    </div>
                </div>
                <div class="bg-blue-600 p-6 rounded-xl shadow-lg shadow-blue-100 dark:shadow-none flex gap-4 transition-all hover:bg-blue-700">
                    <div class="w-10 h-10 bg-white/20 rounded-full shrink-0 flex items-center justify-center">
                        <i class="fa-solid fa-bullseye text-white"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-white uppercase tracking-tight">Fungsi Utama</h4>
                        <p class="text-xs text-blue-100 mt-1 leading-relaxed">Digunakan sebagai parameter utama dalam pemetaan materi pelatihan yang relevan.</p>
                    </div>
                </div>
                <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-gray-100 dark:border-slate-800 shadow-sm flex gap-4 transition-all hover:shadow-md">
                    <div class="w-10 h-10 bg-emerald-50 dark:bg-emerald-900/30 rounded-full shrink-0 flex items-center justify-center">
                        <i class="fa-solid fa-hospital text-emerald-600"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-gray-800 dark:text-white uppercase tracking-tight">Sesuai Standar</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 leading-relaxed transition-colors">Pastikan input data sesuai dengan data kepegawaian resmi rumah sakit.</p>
                    </div>
                </div>
            </div>
        </main>
    </div>

    {{-- MODAL TAMBAH --}}
    <div x-show="openTambah" 
         class="fixed inset-0 z-60 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         x-cloak>
        <div @click.away="openTambah = false" class="bg-white dark:bg-slate-900 w-full max-w-2xl rounded-2xl shadow-2xl overflow-hidden transition-all duration-300">
            <div class="flex justify-between items-center px-8 py-6 border-b border-gray-100 dark:border-slate-800">
                <h2 class="text-base font-bold text-gray-800 dark:text-white">
                    Tambah {{ $type === 'unit' ? 'Unit Kerja' : 'Jenis Tenaga' }} Baru
                </h2>
                <button @click="openTambah = false" class="text-gray-400 hover:text-gray-800 dark:hover:text-white transition">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
            <div class="p-8">
                <form action="{{ route('manajemen-unit-kerja.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" name="type" value="{{ $type }}">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-white mb-2 uppercase tracking-tight">
                            Nama {{ $type === 'unit' ? 'Unit Kerja' : 'Jenis Tenaga' }}
                        </label>
                        <input type="text" name="nama" required placeholder="Masukkan nama..." 
                               class="w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl h-12 px-4 text-sm text-gray-700 dark:text-white outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-white mb-2 uppercase tracking-tight">Keterangan / Deskripsi</label>
                        <textarea name="deskripsi" rows="4" placeholder="Berikan deskripsi singkat..." 
                                  class="w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl p-4 text-sm text-gray-700 dark:text-white resize-none outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all"></textarea>
                    </div>
                    <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4">
                        <button @click="openTambah = false" type="button" class="w-full sm:w-auto px-8 py-2.5 border border-gray-200 dark:border-slate-700 text-gray-500 dark:text-white font-bold rounded-lg hover:bg-gray-50 dark:hover:bg-slate-800 transition text-xs">Batal</button>
                        <button type="submit" class="w-full sm:w-auto px-8 py-2.5 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 shadow-blue-100 transition text-xs active:scale-95">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL EDIT --}}
    <div x-show="openEdit" 
         class="fixed inset-0 z-60 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         x-cloak>
        <div @click.away="openEdit = false" class="bg-white dark:bg-slate-900 w-full max-w-2xl rounded-2xl shadow-2xl overflow-hidden transition-all duration-300">
            <div class="flex justify-between items-center px-8 py-6 border-b border-gray-100 dark:border-slate-800">
                <h2 class="text-base font-bold text-gray-800 dark:text-white">
                    Edit {{ $type === 'unit' ? 'Unit Kerja' : 'Jenis Tenaga' }}
                </h2>
                <button @click="openEdit = false" class="text-gray-400 hover:text-gray-800 dark:hover:text-white transition">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
            <div class="p-8">
                <form :action="'/manajemen-unit-kerja/' + editData.id" method="POST" class="space-y-6">
                    @csrf @method('PUT')
                    <input type="hidden" name="type" value="{{ $type }}">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-white mb-2 uppercase tracking-tight">
                            Nama {{ $type === 'unit' ? 'Unit Kerja' : 'Jenis Tenaga' }}
                        </label>
                        <input type="text" name="nama" x-model="editData.name" required
                               class="w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl h-12 px-4 text-sm text-gray-700 dark:text-white outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-white mb-2 uppercase tracking-tight">Keterangan / Deskripsi</label>
                        <textarea name="deskripsi" rows="4" x-model="editData.desc"
                                  class="w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl p-4 text-sm text-gray-700 dark:text-white resize-none outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all"></textarea>
                    </div>
                    <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4">
                        <button @click="openEdit = false" type="button" class="w-full sm:w-auto px-8 py-2.5 border border-gray-200 dark:border-slate-700 text-gray-500 dark:text-white font-bold rounded-lg text-xs">Batal</button>
                        <button type="submit" class="w-full sm:w-auto px-8 py-2.5 bg-blue-600 text-white font-bold rounded-lg shadow-blue-100 transition text-xs active:scale-95">Simpan Perubahan</button>
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

<script>
    // Search Debounce
    let searchInput = document.getElementById('searchInput');
    let timeout = null;
    searchInput.addEventListener('input', function() {
        clearTimeout(timeout);
        timeout = setTimeout(function() {
            document.getElementById('searchForm').submit();
        }, 800);
    });

    // Delete Confirmation
    function confirmDelete(button) {
        const form = button.closest('form');
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data yang dihapus mungkin memengaruhi penugasan karyawan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-2xl',
                confirmButton: 'rounded-lg px-6 py-2.5 text-xs font-bold',
                cancelButton: 'rounded-lg px-6 py-2.5 text-xs font-bold'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }
</script>
@endsection