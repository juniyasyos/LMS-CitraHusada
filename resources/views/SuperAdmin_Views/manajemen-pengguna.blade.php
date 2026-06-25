@extends('components.layout')
@section('title', 'Manajemen Pengguna')
@section('content')

    {{-- Menambahkan state sidebarOpen untuk kontrol menu mobile --}}
    <div class="flex h-screen overflow-hidden bg-slate-50 dark:bg-slate-950 transition-colors duration-300"
        x-data="manajemenPenggunaData()">

        {{-- Sidebar Responsive Logic --}}
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-slate-900 border-r dark:border-slate-800 transition-transform duration-300 lg:translate-x-0 lg:static lg:inset-0 shrink-0 overflow-y-auto">
            @include('components.nav-superadmin')
        </aside>

        {{-- Overlay untuk menutup sidebar mobile --}}
        <div x-show="sidebarOpen" @click="sidebarOpen = false"
            x-transition:enter="transition opacity-100 ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:leave="transition opacity-100 ease-in duration-200" x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-40 bg-black/50 backdrop-blur-sm lg:hidden" x-cloak>
        </div>

        <div class="flex-1 flex flex-col min-w-0 transition-colors duration-300">

            {{-- Header Responsive --}}
            @include('components.header-superadmin', ['title' => 'Manajemen Pengguna'])

            <main class="flex-1 overflow-y-auto p-4 lg:p-8 custom-scrollbar">
                <div class="flex flex-col sm:flex-row justify-between items-start gap-4 mb-6">
                    <div>
                        <h2 class="text-lg font-bold text-gray-800 dark:text-white transition-colors">Daftar Pengguna</h2>
                        <p class="text-xs lg:text-sm text-gray-500 dark:text-gray-300 mt-1">Kelola data staf dan tenaga
                            medis sistem pembelajaran.</p>
                    </div>
                    <a href="{{ route('tambah-peran') }}"
                        class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg flex items-center justify-center gap-2 text-sm font-bold transition shadow-sm active:scale-95">
                        <i class="fa-solid fa-plus text-xs"></i>
                        Tambah Peran
                    </a>
                </div>

                <form @submit.prevent="fetchData(1)"
                    class="mb-6 flex flex-wrap items-center justify-between gap-4 bg-white dark:bg-slate-900 p-4 rounded-xl border border-gray-100 dark:border-slate-800 shadow-sm transition-colors duration-300">
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-2">
                            <label for="per_page" class="text-xs font-bold text-gray-600 dark:text-gray-400">Baris:</label>
                            <select id="per_page" x-model="filters.per_page" @change="fetchData(1)" :disabled="filters.all"
                                class="bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-700 dark:text-white text-[11px] rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-16 p-1.5 outline-none disabled:opacity-50">
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>

                        <div class="flex items-center gap-2">
                            <input type="checkbox" id="show_all" x-model="filters.all" @change="fetchData(1)"
                                class="w-4 h-4 text-blue-600 bg-gray-100 dark:bg-slate-800 border-gray-300 dark:border-slate-700 rounded focus:ring-blue-500">
                            <label for="show_all"
                                class="text-xs font-bold text-gray-600 dark:text-gray-400 cursor-pointer">Tampilkan Semua
                                Data</label>
                        </div>
                    </div>

                    <div class="relative w-full sm:w-72">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 dark:text-white">
                            <i class="fa-solid fa-magnifying-glass text-xs"></i>
                        </span>
                        <input type="text" x-model="filters.search" @keydown.enter.prevent="fetchData(1)" id="searchUser"
                            class="block w-full pl-9 pr-3 py-2.5 border border-gray-200 dark:border-slate-700 rounded-lg bg-gray-50 dark:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-xs text-gray-700 dark:text-white transition-all placeholder:dark:text-gray-400"
                            placeholder="Cari nama atau NIP...">
                    </div>
                </form>

                {{-- Table Wrapper --}}
                <div
                    class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 overflow-x-auto mb-6 transition-colors duration-300">
                    <table class="w-full text-left text-xs min-w-[800px]">
                        <thead
                            class="text-gray-500 dark:text-white font-bold border-b dark:border-slate-800 bg-gray-50/50 dark:bg-slate-800/50">
                            <tr>
                                <th class="py-4 px-6 uppercase tracking-wider">Nama Lengkap</th>
                                <th class="py-4 px-4 uppercase tracking-wider">NIP</th>
                                <th class="py-4 px-4 uppercase tracking-wider">Jenis Tenaga</th>
                                <th class="py-4 px-4 uppercase tracking-wider">Unit Kerja</th>
                                <th class="py-4 px-4 uppercase tracking-wider">JPL</th>
                                <th class="py-4 px-4 uppercase tracking-wider text-center">Status</th>
                                <th class="py-4 px-6 uppercase tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-slate-800 text-gray-700 dark:text-white">
                            <template x-if="isLoading">
                                <tr>
                                    <td colspan="7" class="py-10 text-center">
                                        <i class="fa-solid fa-spinner fa-spin text-3xl text-blue-500 mb-2"></i>
                                        <p class="text-xs text-gray-500">Memuat data pengguna...</p>
                                    </td>
                                </tr>
                            </template>
                            <template x-if="!isLoading && users.length === 0">
                                <tr>
                                    <td colspan="7"
                                        class="text-center py-6 text-gray-500 italic border-t border-gray-100 dark:border-slate-800">
                                        Data tidak ditemukan</td>
                                </tr>
                            </template>
                            <template x-if="!isLoading && users.length > 0">
                                <template x-for="user in users" :key="user.user_id">
                                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-800 transition">
                                        <!-- Nama -->
                                        <td class="py-5 px-6 font-bold text-gray-800 dark:text-white"
                                            x-text="user.name || '-'">
                                        </td>

                                        <!-- NIP -->
                                        <td class="py-5 px-4 text-gray-500 dark:text-gray-300" x-text="user.nip || '-'">
                                        </td>

                                        <!-- Jenis Tenaga -->
                                        <td class="py-5 px-4">
                                            <span
                                                class="bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-gray-200 px-3 py-1 rounded-md font-semibold transition-colors"
                                                x-text="user.jenis_tenaga 
                        ? (user.jenis_tenaga.jenis_tenaga || user.jenis_tenaga.name || user.jenis_tenaga.nama || '-') 
                        : '-'">
                                            </span>
                                        </td>

                                        <!-- Unit Kerja -->
                                        <td class="py-5 px-4 whitespace-nowrap">
                                            <span
                                                class="bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-gray-200 px-3 py-1 rounded-md font-semibold"
                                                x-text="(user.unit_kerjas && user.unit_kerjas.length > 0) 
                        ? user.unit_kerjas.map(unit => unit.unit_name).join(', ') 
                        : '-'">
                                            </span>
                                        </td>

                                        <!-- Total JPL -->
                                        <td class="py-5 px-4 text-gray-500 dark:text-gray-300"
                                            x-text="(parseInt(user.total_jpl) || 0) + (parseInt(user.jpl_eksternal) || 0)">
                                        </td>

                                        <!-- Status -->
                                        <td class="py-5 px-4 text-center">
                                            <span
                                                class="px-3 py-1 rounded font-bold text-[10px] uppercase transition-colors"
                                                :class="{
                        'bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400': ['active', 'aktif'].includes((user.status || '').toLowerCase()),
                        'bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400': ['inactive', 'tidak aktif'].includes((user.status || '').toLowerCase()),
                        'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400': (user.status || '').toLowerCase() === 'suspended',
                        'bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-gray-300': !['active', 'aktif', 'inactive', 'tidak aktif', 'suspended'].includes((user.status || '').toLowerCase())
                    }" x-text="
                        ['active', 'aktif'].includes((user.status || '').toLowerCase())
                            ? 'Aktif'
                            : ['inactive', 'tidak aktif'].includes((user.status || '').toLowerCase())
                                ? 'Tidak Aktif'
                                : (user.status || '-')
                    ">
                                            </span>
                                        </td>

                                        <!-- Actions -->
                                        <td class="py-5 px-6 text-right">
                                            <div class="flex justify-end gap-3">
                                                <!-- Impersonate -->
                                                <a :href="`/manajemen-pengguna/impersonate/${user.user_id}`"
                                                    class="p-1.5 hover:bg-amber-50 dark:hover:bg-amber-900/20 text-gray-500 rounded-lg transition-all active:scale-90"
                                                    title="Masuk sebagai user ini">
                                                    <i class="fa-solid fa-eye text-sm"></i>
                                                </a>

                                                <!-- Edit -->
                                                <button type="button" @click="openEditModal(user)"
                                                    class="p-1.5 hover:bg-blue-50 dark:hover:bg-blue-900/20 text-gray-500 rounded-lg transition-all active:scale-90"
                                                    title="Edit data">
                                                    <i class="fa-solid fa-pen text-sm"></i>
                                                </button>

                                                <!-- Delete -->
                                                <button type="button" @click="deleteUser(user)"
                                                    class="p-1.5 hover:bg-red-50 dark:hover:bg-red-900/20 text-gray-500 rounded-lg transition-all active:scale-90"
                                                    title="Hapus user">
                                                    <i class="fa-solid fa-trash-can text-sm"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                            </template>
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 px-4">
                    <template x-if="pagination.links && !filters.all">
                        <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-8">
                            <p class="text-[10px] text-gray-400 dark:text-white font-medium uppercase tracking-wider">
                                Menampilkan <span x-text="pagination.from || 0"></span>-<span
                                    x-text="pagination.to || 0"></span> dari <span x-text="pagination.total || 0"></span>
                                pengguna
                            </p>
                            <div class="flex flex-wrap items-center justify-center gap-1">
                                <template x-for="(link, index) in pagination.links" :key="index">
                                    <button
                                        @click.prevent="if(link.url) fetchData(new URL(link.url).searchParams.get('page'))"
                                        x-html="link.label" :disabled="!link.url || link.active" :class="[
                                                                'px-3 py-1.5 text-[10px] sm:text-xs font-medium rounded-md transition-colors',
                                                                link.active ? 'bg-blue-600 text-white shadow-md' : 'bg-white dark:bg-slate-800 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-700',
                                                                !link.url ? 'opacity-50 cursor-not-allowed' : ''
                                                            ]"></button>
                                </template>
                            </div>
                        </div>
                    </template>
                    <template x-if="filters.all">
                        <div class="flex justify-between items-center mb-8 mt-4">
                            <p class="text-[10px] text-gray-400 dark:text-white font-medium uppercase tracking-wider">
                                Menampilkan semua <span class="font-bold text-gray-600 dark:text-white"
                                    x-text="users.length"></span> pengguna
                            </p>
                        </div>
                    </template>
                </div>

                <div
                    class="bg-white dark:bg-slate-900 rounded-xl border border-gray-100 dark:border-slate-800 p-6 flex items-start gap-4 shadow-sm mb-10 transition-colors">
                    <div
                        class="w-10 h-10 bg-blue-50 dark:bg-blue-900/30 rounded-full shrink-0 flex items-center justify-center">
                        <i class="fa-solid fa-lightbulb text-blue-500 dark:text-blue-400"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-gray-800 dark:text-white">Tips Admin</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-300 mt-1 leading-relaxed">Gunakan fitur pencarian
                            untuk mempercepat pelacakan data NIP staf secara instan.</p>
                    </div>
                </div>
            </main>
        </div>

        {{-- MODAL EDIT DATA PENGGUNA --}}
        <div x-show="openEdit"
            class="fixed inset-0 z-[60] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" x-cloak>

            <div @click.away="openEdit = false"
                class="bg-white dark:bg-slate-900 w-full max-w-4xl rounded-2xl shadow-2xl overflow-hidden transition-colors duration-300">
                <form @submit.prevent="submitEdit">

                    <div
                        class="flex justify-between items-center px-6 lg:px-8 py-5 border-b border-gray-100 dark:border-slate-800">
                        <h2 class="text-base font-bold text-gray-800 dark:text-white">Edit Data Pengguna</h2>
                        <button type="button" @click="openEdit = false"
                            class="text-gray-400 hover:text-gray-800 dark:hover:text-white transition">
                            <i class="fa-solid fa-xmark text-lg"></i>
                        </button>
                    </div>

                    <div class="p-6 lg:p-8 space-y-8 max-h-[80vh] overflow-y-auto custom-scrollbar">
                        <div>
                            <h3 class="text-[11px] font-bold text-gray-400 dark:text-white uppercase tracking-widest mb-4">
                                Informasi Personal</h3>
                            <div class="space-y-4 border-t border-gray-50 dark:border-slate-800 pt-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-600 dark:text-white mb-2">Nama
                                        Lengkap</label>
                                    <input type="text" x-model="editForm.nama" required
                                        class="w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg h-12 px-4 focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all text-sm dark:text-white">
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-600 dark:text-white mb-2">Nomor
                                            Induk Karyawan (NIP)</label>
                                        <input type="text" x-model="editForm.nip" required
                                            class="w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg h-12 px-4 focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all text-sm dark:text-white">
                                    </div>
                                    <div>
                                        <label
                                            class="block text-xs font-bold text-gray-600 dark:text-white mb-2">JPL</label>
                                        <input type="text" x-model="editForm.total_jpl"
                                            class="w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg h-12 px-4 focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all text-sm dark:text-white opacity-50 cursor-not-allowed"
                                            readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-[11px] font-bold text-gray-400 dark:text-white uppercase tracking-widest mb-4">
                                Akses Sistem</h3>
                            <div
                                class="grid grid-cols-1 md:grid-cols-3 gap-6 border-t border-gray-50 dark:border-slate-800 pt-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-600 dark:text-white mb-2">Unit
                                        Kerja</label>
                                    <select x-model="editForm.unit_kerja_id" required
                                        class="w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg h-12 px-4 text-sm dark:text-white outline-none">
                                        @foreach($unit_kerjas as $uk)
                                            <option value="{{ $uk->unit_kerja_id }}">{{ $uk->unit_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-600 dark:text-white mb-2">Jenis
                                        Tenaga</label>
                                    <select x-model="editForm.jenis_tenaga_id" required
                                        class="w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg h-12 px-4 text-sm dark:text-white outline-none">
                                        @foreach($jenis_tenagas as $jt)
                                            <option value="{{ $jt->jenis_tenaga_id }}">{{ $jt->jenis_tenaga }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-bold text-gray-600 dark:text-white mb-2">Role/Peran</label>
                                    <select x-model="editForm.roles" required
                                        class="w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg h-12 px-4 text-sm dark:text-white outline-none">
                                        @foreach($roles as $role)
                                            <option value="{{ $role->role_id }}">{{ $role->role }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div>
                                <label class="block text-xs font-bold text-gray-600 dark:text-white mb-2">Password
                                    (Kosongkan jika tidak ingin mengubah)</label>
                                <input type="password" x-model="editForm.password" placeholder="********"
                                    class="w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg h-12 px-4 focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all text-sm dark:text-white">
                            </div>

                            <div
                                class="flex items-center justify-between bg-gray-50 dark:bg-slate-800/50 p-4 rounded-xl border border-gray-100 dark:border-slate-700">
                                <span class="text-xs font-bold text-gray-700 dark:text-white">Status Pengguna</span>
                                <div class="flex space-x-2">
                                    <button type="button" @click="editForm.status = 'active'"
                                        :class="editForm.status === 'active' || editForm.status === 'Aktif' ? 'bg-emerald-600 text-white' : 'bg-gray-200 dark:bg-slate-700 text-gray-700 dark:text-gray-300'"
                                        class="py-1.5 px-3 text-xs font-bold rounded-lg transition-colors">
                                        Active
                                    </button>
                                    <button type="button" @click="editForm.status = 'inactive'"
                                        :class="editForm.status === 'inactive' || editForm.status === 'Tidak Aktif' ? 'bg-rose-600 text-white' : 'bg-gray-200 dark:bg-slate-700 text-gray-700 dark:text-gray-300'"
                                        class="py-1.5 px-3 text-xs font-bold rounded-lg transition-colors">
                                        Inactive
                                    </button>
                                    <button type="button" @click="editForm.status = 'suspended'"
                                        :class="editForm.status === 'suspended' ? 'bg-amber-600 text-white' : 'bg-gray-200 dark:bg-slate-700 text-gray-700 dark:text-gray-300'"
                                        class="py-1.5 px-3 text-xs font-bold rounded-lg transition-colors">
                                        Suspended
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4 border-t dark:border-slate-800">
                            <button type="button" @click="openEdit = false"
                                class="w-full sm:w-auto px-8 py-2.5 border border-gray-200 dark:border-slate-700 text-gray-500 dark:text-white text-xs font-bold rounded-lg hover:bg-gray-50 transition">Batal</button>
                            <button type="submit" :disabled="isSubmitting"
                                class="w-full sm:w-auto px-8 py-2.5 bg-blue-600 text-white text-xs font-bold rounded-lg hover:bg-blue-700 shadow-blue-100 transition active:scale-95 disabled:opacity-50">
                                <span x-show="!isSubmitting">Simpan Perubahan</span>
                                <span x-show="isSubmitting">Menyimpan...</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

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

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('manajemenPenggunaData', () => ({
                openEdit: false,
                sidebarOpen: false,
                darkMode: localStorage.getItem('theme') === 'dark',
                isLoading: true,
                isSubmitting: false,
                users: [],
                pagination: {},
                filters: {
                    search: new URLSearchParams(window.location.search).get('search') || '',
                    per_page: new URLSearchParams(window.location.search).get('per_page') || '10',
                    all: new URLSearchParams(window.location.search).get('all') === 'true'
                },
                editForm: {
                    user_id: null,
                    nama: '',
                    nip: '',
                    total_jpl: 0,
                    unit_kerja_id: '',
                    jenis_tenaga_id: '',
                    role_id: '',
                    status: 'active',
                    password: ''
                },

                init() {
                    this.fetchData();
                },

                async fetchData(page = 1) {
                    this.isLoading = true;
                    try {
                        const url = new URL('/api/admin/manajemen-pengguna', window.location.origin);
                        url.searchParams.append('page', page);
                        if (this.filters.search) url.searchParams.append('search', this.filters.search);
                        url.searchParams.append('per_page', this.filters.per_page);
                        if (this.filters.all) url.searchParams.append('all', 'true');

                        const params = new URLSearchParams(url.search);
                        params.delete('page');
                        window.history.replaceState({}, '', `${window.location.pathname}?${params}`);

                        const response = await fetch(url.toString(), {
                            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                        });
                        const data = await response.json();

                        this.users = data.data;
                        this.pagination = {
                            current_page: data.current_page,
                            last_page: data.last_page,
                            links: data.links,
                            from: data.from,
                            to: data.to,
                            total: data.total
                        };
                    } catch (error) {
                        console.error(error);
                    } finally {
                        this.isLoading = false;
                    }
                },

                openEditModal(user) {
                    this.editForm = {
                        user_id: user.user_id,
                        nama: user.name,
                        nip: user.nip,
                        total_jpl: (parseInt(user.total_jpl) || 0) + (parseInt(user.jpl_eksternal) || 0),
                        unit_kerja_id: user.unit_kerjas && user.unit_kerjas.length > 0 ? user.unit_kerjas[0].unit_kerja_id : '',
                        jenis_tenaga_id: user.jenis_tenaga_id,
                        roles: user.roles && user.roles.length ? user.roles[0] : '',
                        status: user.status,
                        password: '' // Reset password input
                    };
                    this.openEdit = true;
                },

                async submitEdit() {
                    this.isSubmitting = true;
                    try {
                        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                        // Ensure roles is an array
                        if (typeof this.editForm.roles === 'string') {
                            this.editForm.roles = [this.editForm.roles];
                        }
                        const response = await fetch(`/api/admin/manajemen-pengguna/update/${this.editForm.user_id}`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify(this.editForm)
                        });
                        const result = await response.json();

                        if (response.ok) {
                            this.openEdit = false;
                            Toast.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: result.message || 'Data pengguna berhasil diperbarui'
                            });
                            this.fetchData(this.pagination.current_page || 1);
                        } else {
                            Toast.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: result.message || 'Terjadi kesalahan sistem'
                            });
                        }
                    } catch (error) {
                        console.error(error);
                        Toast.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Gagal terhubung ke server'
                        });
                    } finally {
                        this.isSubmitting = false;
                    }
                },

                async deleteUser(user) {
                    const result = await Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: `Data pengguna ${user.name} yang dihapus tidak dapat dikembalikan!`,
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
                            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                            const response = await fetch(`/api/admin/manajemen-pengguna/destroy/${user.user_id}`, {
                                method: 'DELETE',
                                headers: {
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken
                                }
                            });

                            const data = await response.json();
                            if (response.ok) {
                                Toast.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: data.message || 'Pengguna berhasil dihapus.'
                                });
                                this.fetchData(this.pagination.current_page || 1);
                            } else {
                                Toast.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: data.message || 'Terjadi kesalahan saat menghapus data.'
                                });
                            }
                        } catch (error) {
                            console.error(error);
                            Toast.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: 'Gagal terhubung ke server'
                            });
                        }
                    }
                }
            }));
        });
    </script>
@endsection