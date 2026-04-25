@extends('components.layout')
@section('title', 'Manajemen Kategori')

@section('content')
<div class="flex h-screen overflow-hidden bg-slate-50 dark:bg-slate-950 transition-colors duration-300" 
    x-data="{ 
        openTambah: false, 
        openEdit: false, 
        sidebarOpen: false, 
        darkMode: localStorage.getItem('theme') === 'dark',
        selectedCategory: {
            id: '',
            nama_kategori: '',
            keterangan: ''
        },
        editCategory(cat) {
            this.selectedCategory = {
                id: cat.kategori_id,
                nama_kategori: cat.nama_kategori,
                keterangan: cat.keterangan || ''
            };
            this.openEdit = true;
        }
    }">
    
    <aside id="sidebar"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed inset-y-0 left-0 z-50 w-64 h-screen bg-white dark:bg-slate-900 border-r dark:border-slate-800 transition-transform duration-300 lg:translate-x-0 lg:static lg:inset-0 shrink-0 overflow-y-auto">
        @include('components.nav-superadmin')
    </aside>

    <div x-show="sidebarOpen" 
         @click="sidebarOpen = false" 
         x-transition:enter="transition opacity-100 ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:leave="transition opacity-100 ease-in duration-200"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-40 bg-black/50 backdrop-blur-sm lg:hidden" x-cloak>
    </div>

    <div class="flex-1 flex flex-col min-w-0 transition-colors duration-300">
        
        <header class="bg-white dark:bg-slate-900 border-b dark:border-slate-800 h-16 flex items-center justify-between px-4 lg:px-8 shrink-0 z-10 transition-colors duration-300">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = true" class="lg:hidden p-2 text-gray-500 dark:text-white">
                    <i class="fa-solid fa-bars text-lg"></i>
                </button>
                <h1 class="text-sm font-semibold text-gray-600 dark:text-white truncate">Manajemen Kategori</h1>
            </div>

            <div class="flex items-center gap-3 lg:gap-4">
                <div class="">
                    @include('components.notif-superadmin')
                </div>
                <div class="flex items-center gap-3 pl-2 lg:pl-4 border-l border-gray-100 dark:border-slate-800">
                    <div class="text-right hidden sm:block">
                        <p class="text-xs font-bold text-gray-800 dark:text-white leading-tight">Superadmin</p>
                        <p class="text-[10px] text-gray-500 dark:text-gray-300 font-medium italic">Utama</p>
                    </div>
                    <div class="w-8 h-8 bg-gray-200 dark:bg-slate-700 rounded-full overflow-hidden border border-gray-100 dark:border-slate-800 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-user text-gray-500 dark:text-white text-xs"></i>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-4 lg:p-8 custom-scrollbar">
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
                <div class="bg-white dark:bg-slate-900 p-5 rounded-xl border border-gray-100 dark:border-slate-800 shadow-sm flex items-center justify-between transition-all hover:shadow-md group">
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 dark:text-white uppercase tracking-wider transition-colors">Total Kategori</p>
                        <h3 class="text-xl font-bold text-gray-800 dark:text-white transition-colors">{{ $totalKategori }}</h3>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center shadow-sm shadow-blue-200 dark:shadow-none">
                        <i class="fa-solid fa-tags text-white text-xs"></i>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-900 p-5 rounded-xl border border-gray-100 dark:border-slate-800 shadow-sm flex items-center justify-between transition-all hover:shadow-md group">
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 dark:text-white uppercase tracking-wider transition-colors">Total Pelatihan</p>
                        <h3 class="text-xl font-bold text-gray-800 dark:text-white transition-colors">{{ $totalPelatihan }}</h3>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-emerald-500 flex items-center justify-center shadow-sm shadow-emerald-200 dark:shadow-none">
                        <i class="fa-solid fa-book text-white text-xs"></i>
                    </div>
                </div>

                <div class="bg-transparent p-5 rounded-xl border-2 border-dashed border-gray-200 dark:border-slate-800 flex items-center transition-colors sm:col-span-2 lg:col-span-1">
                    <p class="text-[10px] text-gray-400 dark:text-gray-300 leading-relaxed italic transition-colors">
                        Gunakan kategori untuk mengelompokkan materi pelatihan agar memudahkan staf medis dalam pencarian modul yang relevan.
                    </p>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-800 text-emerald-600 dark:text-emerald-400 text-xs font-bold rounded-xl flex items-center gap-3">
                    <i class="fa-solid fa-circle-check"></i>
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 p-4 lg:p-8 mb-10 transition-colors duration-300">
                <div class="flex flex-col sm:flex-row justify-between items-start gap-4 mb-6">
                    <div>
                        <h2 class="text-lg font-bold text-gray-800 dark:text-white transition-colors uppercase tracking-tight">Daftar Kategori Pelatihan</h2>
                        <p class="text-xs lg:text-sm text-gray-500 dark:text-gray-300 mt-1 transition-colors leading-relaxed">Kelola klasifikasi taksonomi materi pembelajaran rumah sakit.</p>
                    </div>
                    <button @click="openTambah = true" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg flex items-center justify-center gap-2 text-sm font-bold transition shadow-sm active:scale-95">
                        <i class="fa-solid fa-plus text-xs"></i>
                        Tambah Kategori
                    </button>
                </div>

                <div class="mb-6">
                    <div class="relative w-full">
                        <form action="{{ route('manajemen-kategori') }}" method="GET" class="w-full">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 dark:text-white transition-colors">
                                <i class="fa-solid fa-magnifying-glass text-xs"></i>
                            </span>
                            <input type="text" name="search" value="{{ request('search') }}"
                                class="block w-full pl-9 pr-3 py-2.5 border border-gray-200 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-xs transition-all placeholder:dark:text-gray-400" 
                                placeholder="Cari nama kategori...">
                        </form>
                    </div>
                </div>

                <div class="overflow-x-auto border dark:border-slate-800 rounded-lg transition-colors">
                    <table class="w-full text-left text-xs min-w-[600px]">
                        <thead class="text-gray-500 dark:text-white font-bold bg-gray-50/50 dark:bg-slate-800/50 border-b dark:border-slate-800 transition-colors">
                            <tr>
                                <th class="py-4 px-6">Nama Kategori</th>
                                <th class="py-4 px-4 text-center">Jumlah Pelatihan</th>
                                <th class="py-4 px-4 text-center">Terakhir Diperbarui</th>
                                <th class="py-4 px-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-slate-800 text-gray-700 dark:text-white transition-colors">
                            @forelse($categories as $cat)
                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors">
                                <td class="py-5 px-6 font-bold text-gray-800 dark:text-white">{{ $cat->nama_kategori }}</td>
                                <td class="py-5 px-4 text-center">
                                    <span class="bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-gray-200 px-3 py-1 rounded-full font-bold text-[10px] whitespace-nowrap transition-colors border dark:border-slate-700">
                                        {{ $cat->materis_count }} Pelatihan
                                    </span>
                                </td>
                                <td class="py-5 px-4 text-center text-gray-500 dark:text-gray-300 italic transition-colors">
                                    {{ $cat->updated_at ? $cat->updated_at->format('d M Y') : '-' }}
                                </td>
                                <td class="py-5 px-6 text-right">
                                    <div class="flex justify-end gap-3 text-gray-400 dark:text-white">
                                        <button @click="editCategory({{ json_encode($cat) }})" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors p-1"><i class="fa-solid fa-pen"></i></button>
                                        
                                        <form action="{{ route('manajemen-kategori.destroy', $cat->kategori_id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="hover:text-red-600 dark:hover:text-red-400 transition-colors p-1"><i class="fa-solid fa-trash-can"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-10 text-center text-gray-500 dark:text-gray-400 font-medium italic">
                                    Data Kategori tidak ditemukan
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-8">
                    {{ $categories->appends(['search' => request('search')])->links('vendor.pagination.custom-tailwind') }}
                </div>
            </div>
        </main>
    </div>

    {{-- MODAL TAMBAH KATEGORI --}}
    <div x-show="openTambah" 
        class="fixed inset-0 z-60 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        x-cloak>
        
        <div @click.away="openTambah = false" class="bg-white dark:bg-slate-900 w-full max-w-3xl rounded-2xl shadow-2xl overflow-hidden transition-colors duration-300">
            <div class="flex justify-between items-center px-6 lg:px-8 py-5 border-b border-gray-100 dark:border-slate-800 transition-colors">
                <h2 class="text-base font-bold text-gray-800 dark:text-white">Tambah Kategori</h2>
                <button @click="openTambah = false" class="text-gray-400 hover:text-gray-800 dark:hover:text-white transition-colors">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <div class="p-6 lg:p-8">
                <form action="{{ route('manajemen-kategori.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-white mb-2 uppercase tracking-tight transition-colors">Nama Kategori</label>
                        <input type="text" name="nama_kategori" placeholder="Contoh: Keperawatan Dasar" required
                            class="w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg h-12 px-4 focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all text-sm text-gray-700 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-white mb-2 uppercase tracking-tight transition-colors">Keterangan</label>
                        <textarea name="keterangan" placeholder="Masukkan keterangan kategori..." 
                            class="w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg p-4 focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all text-sm text-gray-700 dark:text-white min-h-[100px]"></textarea>
                    </div>

                    <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4 transition-colors">
                        <button @click="openTambah = false" type="button" 
                                class="w-full sm:w-auto px-8 py-2.5 border border-gray-200 dark:border-slate-700 text-gray-500 dark:text-white text-xs font-bold rounded-lg hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors">
                            Batal
                        </button>
                        <button type="submit" 
                                class="w-full sm:w-auto px-8 py-2.5 bg-blue-600 text-white text-xs font-bold rounded-lg hover:bg-blue-700 shadow-lg shadow-blue-100 dark:shadow-none transition-all active:scale-95">
                            Simpan Kategori
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL EDIT KATEGORI --}}
    <div x-show="openEdit" 
        class="fixed inset-0 z-60 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        x-cloak>
        
        <div @click.away="openEdit = false" class="bg-white dark:bg-slate-900 w-full max-w-3xl rounded-2xl shadow-2xl overflow-hidden transition-colors duration-300">
            <div class="flex justify-between items-center px-6 lg:px-8 py-5 border-b border-gray-100 dark:border-slate-800 transition-colors">
                <h2 class="text-base font-bold text-gray-800 dark:text-white">Edit Kategori</h2>
                <button @click="openEdit = false" class="text-gray-400 hover:text-gray-800 dark:hover:text-white transition-colors">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <div class="p-6 lg:p-8">
                <form :action="'{{ url('/manajemen-kategori') }}/' + selectedCategory.id" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT') 
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-white mb-2 uppercase tracking-tight transition-colors">Nama Kategori</label>
                        <input type="text" name="nama_kategori" x-model="selectedCategory.nama_kategori" required
                            class="w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg h-12 px-4 focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all text-sm text-gray-700 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-white mb-2 uppercase tracking-tight transition-colors">Keterangan</label>
                        <textarea name="keterangan" x-model="selectedCategory.keterangan"
                            class="w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg p-4 focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all text-sm text-gray-700 dark:text-white min-h-[100px]"></textarea>
                    </div>

                    <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4 transition-colors">
                        <button @click="openEdit = false" type="button" 
                                class="w-full sm:w-auto px-8 py-2.5 border border-gray-200 dark:border-slate-700 text-gray-500 dark:text-white text-xs font-bold rounded-lg hover:bg-gray-50 dark:hover:bg-slate-800 transition-colors">
                            Batal
                        </button>
                        <button type="submit" 
                                class="w-full sm:w-auto px-8 py-2.5 bg-blue-600 text-white text-xs font-bold rounded-lg hover:bg-blue-700 shadow-lg shadow-blue-100 dark:shadow-none transition-all active:scale-95">
                            Simpan Pengeditan
                        </button>
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