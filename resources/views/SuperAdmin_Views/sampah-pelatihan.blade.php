@extends('components.layout')
@section('title', 'Sampah Pelatihan')

@section('content')
    <div class="flex h-screen overflow-hidden bg-slate-50 dark:bg-slate-950 transition-colors duration-300"
        x-data="sampahPelatihanData()" x-init="initData()">

        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-slate-900 border-r dark:border-slate-800 transition-transform duration-300 lg:translate-x-0 lg:static lg:inset-0 shrink-0 overflow-y-auto">
            @include('components.nav-superadmin')
        </aside>

        <div x-show="sidebarOpen" @click="sidebarOpen = false"
            x-transition:enter="transition opacity-100 ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:leave="transition opacity-100 ease-in duration-200" x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-40 bg-black/50 backdrop-blur-sm lg:hidden" x-cloak></div>

        <div class="flex-1 flex flex-col min-w-0 transition-colors duration-300">
            @include('components.header-superadmin', ['title' => 'Sampah'])

            <main class="flex-1 overflow-y-auto p-4 lg:p-8 custom-scrollbar">
                {{-- Breadcrumb --}}
                <nav class="mb-6 text-[14px] font-medium">
                    <ol class="flex items-center gap-2 text-gray-500 dark:text-gray-400">
                        <li><a href="{{ route('manajemen-pelatihan') }}"
                                class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Manajemen Media</a>
                        </li>
                        <li class="flex items-center gap-2"><span class="text-gray-300 dark:text-gray-600"> > </span><span
                                class="text-gray-800 dark:text-white font-semibold">Sampah</span></li>
                    </ol>
                </nav>

                <div
                    class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 p-4 lg:p-6 mb-10 transition-colors duration-300">
                    <div class="flex flex-col sm:flex-row justify-between items-start gap-4 mb-6">
                        <div>
                            <h2 class="text-lg font-bold text-gray-800 dark:text-white flex items-center gap-2"><i
                                    class="fa-solid fa-trash-can text-red-500 text-sm"></i> Pelatihan yang Dihapus</h2>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Materi yang dihapus akan otomatis
                                terhapus permanen setelah 30 hari.</p>
                        </div>
                    </div>

                    <div class="mb-6">
                        <div class="relative w-full sm:w-72">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 dark:text-white"><i
                                    class="fa-solid fa-magnifying-glass text-xs"></i></span>
                            <input type="text" x-model="searchQuery" @input.debounce.500ms="fetchData(1)"
                                class="block w-full pl-9 pr-3 py-2.5 border border-gray-200 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 text-xs transition-all placeholder:dark:text-gray-400"
                                placeholder="Cari pelatihan terhapus...">
                        </div>
                    </div>

                    <div x-show="isLoading" class="flex justify-center items-center py-20">
                        <i class="fa-solid fa-spinner fa-spin text-3xl text-blue-500"></i>
                    </div>

                    <div x-show="!isLoading" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-6">
                        <template x-for="materi in materis" :key="materi.materi_id">
                            <div class="border border-red-100 dark:border-red-900/30 rounded-xl overflow-hidden opacity-75 hover:opacity-100 transition-all group relative"
                                x-data="{ menuOpen: false }">
                                <div class="h-40 bg-slate-100 dark:bg-slate-800 overflow-hidden relative">
                                    <template x-if="materi.image_path">
                                        <img :src="'{{ Storage::url('') }}' + materi.image_path" :alt="materi.judul"
                                            class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-300">
                                    </template>
                                    <template x-if="!materi.image_path">
                                        <div class="w-full h-full flex items-center justify-center">
                                            <i class="fa-solid fa-folder text-gray-300 dark:text-slate-600 text-5xl"></i>
                                        </div>
                                    </template>
                                    <div class="absolute inset-0 bg-red-900/10"></div>
                                </div>
                                <div class="p-4">
                                    <p class="text-xs font-bold text-gray-700 dark:text-white mb-1 truncate"
                                        x-text="materi.judul"></p>
                                    <p class="text-[10px] text-red-400 italic"><i
                                            class="fa-solid fa-clock mr-1"></i>Dihapus: <span
                                            x-text="formatDateTime(materi.deleted_at)"></span></p>
                                    <p class="text-[9px] text-gray-400 mt-1">Otomatis terhapus dalam <span
                                            class="font-bold text-red-500"
                                            x-text="calculateDaysLeft(materi.deleted_at)"></span> hari</p>
                                </div>
                                {{-- 3-dot menu --}}
                                <div class="absolute top-2 right-2">
                                    <button @click="menuOpen = !menuOpen"
                                        class="w-7 h-7 rounded-full bg-white/80 dark:bg-slate-900/80 flex items-center justify-center text-gray-500 dark:text-white hover:bg-white transition text-xs"><i
                                            class="fa-solid fa-ellipsis-vertical"></i></button>
                                    <div x-show="menuOpen" @click.away="menuOpen = false" x-cloak
                                        class="absolute right-0 mt-1 w-44 bg-white dark:bg-slate-900 rounded-lg shadow-xl border border-gray-100 dark:border-slate-800 py-1 z-20">
                                        <button type="button" @click="restoreMateri(materi.materi_id); menuOpen = false;"
                                            class="w-full text-left px-4 py-2 text-xs text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20"><i
                                                class="fa-solid fa-rotate-left mr-2"></i>Pulihkan</button>
                                        <button type="button" @click="forceDelete(materi.materi_id); menuOpen = false;"
                                            class="w-full text-left px-4 py-2 text-xs text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20"><i
                                                class="fa-solid fa-fire mr-2"></i>Hapus Permanen</button>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <template x-if="materis.length === 0">
                            <div class="col-span-full py-16 text-center">
                                <i class="fa-solid fa-recycle text-5xl text-gray-200 dark:text-slate-700 mb-4"></i>
                                <p class="text-sm text-gray-400 dark:text-gray-500 font-medium">Sampah kosong — tidak ada
                                    pelatihan yang dihapus.</p>
                            </div>
                        </template>
                    </div>

                    <div class="mt-8 flex justify-center gap-1" x-show="!isLoading && totalPages > 1">
                        <template x-for="page in totalPages" :key="page">
                            <button @click="fetchData(page)"
                                :class="currentPage === page ? 'bg-blue-600 text-white' : 'bg-white dark:bg-slate-800 text-gray-700 dark:text-white border border-gray-200 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-700'"
                                class="px-3 py-1 rounded-md text-xs font-bold transition-colors" x-text="page"></button>
                        </template>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function sampahPelatihanData() {
            return {
                sidebarOpen: false,
                darkMode: localStorage.getItem('theme') === 'dark',
                isLoading: true,
                materis: [],
                searchQuery: '',
                currentPage: 1,
                totalPages: 1,

                async initData() {
                    this.fetchData(1);
                },

                formatDateTime(dateString) {
                    if (!dateString) return '';
                    const options = { day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' };
                    return new Date(dateString).toLocaleDateString('id-ID', options);
                },

                calculateDaysLeft(deletedAt) {
                    if (!deletedAt) return 0;
                    const deletedDate = new Date(deletedAt);
                    const now = new Date();
                    const diffTime = Math.abs(now - deletedDate);
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                    return Math.max(30 - diffDays, 0);
                },

                async fetchData(page = 1) {
                    this.isLoading = true;
                    this.currentPage = page;
                    try {
                        const response = await fetch(`/api/admin/manajemen-pelatihan/sampah/data?page=${page}&search=${this.searchQuery}`, {
                            headers: {
                                'Accept': 'application/json',
                                'Authorization': 'Bearer ' + localStorage.getItem('token')
                            }
                        });
                        const res = await response.json();
                        if (res.success) {
                            this.materis = res.data.trashedMateris.data;
                            this.totalPages = res.data.trashedMateris.last_page;
                        }
                    } catch (e) {
                        console.error(e);
                    }
                    this.isLoading = false;
                },

                async restoreMateri(id) {
                    try {
                        const response = await fetch(`/api/admin/manajemen-pelatihan/sampah/${id}/restore`, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'Authorization': 'Bearer ' + localStorage.getItem('token'),
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });
                        const res = await response.json();
                        if (res.success) {
                            Toast.fire({ icon: 'success', title: 'Berhasil', text: res.message || 'Data pelatihan berhasil dipulihkan.' });
                            this.fetchData(this.currentPage);
                        } else {
                            Toast.fire({ icon: 'error', title: 'Gagal', text: res.message || 'Gagal memulihkan data.' });
                        }
                    } catch (e) {
                        console.error(e);
                        Toast.fire({ icon: 'error', title: 'Gagal!', text: 'Gagal terhubung ke server.' });
                    }
                },

                async forceDelete(id) {
                    const result = await Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Data yang dihapus tidak dapat dikembalikan!",
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
                            const response = await fetch(`/api/admin/manajemen-pelatihan/sampah/${id}/force`, {
                                method: 'DELETE',
                                headers: {
                                    'Accept': 'application/json',
                                    'Authorization': 'Bearer ' + localStorage.getItem('token'),
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            });
                            const res = await response.json();
                            if (res.success) {
                                Toast.fire({ icon: 'success', title: 'Berhasil', text: res.message || 'Data berhasil dihapus permanen.' });
                                this.fetchData(this.currentPage);
                            } else {
                                Toast.fire({ icon: 'error', title: 'Gagal', text: res.message || 'Gagal menghapus data.' });
                            }
                        } catch (e) {
                            console.error(e);
                            Toast.fire({ icon: 'error', title: 'Gagal!', text: 'Gagal terhubung ke server.' });
                        }
                    }
                }
            }
        }
    </script>

    <style>
        [x-cloak] {
            display: none !important;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
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
    </style>
@endsection