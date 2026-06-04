@extends('components.layout')
@section('title', 'Arsip Pelatihan')

@section('content')
    <div class="flex h-screen overflow-hidden bg-slate-50 dark:bg-slate-950 transition-colors duration-300"
        x-data="arsipPelatihanData()" x-init="initData()">

        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-slate-900 border-r dark:border-slate-800 transition-transform duration-300 lg:translate-x-0 lg:static lg:inset-0 shrink-0 overflow-y-auto">
            @include('components.nav-superadmin')
        </aside>

        <div x-show="sidebarOpen" @click="sidebarOpen = false"
            x-transition:enter="transition opacity-100 ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:leave="transition opacity-100 ease-in duration-200" x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-40 bg-black/50 backdrop-blur-sm lg:hidden" x-cloak></div>

        <div class="flex-1 flex flex-col min-w-0 transition-colors duration-300">
            @include('components.header-superadmin', ['title' => 'Arsip'])
            
            <main class="flex-1 overflow-y-auto p-4 lg:p-8 custom-scrollbar">
                <nav class="mb-6 text-[14px] font-medium">
                    <ol class="flex items-center gap-2 text-gray-500 dark:text-gray-400">
                        <li><a href="{{ route('manajemen-pelatihan') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Manajemen Media</a></li>
                        <li class="flex items-center gap-2"><span class="text-gray-300 dark:text-gray-600"> > </span><span class="text-gray-800 dark:text-white font-semibold">Arsip</span></li>
                    </ol>
                </nav>
                <div class="flex flex-col sm:flex-row justify-between items-start gap-4 mb-6">
                    <div>
                        <h2 class="text-lg lg:text-xl font-bold text-gray-800 dark:text-white transition-colors">Arsip</h2>
                        <p class="text-xs lg:text-sm text-gray-500 dark:text-gray-300 mt-1 leading-relaxed">Daftar folder pelatihan yang telah dinonaktifkan atau diarsipkan.</p>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 p-4 lg:p-6 mb-10 transition-colors duration-300">
                    <div class="flex flex-col md:flex-row justify-end items-center gap-4 mb-8">
                        <div class="relative w-full md:w-64">
                            <span class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 dark:text-white"><i
                                    class="fa-solid fa-magnifying-glass text-xs"></i></span>
                            <input type="text" x-model="searchQuery" @input.debounce.500ms="fetchData(1)"
                                class="block w-full pl-4 pr-10 py-2 border border-gray-200 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 text-xs transition-all placeholder:dark:text-gray-400"
                                placeholder="Cari di arsip...">
                        </div>
                    </div>

                    <div x-show="isLoading" class="flex justify-center items-center py-20">
                        <i class="fa-solid fa-spinner fa-spin text-3xl text-blue-500"></i>
                    </div>

                    <div x-show="!isLoading" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-6">
                        <template x-for="materi in materis" :key="materi.materi_id">
                            <div class="relative group" x-data="{ menuOpen: false }">
                                <a :href="'/arsip-pelatihan/daftar-materi-kuis/' + materi.materi_id"
                                    class="block border border-gray-100 dark:border-slate-800 rounded-xl overflow-hidden bg-white dark:bg-slate-900 opacity-75 hover:opacity-100 transition-all hover:shadow-md active:scale-[0.98] cursor-pointer">
                                    <div class="p-6 flex items-center justify-center bg-gray-100 dark:bg-slate-800">
                                        <i class="fa-solid fa-folder text-gray-400 text-6xl lg:text-7xl"></i>
                                    </div>

                                    <div class="p-4 text-center">
                                        <p class="text-xs font-bold text-gray-700 dark:text-white mb-1 truncate px-2" x-text="materi.judul"></p>
                                        <p class="text-[10px] text-gray-400 dark:text-gray-500 font-medium italic">
                                            Kedaluwarsa: <span x-text="formatDate(materi.tanggal_selesai)"></span>
                                        </p>
                                    </div>
                                </a>

                                <div class="absolute top-2 right-2 z-20">
                                    <button @click.prevent="menuOpen = !menuOpen"
                                        class="w-7 h-7 rounded-full bg-white/90 dark:bg-slate-800/90 shadow-sm flex items-center justify-center text-gray-500 dark:text-white hover:bg-white dark:hover:bg-slate-700 transition text-xs border border-gray-100 dark:border-slate-700">
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </button>

                                    <div x-show="menuOpen" @click.away="menuOpen = false" x-cloak x-transition
                                        class="absolute right-0 mt-1 w-40 bg-white dark:bg-slate-900 rounded-lg shadow-xl border border-gray-100 dark:border-slate-800 py-1 z-30 overflow-hidden">

                                        <button type="button"
                                            @click="prepareRestore(materi); menuOpen = false;"
                                            class="w-full text-left px-4 py-2 text-xs text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors font-bold">
                                            <i class="fa-solid fa-rotate-left mr-2"></i>Pulihkan
                                        </button>

                                        <button type="button" @click="deletePermanently(materi.materi_id); menuOpen = false;"
                                            class="w-full text-left px-4 py-2 text-xs text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                            <i class="fa-solid fa-trash-can mr-2"></i>Hapus Permanen
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <template x-if="materis.length === 0">
                            <div class="col-span-full py-16 text-center">
                                <i class="fa-solid fa-box-open text-5xl text-gray-200 dark:text-slate-700 mb-4"></i>
                                <p class="text-sm text-gray-400 dark:text-gray-500 font-medium">Arsip kosong.</p>
                            </div>
                        </template>
                    </div>

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

        {{-- MODAL PULIHKAN ARSIP --}}
        <div x-show="openRestore" @click.self="openRestore = false"
            class="fixed inset-0 z-[60] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" x-cloak>

            <div class="bg-white dark:bg-slate-900 w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden transition-colors duration-300">
                <div class="flex justify-between items-center px-6 lg:px-8 py-5 border-b border-gray-100 dark:border-slate-800">
                    <h2 class="text-base font-bold text-gray-800 dark:text-white">Pulihkan Pelatihan</h2>
                    <button @click="openRestore = false" class="text-gray-400 hover:text-gray-800 dark:hover:text-white transition">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <div class="p-6 lg:p-8">
                    <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-100 dark:border-blue-800">
                        <p class="text-xs text-blue-600 dark:text-blue-400 leading-relaxed">
                            Anda akan memulihkan folder <span class="font-bold" x-text="selectedJudul"></span>. Silakan tentukan periode aktif yang baru agar folder dapat diakses kembali oleh staf.
                        </p>
                    </div>

                    <form @submit.prevent="submitRestore" class="space-y-5">
                        <div class="grid grid-cols-1 gap-5">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 dark:text-white mb-2 uppercase tracking-tight">Tanggal Mulai <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <input type="text" x-model="tanggalUpload" required id="restore_tanggal_upload"
                                        class="datepicker w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg h-12 px-4 pl-10 text-sm dark:text-white outline-none focus:ring-2 focus:ring-blue-500/10">
                                    <i class="fa-regular fa-calendar absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 dark:text-white mb-2 uppercase tracking-tight">Tanggal Selesai <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <input type="text" x-model="tanggalSelesai" required id="restore_tanggal_selesai"
                                        class="datepicker w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg h-12 px-4 pl-10 text-sm dark:text-white outline-none focus:ring-2 focus:ring-blue-500/10">
                                    <i class="fa-regular fa-calendar absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4">
                            <button @click="openRestore = false" type="button"
                                class="w-full sm:w-auto px-8 py-2.5 border dark:border-slate-700 text-gray-500 dark:text-white font-bold rounded-lg hover:bg-gray-50 dark:hover:bg-slate-800 transition text-xs">Batal</button>
                            <button type="submit" :disabled="isSubmitting"
                                class="w-full sm:w-auto px-8 py-2.5 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 shadow-lg shadow-blue-100 dark:shadow-none transition text-xs active:scale-95">Pulihkan Sekarang</button>
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
        function arsipPelatihanData() {
            return {
                sidebarOpen: false, 
                darkMode: localStorage.getItem('theme') === 'dark',
                openRestore: false,
                isLoading: true,
                isSubmitting: false,
                materis: [],
                searchQuery: '',
                currentPage: 1,
                totalPages: 1,
                selectedMateriId: null,
                selectedJudul: '',
                tanggalUpload: '',
                tanggalSelesai: '',

                async initData() {
                    this.fetchData(1);
                },

                formatDate(dateString) {
                    if(!dateString) return '';
                    const options = { day: 'numeric', month: 'short', year: 'numeric' };
                    return new Date(dateString).toLocaleDateString('id-ID', options);
                },

                async fetchData(page = 1) {
                    this.isLoading = true;
                    this.currentPage = page;
                    try {
                        const response = await fetch(`/api/admin/manajemen-pelatihan/arsip/data?page=${page}&search=${this.searchQuery}`, {
                            headers: {
                                'Accept': 'application/json',
                                'Authorization': 'Bearer ' + localStorage.getItem('token')
                            }
                        });
                        const res = await response.json();
                        if(res.success) {
                            this.materis = res.data.materis.data;
                            this.totalPages = res.data.materis.last_page;
                        }
                    } catch(e) {
                        console.error(e);
                    }
                    this.isLoading = false;
                },

                prepareRestore(materi) {
                    this.selectedMateriId = materi.materi_id;
                    this.selectedJudul = materi.judul;
                    this.tanggalUpload = materi.tanggal_upload;
                    this.tanggalSelesai = materi.tanggal_selesai;
                    this.openRestore = true;
                    this.$nextTick(() => {
                        this.initDatepickers();
                    });
                },

                async submitRestore() {
                    if (new Date(this.tanggalSelesai) < new Date(this.tanggalUpload)) {
                        Toast.fire({
                            icon: 'warning',
                            title: 'Tanggal Tidak Valid',
                            text: 'Tanggal selesai tidak boleh lebih kecil dari tanggal mulai!',
                        });
                        return;
                    }

                    this.isSubmitting = true;
                    try {
                        const response = await fetch(`/api/admin/manajemen-pelatihan/arsip/${this.selectedMateriId}/restore`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'Authorization': 'Bearer ' + localStorage.getItem('token'),
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                tanggal_upload: this.tanggalUpload,
                                tanggal_selesai: this.tanggalSelesai
                            })
                        });
                        const res = await response.json();
                        if(res.success) {
                            Toast.fire({ icon: 'success', title: 'Berhasil', text: res.message || 'Data pelatihan berhasil dipulihkan.' });
                            this.openRestore = false;
                            this.fetchData(this.currentPage);
                        } else {
                            Toast.fire({ icon: 'error', title: 'Gagal', text: res.message || 'Terjadi kesalahan saat memulihkan data.' });
                        }
                    } catch(e) {
                        console.error(e);
                        Toast.fire({ icon: 'error', title: 'Oops...', text: 'Terjadi kesalahan sistem.' });
                    }
                    this.isSubmitting = false;
                },

                async deletePermanently(id) {
                    const result = await Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Data akan dihapus permanen dari arsip!",
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
                            const response = await fetch(`/api/admin/manajemen-pelatihan/arsip/${id}`, {
                                method: 'DELETE',
                                headers: {
                                    'Accept': 'application/json',
                                    'Authorization': 'Bearer ' + localStorage.getItem('token'),
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            });
                            const res = await response.json();
                            if(res.success) {
                                Toast.fire({ icon: 'success', title: 'Berhasil', text: res.message || 'Data berhasil dihapus permanen.' });
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
                    
                    let start = document.getElementById('restore_tanggal_upload');
                    let end = document.getElementById('restore_tanggal_selesai');
                    if (start) flatpickr(start, Object.assign({}, config, { defaultDate: this.tanggalUpload || null, onChange: (d, s) => this.tanggalUpload = s }));
                    if (end) flatpickr(end, Object.assign({}, config, { defaultDate: this.tanggalSelesai || null, onChange: (d, s) => this.tanggalSelesai = s }));
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
