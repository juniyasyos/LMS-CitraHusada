@extends('components.layout')
@section('title', 'Manajemen Pelatihan')

@section('content')
<div class="flex h-screen overflow-hidden bg-slate-50 dark:bg-slate-950 transition-colors duration-300"
    x-data="manajemenPelatihanData()" x-init="initData()">

    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-slate-900 border-r dark:border-slate-800 transition-transform duration-300 lg:translate-x-0 lg:static lg:inset-0 shrink-0 overflow-y-auto">
        @include('components.nav-superadmin')
    </aside>

    <div x-show="sidebarOpen" @click="sidebarOpen = false"
        x-transition:enter="transition opacity-100 ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:leave="transition opacity-100 ease-in duration-200" x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-40 bg-black/50 backdrop-blur-sm lg:hidden" x-cloak></div>

    <div class="flex-1 flex flex-col min-w-0 transition-colors duration-300">
        @include('components.header-superadmin', ['title' => 'Manajemen Media'])

        <main class="flex-1 overflow-y-auto p-4 lg:p-8 custom-scrollbar">
            <div class="flex flex-col sm:flex-row justify-between items-start gap-4 mb-6">
                <div>
                    <h2 class="text-lg lg:text-xl font-bold text-gray-800 dark:text-white transition-colors">Manajemen Pelatihan</h2>
                    <p class="text-xs lg:text-sm text-gray-500 dark:text-gray-300 mt-1 leading-relaxed">Kelola media pelatihan rumah sakit untuk penugasan yang tepat.</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route('pelatihan.arsip') }}"
                        class="bg-amber-500 hover:bg-amber-600 text-white px-4 py-2.5 rounded-lg flex items-center gap-2 text-xs font-bold transition shadow-sm active:scale-95">
                        <i class="fa-solid fa-box-archive text-xs"></i> Arsip
                    </a>
                    <a href="{{ route('pelatihan.trash') }}"
                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2.5 rounded-lg flex items-center gap-2 text-xs font-bold transition shadow-sm active:scale-95">
                        <i class="fa-solid fa-trash text-xs"></i> Sampah
                    </a>
                    <button @click="openTambah()"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg flex items-center gap-2 text-xs font-bold transition shadow-sm active:scale-95">
                        <i class="fa-solid fa-plus text-xs"></i> Tambah Folder
                    </button>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 p-4 lg:p-6 mb-10 transition-colors duration-300">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-8">
                    <div class="w-full md:w-auto relative">
                        <select x-model="sortOrder" @change="fetchData(1)"
                            class="appearance-none w-full md:w-auto bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg px-4 py-2 pr-10 text-xs font-medium text-gray-600 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 cursor-pointer transition-all">
                            <option value="terbaru">Terbaru</option>
                            <option value="terlama">Terlama</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-400 dark:text-white">
                            <i class="fa-solid fa-chevron-down text-[10px]"></i>
                        </div>
                    </div>
                    <div class="relative w-full md:w-64">
                        <span class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 dark:text-white">
                            <i class="fa-solid fa-magnifying-glass text-xs"></i>
                        </span>
                        <input type="text" x-model="searchQuery" @input.debounce.500ms="fetchData(1)"
                            class="block w-full pl-4 pr-10 py-2 border border-gray-200 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 text-xs transition-all placeholder:dark:text-gray-400"
                            placeholder="Cari pelatihan...">
                    </div>
                </div>

                <!-- Loading State -->
                <div x-show="isLoading" class="flex justify-center items-center py-20">
                    <i class="fa-solid fa-spinner fa-spin text-3xl text-blue-500"></i>
                </div>

                <!-- Grid -->
                <div x-show="!isLoading" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-6">
                    <template x-for="materi in materis" :key="materi.materi_id">
                        <div class="relative group" x-data="{ menuOpen: false }">
                            <a :href="'/daftar-materi-kuis/' + materi.materi_id"
                                class="block border border-gray-100 dark:border-slate-800 rounded-xl overflow-hidden hover:shadow-md transition-all active:scale-[0.98] bg-white dark:bg-slate-900">
                                <div class="p-6 flex items-center justify-center bg-gray-50 dark:bg-slate-800/50 group-hover:bg-gray-100 dark:group-hover:bg-slate-800 transition-colors">
                                    <i class="fa-solid fa-folder text-amber-400 text-6xl lg:text-7xl group-hover:text-amber-500 transition-colors"></i>
                                </div>
                                <div class="p-4 text-center">
                                    <p class="text-xs font-bold text-gray-700 dark:text-white mb-1 truncate px-2" x-text="materi.judul"></p>
                                    <p class="text-[10px] text-gray-400 dark:text-gray-500 font-medium italic" x-text="formatDate(materi.created_at)"></p>
                                    <template x-if="materi.kategori">
                                        <span class="inline-block mt-2 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 px-2 py-0.5 rounded text-[9px] font-bold" x-text="materi.kategori.nama_kategori"></span>
                                    </template>
                                    <template x-if="!materi.kategori">
                                        <span class="inline-block mt-2 bg-gray-50 dark:bg-slate-800 text-gray-400 dark:text-gray-500 px-2 py-0.5 rounded text-[9px] font-medium italic border border-gray-100 dark:border-slate-700">Tidak ada kategori</span>
                                    </template>
                                </div>
                            </a>

                            <div class="absolute top-2 right-2 z-20">
                                <button @click.prevent="menuOpen = !menuOpen"
                                    class="w-7 h-7 rounded-full bg-white/90 dark:bg-slate-800/90 shadow-sm flex items-center justify-center text-gray-500 dark:text-white hover:bg-white dark:hover:bg-slate-700 transition text-xs border border-gray-100 dark:border-slate-700">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>
                                <div x-show="menuOpen" @click.away="menuOpen = false" x-cloak x-transition
                                    class="absolute right-0 mt-1 w-40 bg-white dark:bg-slate-900 rounded-lg shadow-xl border border-gray-100 dark:border-slate-800 py-1 z-30 overflow-hidden">
                                    <button type="button" @click="editFolder(materi); menuOpen = false;"
                                        class="w-full text-left px-4 py-2 text-xs text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors font-bold">
                                        <i class="fa-solid fa-pen mr-2"></i>Edit Folder
                                    </button>
                                    <button type="button" @click="deleteFolder(materi.materi_id); menuOpen = false;"
                                        class="w-full text-left px-4 py-2 text-xs text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                        <i class="fa-solid fa-trash-can mr-2"></i>Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>

                    <template x-if="materis.length === 0">
                        <div class="col-span-full py-16 text-center">
                            <i class="fa-solid fa-folder-open text-5xl text-gray-200 dark:text-slate-700 mb-4"></i>
                            <p class="text-sm text-gray-400 dark:text-gray-500 font-medium">Belum ada data pelatihan.</p>
                        </div>
                    </template>
                </div>

                <!-- Pagination Placeholder -->
                <div class="mt-8 flex justify-center gap-1" x-show="!isLoading && totalPages > 1">
                    <template x-for="page in totalPages" :key="page">
                        <button @click="fetchData(page)"
                            :class="currentPage === page ? 'bg-blue-600 text-white' : 'bg-white dark:bg-slate-800 text-gray-700 dark:text-white border border-gray-200 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-700'"
                            class="px-3 py-1 rounded-md text-xs font-bold transition-colors"
                            x-text="page"></button>
                    </template>
                </div>
            </div>
        </main>
    </div>

    {{-- MODAL TAMBAH FOLDER --}}
    <div x-show="openTambahFolder" @click.self="openTambahFolder = false"
        class="fixed inset-0 z-[60] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" x-cloak>
        <div class="bg-white dark:bg-slate-900 w-full max-w-4xl rounded-2xl shadow-2xl overflow-hidden transition-colors duration-300">
            <div class="flex justify-between items-center px-6 lg:px-8 py-5 border-b border-gray-100 dark:border-slate-800">
                <h2 class="text-base font-bold text-gray-800 dark:text-white">Tambah Folder</h2>
                <button @click="openTambahFolder = false" class="text-gray-400 hover:text-gray-800 dark:hover:text-white transition"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
            <div class="p-6 lg:p-8 max-h-[80vh] overflow-y-auto custom-scrollbar">
                <form @submit.prevent="submitTambah" class="space-y-5">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-white mb-2 uppercase tracking-tight">Nama Materi <span class="text-red-500">*</span></label>
                        <input type="text" x-model="formTambah.judul" required
                            class="w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg h-12 px-4 focus:ring-2 focus:ring-blue-500/10 outline-none transition-all text-sm text-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-white mb-2 uppercase tracking-tight">Sub Judul</label>
                        <input type="text" x-model="formTambah.subjudul"
                        class="w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg h-12 px-4 focus:ring-2 focus:ring-blue-500/10 outline-none transition-all text-sm text-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-white mb-2 uppercase tracking-tight">Deskripsi Pelatihan</label>
                        <textarea x-model="formTambah.deskripsi" rows="3"
                        class="w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg p-4 focus:ring-2 focus:ring-blue-500/10 outline-none transition-all text-sm text-gray-700 dark:text-white resize-none"></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-white mb-2 uppercase tracking-tight">Nama Pemateri <span class="text-red-500">*</span></label>
                        <input type="text" x-model="formTambah.nama_pemateri" required
                            class="w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg h-12 px-4 focus:ring-2 focus:ring-blue-500/10 outline-none transition-all text-sm text-gray-700 dark:text-white">
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 dark:text-white mb-2 uppercase tracking-tight">JPL <span class="text-red-500">*</span></label>
                            <input type="number" x-model="formTambah.jam_pelajaran" required min="1"
                                class="w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg h-12 px-4 text-sm dark:text-white outline-none focus:ring-2 focus:ring-blue-500/10">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 dark:text-white mb-2 uppercase tracking-tight">Tanggal Mulai <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="text" x-model="formTambah.tanggal_upload" required id="tambah_tanggal_upload"
                                    class="datepicker w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg h-12 px-4 pl-10 text-sm dark:text-white outline-none focus:ring-2 focus:ring-blue-500/10">
                                <i class="fa-regular fa-calendar absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 dark:text-white mb-2 uppercase tracking-tight">Tanggal Selesai <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="text" x-model="formTambah.tanggal_selesai" required id="tambah_tanggal_selesai"
                                    class="datepicker w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg h-12 px-4 pl-10 text-sm dark:text-white outline-none focus:ring-2 focus:ring-blue-500/10">
                                <i class="fa-regular fa-calendar absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 dark:text-white mb-2 uppercase tracking-tight">Kategori <span class="text-red-500">*</span></label>
                            <select x-model="formTambah.kategori_id" required
                                class="w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg h-12 px-4 text-sm dark:text-white outline-none focus:ring-2 focus:ring-blue-500/10 cursor-pointer">
                                <option value="">Pilih Kategori</option>
                                <template x-for="kat in kategoris" :key="kat.kategori_id">
                                    <option :value="kat.kategori_id" x-text="kat.nama_kategori"></option>
                                </template>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 dark:text-white mb-2 uppercase tracking-tight">Nomor Sertifikat</label>
                            <input type="text" x-model="formTambah.nomor_surat"
                                class="w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg h-12 px-4 focus:ring-2 focus:ring-blue-500/10 outline-none transition-all text-sm text-gray-700 dark:text-white">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 dark:text-white mb-2 uppercase tracking-tight">Unggah Thumbnail <span class="text-gray-400">(maks 3MB)</span></label>
                            <div class="relative border-2 border-dashed border-gray-200 dark:border-slate-700 rounded-xl p-6 flex flex-col items-center justify-center bg-slate-50/50 dark:bg-slate-800/50 hover:bg-slate-50 transition-colors cursor-pointer">
                                <input type="file" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                    @change="formTambah.thumbnail = $event.target.files[0]">
                                <i class="fa-solid fa-image text-blue-500 text-2xl mb-2"></i>
                                <p class="text-[11px] font-bold text-gray-700 dark:text-white" x-text="formTambah.thumbnail ? formTambah.thumbnail.name : 'Klik untuk upload'"></p>
                                <p class="text-[9px] text-gray-400 mt-1">JPG, PNG, WEBP</p>
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center justify-between gap-3 mb-2">
                                <label class="block text-xs font-bold text-gray-500 dark:text-white uppercase tracking-tight">Unit Kerja Terkait</label>
                                <button type="button" @click="selectAllUnitKerja('tambah')" class="text-blue-600 dark:text-blue-400 text-[10px] font-bold uppercase tracking-widest hover:text-blue-700 dark:hover:text-blue-300 transition">Pilih Semua</button>
                            </div>
                            <div class="border border-gray-200 dark:border-slate-700 rounded-xl p-4 grid grid-cols-2 gap-2 bg-gray-50/30 dark:bg-slate-800/30 max-h-40 overflow-y-auto custom-scrollbar">
                                <template x-for="uk in unitKerjas" :key="uk.unit_kerja_id">
                                    <label class="flex items-center gap-2 cursor-pointer group">
                                        <input type="checkbox" :value="uk.unit_kerja_id" x-model="formTambah.unit_kerja_ids"
                                            class="w-4 h-4 rounded border-gray-300 dark:border-slate-600 text-blue-600">
                                        <span class="text-[11px] font-bold text-gray-600 dark:text-gray-300 group-hover:text-gray-900 transition-colors" x-text="uk.unit_name"></span>
                                    </label>
                                </template>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between gap-3 mb-2">
                            <label class="block text-xs font-bold text-gray-500 dark:text-white uppercase tracking-tight">Jenis Tenaga Terkait</label>
                            <button type="button" @click="selectAllJenisTenaga('tambah')" class="text-blue-600 dark:text-blue-400 text-[10px] font-bold uppercase tracking-widest hover:text-blue-700 dark:hover:text-blue-300 transition">Pilih Semua</button>
                        </div>
                        <div class="border border-gray-200 dark:border-slate-700 rounded-xl p-4 grid grid-cols-2 md:grid-cols-3 gap-2 bg-gray-50/30 dark:bg-slate-800/30 max-h-40 overflow-y-auto custom-scrollbar">
                            <template x-for="jt in jenisTenagas" :key="jt.jenis_tenaga_id">
                                <label class="flex items-center gap-2 cursor-pointer group">
                                    <input type="checkbox" :value="jt.jenis_tenaga_id" x-model="formTambah.jenis_tenaga_ids"
                                        class="w-4 h-4 rounded border-gray-300 dark:border-slate-600 text-blue-600">
                                    <span class="text-[11px] font-bold text-gray-600 dark:text-gray-300 group-hover:text-gray-900 transition-colors" x-text="jt.jenis_tenaga"></span>
                                </label>
                            </template>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4">
                        <button @click="openTambahFolder = false" type="button" class="w-full sm:w-auto px-8 py-2.5 border dark:border-slate-700 text-gray-500 dark:text-white font-bold rounded-lg hover:bg-gray-50 dark:hover:bg-slate-800 transition text-xs">Batal</button>
                        <button type="submit" class="w-full sm:w-auto px-8 py-2.5 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 shadow-lg shadow-blue-100 dark:shadow-none transition text-xs active:scale-95" :disabled="isSubmitting">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL EDIT FOLDER --}}
    <div x-show="openEditFolder" @click.self="openEditFolder = false"
        class="fixed inset-0 z-[60] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" x-cloak>
        <div class="bg-white dark:bg-slate-900 w-full max-w-4xl rounded-2xl shadow-2xl overflow-hidden transition-colors duration-300">
            <div class="flex justify-between items-center px-6 lg:px-8 py-5 border-b border-gray-100 dark:border-slate-800">
                <h2 class="text-base font-bold text-gray-800 dark:text-white">Edit Pelatihan</h2>
                <button @click="openEditFolder = false" class="text-gray-400 hover:text-gray-800 dark:hover:text-white transition"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
            <div class="p-6 lg:p-8 max-h-[80vh] overflow-y-auto custom-scrollbar">
                <form @submit.prevent="submitEdit" class="space-y-5">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-white mb-2 uppercase tracking-tight">Nama Materi <span class="text-red-500">*</span></label>
                        <input type="text" x-model="selectedMateri.judul" required
                            class="w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg h-12 px-4 focus:ring-2 focus:ring-blue-500/10 outline-none transition-all text-sm text-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-white mb-2 uppercase tracking-tight">Sub Judul</label>
                        <input type="text" x-model="selectedMateri.subjudul"
                        class="w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg h-12 px-4 focus:ring-2 focus:ring-blue-500/10 outline-none transition-all text-sm text-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-white mb-2 uppercase tracking-tight">Deskripsi Pelatihan</label>
                        <textarea x-model="selectedMateri.deskripsi" rows="3"
                        class="w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg p-4 focus:ring-2 focus:ring-blue-500/10 outline-none transition-all text-sm text-gray-700 dark:text-white resize-none"></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-white mb-2 uppercase tracking-tight">Nama Pemateri <span class="text-red-500">*</span></label>
                        <input type="text" x-model="selectedMateri.nama_pemateri" required
                            class="w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg h-12 px-4 focus:ring-2 focus:ring-blue-500/10 outline-none transition-all text-sm text-gray-700 dark:text-white">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 dark:text-white mb-2 uppercase tracking-tight">JPL <span class="text-red-500">*</span></label>
                            <input type="number" x-model="selectedMateri.jam_pelajaran" required min="1"
                                class="w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg h-12 px-4 text-sm dark:text-white outline-none focus:ring-2 focus:ring-blue-500/10">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 dark:text-white mb-2 uppercase tracking-tight">Tanggal Mulai <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="text" x-model="selectedMateri.tanggal_upload" required id="edit_tanggal_upload"
                                    class="datepicker w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg h-12 px-4 pl-10 text-sm dark:text-white outline-none focus:ring-2 focus:ring-blue-500/10">
                                <i class="fa-regular fa-calendar absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 dark:text-white mb-2 uppercase tracking-tight">Tanggal Selesai <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="text" x-model="selectedMateri.tanggal_selesai" required id="edit_tanggal_selesai"
                                    class="datepicker w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg h-12 px-4 pl-10 text-sm dark:text-white outline-none focus:ring-2 focus:ring-blue-500/10">
                                <i class="fa-regular fa-calendar absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 dark:text-white mb-2 uppercase tracking-tight">Kategori <span class="text-red-500">*</span></label>
                            <select x-model="selectedMateri.kategori_id" required
                                class="w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg h-12 px-4 text-sm dark:text-white outline-none focus:ring-2 focus:ring-blue-500/10 cursor-pointer">
                                <option value="">Pilih Kategori</option>
                                <template x-for="kat in kategoris" :key="kat.kategori_id">
                                    <option :value="kat.kategori_id" x-text="kat.nama_kategori"></option>
                                </template>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 dark:text-white mb-2 uppercase tracking-tight">Nomor Sertifikat</label>
                            <input type="text" x-model="selectedMateri.nomor_surat"
                                class="w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg h-12 px-4 focus:ring-2 focus:ring-blue-500/10 outline-none transition-all text-sm text-gray-700 dark:text-white">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 dark:text-white mb-2 uppercase tracking-tight">Unggah Thumbnail Baru <span class="text-gray-400">(opsional)</span></label>
                            <div class="relative border-2 border-dashed border-gray-200 dark:border-slate-700 rounded-xl p-6 flex flex-col items-center justify-center bg-slate-50/50 dark:bg-slate-800/50 hover:bg-slate-50 transition-colors cursor-pointer">
                                <input type="file" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                    @change="selectedMateri.thumbnail = $event.target.files[0]">
                                <i class="fa-solid fa-image text-blue-500 text-2xl mb-2"></i>
                                <p class="text-[11px] font-bold text-gray-700 dark:text-white" x-text="selectedMateri.thumbnail ? selectedMateri.thumbnail.name : 'Klik untuk ubah thumbnail'"></p>
                                <p class="text-[9px] text-gray-400 mt-1">JPG, PNG, WEBP</p>
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center justify-between gap-3 mb-2">
                                <label class="block text-xs font-bold text-gray-500 dark:text-white uppercase tracking-tight">Unit Kerja Terkait</label>
                                <button type="button" @click="selectAllUnitKerja('edit')" class="text-blue-600 dark:text-blue-400 text-[10px] font-bold uppercase tracking-widest hover:text-blue-700 dark:hover:text-blue-300 transition">Pilih Semua</button>
                            </div>
                            <div class="border border-gray-200 dark:border-slate-700 rounded-xl p-4 grid grid-cols-2 gap-2 bg-gray-50/30 dark:bg-slate-800/30 max-h-40 overflow-y-auto custom-scrollbar">
                                <template x-for="uk in unitKerjas" :key="uk.unit_kerja_id">
                                    <label class="flex items-center gap-2 cursor-pointer group">
                                        <input type="checkbox" :value="uk.unit_kerja_id" x-model="selectedMateri.unit_kerja_ids"
                                            class="w-4 h-4 rounded border-gray-300 dark:border-slate-600 text-blue-600">
                                        <span class="text-[11px] font-bold text-gray-600 dark:text-gray-300 group-hover:text-gray-900 transition-colors" x-text="uk.unit_name"></span>
                                    </label>
                                </template>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between gap-3 mb-2">
                            <label class="block text-xs font-bold text-gray-500 dark:text-white uppercase tracking-tight">Jenis Tenaga Terkait</label>
                            <button type="button" @click="selectAllJenisTenaga('edit')" class="text-blue-600 dark:text-blue-400 text-[10px] font-bold uppercase tracking-widest hover:text-blue-700 dark:hover:text-blue-300 transition">Pilih Semua</button>
                        </div>
                        <div class="border border-gray-200 dark:border-slate-700 rounded-xl p-4 grid grid-cols-2 md:grid-cols-3 gap-2 bg-gray-50/30 dark:bg-slate-800/30 max-h-40 overflow-y-auto custom-scrollbar">
                            <template x-for="jt in jenisTenagas" :key="jt.jenis_tenaga_id">
                                <label class="flex items-center gap-2 cursor-pointer group">
                                    <input type="checkbox" :value="jt.jenis_tenaga_id" x-model="selectedMateri.jenis_tenaga_ids"
                                        class="w-4 h-4 rounded border-gray-300 dark:border-slate-600 text-blue-600">
                                    <span class="text-[11px] font-bold text-gray-600 dark:text-gray-300 group-hover:text-gray-900 transition-colors" x-text="jt.jenis_tenaga"></span>
                                </label>
                            </template>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4">
                        <button @click="openEditFolder = false" type="button" class="w-full sm:w-auto px-8 py-2.5 border dark:border-slate-700 text-gray-500 dark:text-white font-bold rounded-lg hover:bg-gray-50 dark:hover:bg-slate-800 transition text-xs">Batal</button>
                        <button type="submit" class="w-full sm:w-auto px-8 py-2.5 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 shadow-lg shadow-blue-100 dark:shadow-none transition text-xs active:scale-95" :disabled="isSubmitting">Perbarui</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/dark.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function manajemenPelatihanData() {
        return {
            openTambahFolder: false, 
            openEditFolder: false,
            sidebarOpen: false, 
            darkMode: localStorage.getItem('theme') === 'dark',
            
            materis: [],
            kategoris: [],
            unitKerjas: [],
            jenisTenagas: [],
            
            searchQuery: '',
            sortOrder: 'terbaru',
            currentPage: 1,
            totalPages: 1,
            isLoading: true,
            isSubmitting: false,

            formTambah: {
                judul: '', nama_pemateri: '', subjudul: '', deskripsi: '', jam_pelajaran: '',
                tanggal_upload: '', tanggal_selesai: '', kategori_id: '', nomor_surat: '',
                thumbnail: null, unit_kerja_ids: [], jenis_tenaga_ids: []
            },
            
            selectedMateri: {
                id: '', judul: '', nama_pemateri: '', subjudul: '', deskripsi: '', jam_pelajaran: '',
                tanggal_upload: '', tanggal_selesai: '', kategori_id: '', nomor_surat: '',
                thumbnail: null, unit_kerja_ids: [], jenis_tenaga_ids: []
            },
            
            async initData() {
                this.fetchData(1);
            },
            
            formatDate(dateString) {
                if(!dateString) return '';
                const options = { day: 'numeric', month: 'short', year: 'numeric' };
                return new Date(dateString).toLocaleDateString('id-ID', options);
            },

            openTambah() {
                this.formTambah = {
                    judul: '', nama_pemateri: '', subjudul: '', deskripsi: '', jam_pelajaran: '',
                    tanggal_upload: '', tanggal_selesai: '', kategori_id: '', nomor_surat: '',
                    thumbnail: null, unit_kerja_ids: [], jenis_tenaga_ids: []
                };
                this.openTambahFolder = true;
                this.$nextTick(() => { this.initDatepickers(); });
            },

            editFolder(materi) {
                this.selectedMateri = {
                    id: materi.materi_id,
                    judul: materi.judul,
                    nama_pemateri: materi.nama_pemateri || '',
                    subjudul: materi.subjudul || '',
                    deskripsi: materi.deskripsi || '',
                    jam_pelajaran: materi.jam_pelajaran,
                    tanggal_upload: materi.tanggal_upload,
                    tanggal_selesai: materi.tanggal_selesai,
                    kategori_id: materi.kategori_id,
                    nomor_surat: materi.nomor_surat || '',
                    unit_kerja_ids: materi.unit_kerjas ? materi.unit_kerjas.map(u => u.unit_kerja_id) : [],
                    jenis_tenaga_ids: materi.jenis_tenagas ? materi.jenis_tenagas.map(j => j.jenis_tenaga_id) : [],
                    thumbnail: null
                };
                this.openEditFolder = true;
                this.$nextTick(() => { this.initDatepickers(); });
            },

            selectAllUnitKerja(context) {
                const allUnitKerjaIds = this.unitKerjas.map(uk => uk.unit_kerja_id);
                if (context === 'tambah') {
                    this.formTambah.unit_kerja_ids = this.formTambah.unit_kerja_ids.length === allUnitKerjaIds.length
                        ? []
                        : [...allUnitKerjaIds];
                } else {
                    this.selectedMateri.unit_kerja_ids = this.selectedMateri.unit_kerja_ids.length === allUnitKerjaIds.length
                        ? []
                        : [...allUnitKerjaIds];
                }
            },

            selectAllJenisTenaga(context) {
                const allJenisTenagaIds = this.jenisTenagas.map(jt => jt.jenis_tenaga_id);
                if (context === 'tambah') {
                    this.formTambah.jenis_tenaga_ids = this.formTambah.jenis_tenaga_ids.length === allJenisTenagaIds.length
                        ? []
                        : [...allJenisTenagaIds];
                } else {
                    this.selectedMateri.jenis_tenaga_ids = this.selectedMateri.jenis_tenaga_ids.length === allJenisTenagaIds.length
                        ? []
                        : [...allJenisTenagaIds];
                }
            },

            async fetchData(page = 1) {
                this.isLoading = true;
                this.currentPage = page;
                try {
                    const response = await fetch(`/api/admin/manajemen-pelatihan/data?page=${page}&search=${this.searchQuery}&sort=${this.sortOrder}`, {
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': 'Bearer ' + localStorage.getItem('token')
                        }
                    });
                    const res = await response.json();
                    if(res.success) {
                        this.materis = res.data.materis.data;
                        this.totalPages = res.data.materis.last_page;
                        this.kategoris = res.data.kategoris;
                        this.unitKerjas = res.data.unitKerjas;
                        this.jenisTenagas = res.data.jenisTenagas;
                    }
                } catch(e) {
                    console.error(e);
                }
                this.isLoading = false;
            },

            async submitTambah() {
                if (new Date(this.formTambah.tanggal_selesai) < new Date(this.formTambah.tanggal_upload)) {
                    Swal.fire({ icon: 'warning', title: 'Tanggal Tidak Valid', text: 'Tanggal selesai tidak boleh lebih kecil dari tanggal mulai!' });
                    return;
                }
                
                this.isSubmitting = true;
                let formData = new FormData();
                formData.append('judul', this.formTambah.judul);
                formData.append('nama_pemateri', this.formTambah.nama_pemateri);
                formData.append('subjudul', this.formTambah.subjudul);
                formData.append('deskripsi', this.formTambah.deskripsi);
                formData.append('jam_pelajaran', this.formTambah.jam_pelajaran);
                formData.append('tanggal_upload', this.formTambah.tanggal_upload);
                formData.append('tanggal_selesai', this.formTambah.tanggal_selesai);
                formData.append('kategori_id', this.formTambah.kategori_id);
                formData.append('nomor_surat', this.formTambah.nomor_surat);
                if(this.formTambah.thumbnail) formData.append('thumbnail', this.formTambah.thumbnail);
                
                if(this.formTambah.unit_kerja_ids.length > 0) formData.append('unit_kerja_ids', this.formTambah.unit_kerja_ids.join(','));
                if(this.formTambah.jenis_tenaga_ids.length > 0) formData.append('jenis_tenaga_ids', this.formTambah.jenis_tenaga_ids.join(','));

                try {
                    const response = await fetch(`/api/admin/manajemen-pelatihan`, {
                        method: 'POST',
                        headers: { 'Accept': 'application/json', 'Authorization': 'Bearer ' + localStorage.getItem('token') },
                        body: formData
                    });
                    const res = await response.json();
                    if(res.success) {
                        Toast.fire({ icon: 'success', title: 'Berhasil', text: res.message || 'Data pelatihan berhasil ditambahkan.' });
                        this.openTambahFolder = false;
                        this.fetchData(1);
                    } else {
                        Toast.fire({ icon: 'error', title: 'Gagal', text: res.message || 'Terjadi kesalahan saat menambah data.' });
                    }
                } catch(e) {
                    console.error(e);
                    Toast.fire({ icon: 'error', title: 'Oops...', text: 'Terjadi kesalahan sistem.' });
                }
                this.isSubmitting = false;
            },

            async submitEdit() {
                if (new Date(this.selectedMateri.tanggal_selesai) < new Date(this.selectedMateri.tanggal_upload)) {
                    Toast.fire({ icon: 'warning', title: 'Tanggal Tidak Valid', text: 'Tanggal selesai tidak boleh lebih kecil dari tanggal mulai!' });
                    return;
                }

                this.isSubmitting = true;
                let formData = new FormData();
                formData.append('_method', 'PUT');
                formData.append('judul', this.selectedMateri.judul);
                formData.append('nama_pemateri', this.selectedMateri.nama_pemateri);
                formData.append('subjudul', this.selectedMateri.subjudul);
                formData.append('deskripsi', this.selectedMateri.deskripsi);
                formData.append('jam_pelajaran', this.selectedMateri.jam_pelajaran);
                formData.append('tanggal_upload', this.selectedMateri.tanggal_upload);
                formData.append('tanggal_selesai', this.selectedMateri.tanggal_selesai);
                formData.append('kategori_id', this.selectedMateri.kategori_id);
                formData.append('nomor_surat', this.selectedMateri.nomor_surat);
                if(this.selectedMateri.thumbnail) formData.append('thumbnail', this.selectedMateri.thumbnail);
                
                if(this.selectedMateri.unit_kerja_ids.length > 0) formData.append('unit_kerja_ids', this.selectedMateri.unit_kerja_ids.join(','));
                if(this.selectedMateri.jenis_tenaga_ids.length > 0) formData.append('jenis_tenaga_ids', this.selectedMateri.jenis_tenaga_ids.join(','));

                try {
                    const response = await fetch(`/api/admin/manajemen-pelatihan/${this.selectedMateri.id}`, {
                        method: 'POST',
                        headers: { 'Accept': 'application/json', 'Authorization': 'Bearer ' + localStorage.getItem('token') },
                        body: formData
                    });
                    const res = await response.json();
                    if(res.success) {
                        Toast.fire({ icon: 'success', title: 'Berhasil', text: res.message || 'Data pelatihan berhasil diperbarui.' });
                        this.openEditFolder = false;
                        this.fetchData(this.currentPage);
                    } else {
                        Toast.fire({ icon: 'error', title: 'Gagal', text: res.message || 'Terjadi kesalahan saat memperbarui data.' });
                    }
                } catch(e) {
                    console.error(e);
                    Toast.fire({ icon: 'error', title: 'Oops...', text: 'Terjadi kesalahan sistem.' });
                }
                this.isSubmitting = false;
            },

            async deleteFolder(id) {
                const result = await Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Folder akan dipindahkan ke Sampah!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    customClass: {
                        popup: 'rounded-2xl dark:bg-slate-800 dark:text-white',
                        confirmButton: 'rounded-lg px-6 py-2.5 text-xs font-bold',
                        cancelButton: 'rounded-lg px-6 py-2.5 text-xs font-bold'
                    }
                });

                if (result.isConfirmed) {
                    try {
                        const response = await fetch(`/api/admin/manajemen-pelatihan/${id}`, {
                            method: 'DELETE',
                            headers: { 'Accept': 'application/json', 'Authorization': 'Bearer ' + localStorage.getItem('token') }
                        });
                        const res = await response.json();
                        if(res.success) {
                            Toast.fire({ icon: 'success', title: 'Berhasil', text: res.message || 'Data berhasil dihapus.' });
                            this.fetchData(this.currentPage);
                        } else {
                            Toast.fire({ icon: 'error', title: 'Gagal', text: res.message || 'Gagal menghapus data.' });
                        }
                    } catch(e) {
                        console.error(e);
                    }
                }
            },

            initDatepickers() {
                const config = {
                    dateFormat: "Y-m-d",
                    altInput: true,
                    altFormat: "d F Y",
                    theme: this.darkMode ? 'dark' : 'light'
                };
                
                let start1 = document.getElementById('tambah_tanggal_upload');
                let end1 = document.getElementById('tambah_tanggal_selesai');
                if (start1) flatpickr(start1, Object.assign({}, config, { defaultDate: this.formTambah.tanggal_upload || null, onChange: (d, s) => this.formTambah.tanggal_upload = s }));
                if (end1) flatpickr(end1, Object.assign({}, config, { defaultDate: this.formTambah.tanggal_selesai || null, onChange: (d, s) => this.formTambah.tanggal_selesai = s }));
                
                let start2 = document.getElementById('edit_tanggal_upload');
                let end2 = document.getElementById('edit_tanggal_selesai');
                if (start2) flatpickr(start2, Object.assign({}, config, { defaultDate: this.selectedMateri.tanggal_upload || null, onChange: (d, s) => this.selectedMateri.tanggal_upload = s }));
                if (end2) flatpickr(end2, Object.assign({}, config, { defaultDate: this.selectedMateri.tanggal_selesai || null, onChange: (d, s) => this.selectedMateri.tanggal_selesai = s }));
            }
        }
    }
</script>

<style>
    [x-cloak] { display: none !important; }
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 10px; }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; }
</style>
@endsection