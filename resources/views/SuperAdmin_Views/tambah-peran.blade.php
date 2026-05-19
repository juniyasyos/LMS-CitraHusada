@extends('components.layout')
@section('title', 'Tambah Peran')

@section('content')
    {{-- Root container dengan state utama --}}
    <div class="flex h-screen overflow-hidden bg-slate-50 dark:bg-slate-950 transition-colors duration-300"
        x-data="tambahPeranData()">

        {{-- Sidebar Responsive --}}
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-slate-900 border-r dark:border-slate-800 transition-transform duration-300 lg:translate-x-0 lg:static lg:inset-0 shrink-0 overflow-y-auto">
            @include('components.nav-superadmin', ['hideSideMenu' => true])
        </aside>

        {{-- Overlay --}}
        <div x-show="sidebarOpen" @click="sidebarOpen = false"
            class="fixed inset-0 z-40 bg-black/50 backdrop-blur-sm lg:hidden" x-cloak></div>

        <div class="flex-1 flex flex-col min-w-0 transition-colors duration-300">

            {{-- Header --}}
            @include('components.header-superadmin', ['title' => 'Manajemen Pengguna'])

            <main class="flex-1 overflow-y-auto p-4 lg:p-8 custom-scrollbar">

                {{-- Breadcrumb --}}
                <nav class="mb-6 text-[14px] font-medium">
                    <ol class="flex items-center gap-2 text-gray-500 dark:text-gray-400">
                        <li>
                            <a href="/manajemen-pengguna"
                                class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                Manajemen Pengguna
                            </a>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-gray-300 dark:text-gray-600"> > </span>
                            <span class="text-gray-800 dark:text-white font-semibold">Tambah Peran</span>
                        </li>
                    </ol>
                </nav>

                {{-- Form Card Utama --}}
                <form @submit.prevent="submitForm"
                    class="bg-white dark:bg-slate-900 rounded-xl border border-gray-100 dark:border-slate-800 shadow-sm p-6 lg:p-10 mb-6 transition-colors">
                    <div class="space-y-6">
                        {{-- Input Nama --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 mb-2">Nama</label>
                            <input type="text" x-model="form.nama" placeholder="Masukkan nama lengkap"
                                class="w-full bg-slate-100 dark:bg-slate-800 border rounded-lg h-12 px-4 focus:ring-2 focus:ring-blue-500/20 focus:bg-white dark:focus:bg-slate-800 outline-none transition-all text-sm dark:text-white"
                                :class="errors.nama ? 'border-red-500' : 'border-transparent dark:border-slate-700'">
                            <template x-if="errors.nama">
                                <p class="text-red-500 text-[10px] mt-1 font-bold" x-text="errors.nama[0]"></p>
                            </template>
                        </div>

                        {{-- Input NIK --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 mb-2">Nomor Induk
                                Karyawan</label>
                            <input type="text" x-model="form.nik" placeholder="Masukkan NIK"
                                class="w-full bg-slate-100 dark:bg-slate-800 border rounded-lg h-12 px-4 focus:ring-2 focus:ring-blue-500/20 focus:bg-white dark:focus:bg-slate-800 outline-none transition-all text-sm dark:text-white"
                                :class="errors.nik ? 'border-red-500' : 'border-transparent dark:border-slate-700'">
                            <template x-if="errors.nik">
                                <p class="text-red-500 text-[10px] mt-1 font-bold" x-text="errors.nik[0]"></p>
                            </template>
                        </div>

                        {{-- Input Password --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 mb-2">Password</label>
                            <div class="relative group">
                                <input type="text" x-model="form.password" placeholder="Masukkan Password"
                                    class="w-full bg-slate-100 dark:bg-slate-800 border rounded-lg h-12 px-4 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all text-sm dark:text-white"
                                    :class="errors.password ? 'border-red-500' : 'border-transparent dark:border-slate-700'">
                                <template x-if="errors.password">
                                    <p class="text-red-500 text-[10px] mt-1 font-bold" x-text="errors.password[0]"></p>
                                </template>
                            </div>
                        </div>

                        {{-- Input JPL --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 mb-2">JPL</label>
                            <div class="relative group">
                                <input type="number" x-model="form.total_jpl"
                                    class="w-full bg-slate-100 dark:bg-slate-800 border border-transparent dark:border-slate-700 rounded-lg h-12 px-4 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all text-sm dark:text-white">
                            </div>
                        </div>

                        {{-- Row Select Dropdown (3 Kolom) --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 mb-2">Unit
                                    Kerja</label>
                                <select x-model="form.unit_kerja_id"
                                    class="w-full bg-white dark:bg-slate-800 border rounded-lg h-12 px-4 text-xs dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all cursor-pointer"
                                    :class="errors.unit_kerja_id ? 'border-red-500' : 'border-gray-200 dark:border-slate-700'">
                                    <option disabled value="">Pilih Unit Kerja</option>
                                    @foreach($unit_kerjas as $uk)
                                        <option value="{{ $uk->unit_kerja_id }}">{{ $uk->unit_kerja }}</option>
                                    @endforeach
                                </select>
                                <template x-if="errors.unit_kerja_id">
                                    <p class="text-red-500 text-[10px] mt-1 font-bold" x-text="errors.unit_kerja_id[0]"></p>
                                </template>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 mb-2">Jenis
                                    Tenaga</label>
                                <select x-model="form.jenis_tenaga_id"
                                    class="w-full bg-white dark:bg-slate-800 border rounded-lg h-12 px-4 text-xs dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all cursor-pointer"
                                    :class="errors.jenis_tenaga_id ? 'border-red-500' : 'border-gray-200 dark:border-slate-700'">
                                    <option disabled value="">Pilih Jenis Tenaga</option>
                                    @foreach($jenis_tenagas as $jt)
                                        <option value="{{ $jt->jenis_tenaga_id }}">{{ $jt->jenis_tenaga }}</option>
                                    @endforeach
                                </select>
                                <template x-if="errors.jenis_tenaga_id">
                                    <p class="text-red-500 text-[10px] mt-1 font-bold" x-text="errors.jenis_tenaga_id[0]">
                                    </p>
                                </template>
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-bold text-gray-600 dark:text-gray-400 mb-2">Role/Peran</label>
                                <select x-model="form.role_id"
                                    class="w-full bg-white dark:bg-slate-800 border rounded-lg h-12 px-4 text-xs dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all cursor-pointer"
                                    :class="errors.role_id ? 'border-red-500' : 'border-gray-200 dark:border-slate-700'">
                                    <option disabled value="">Pilih Role/Peran</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->role_id }}">{{ $role->role }}</option>
                                    @endforeach
                                </select>
                                <template x-if="errors.role_id">
                                    <p class="text-red-500 text-[10px] mt-1 font-bold" x-text="errors.role_id[0]"></p>
                                </template>
                            </div>
                        </div>

                        {{-- Status Pengguna dengan Toggle --}}
                        <div class="pt-4">
                            <div
                                class="flex items-center justify-between bg-gray-50 dark:bg-slate-800/40 p-4 rounded-xl border border-gray-100 dark:border-slate-800/60 transition-all">
                                <div class="flex flex-col">
                                    <span class="text-xs font-bold text-gray-700 dark:text-white">Status Pengguna</span>
                                    <span class="text-[10px] font-medium transition-colors"
                                        :class="form.status === 'Aktif' ? 'text-emerald-500' : 'text-rose-500'"
                                        x-text="form.status === 'Aktif' ? 'Akun Aktif (Dapat mengakses sistem)' : 'Akun Nonaktif (Akses ditangguhkan)'"></span>
                                </div>

                                {{-- Switch Toggle --}}
                                <button type="button"
                                    @click="form.status = (form.status === 'Aktif') ? 'Tidak Aktif' : 'Aktif'"
                                    :class="form.status === 'Aktif' ? 'bg-blue-600' : 'bg-gray-300 dark:bg-slate-700'"
                                    class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-300 ease-in-out focus:outline-none ring-offset-2 dark:ring-offset-slate-900 focus:ring-2 focus:ring-blue-500/40">

                                    {{-- Dot Toggle --}}
                                    <span :class="form.status === 'Aktif' ? 'translate-x-5' : 'translate-x-0'"
                                        class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow-md ring-0 transition duration-300 ease-in-out">
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Footer Button --}}
                    <div class="flex justify-end gap-3 mt-10">
                        <a href="{{ route('manajemen-pengguna') }}"
                            class="px-8 py-3 rounded-lg text-sm font-bold text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-800 transition-all flex items-center">
                            Batal
                        </a>
                        <button type="submit" :disabled="isSubmitting"
                            class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-10 py-3 rounded-lg text-sm font-bold transition shadow-lg shadow-blue-200 dark:shadow-none active:scale-95 disabled:opacity-50">
                            <span x-show="!isSubmitting">Tambah Pengguna</span>
                            <span x-show="isSubmitting">Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </main>
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

        /* Mencegah input autofill merusak warna dark mode */
        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus {
            -webkit-text-fill-color: inherit;
            -webkit-box-shadow: 0 0 0px 1000px transparent inset;
            transition: background-color 5000s ease-in-out 0s;
        }
    </style>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('tambahPeranData', () => ({
                darkMode: localStorage.getItem('theme') === 'dark',
                sidebarOpen: false,
                isSubmitting: false,
                form: {
                    nama: '',
                    nik: '',
                    password: '',
                    total_jpl: 0,
                    unit_kerja_id: '',
                    jenis_tenaga_id: '',
                    role_id: '',
                    status: 'Aktif'
                },
                errors: {},

                async submitForm() {
                    this.isSubmitting = true;
                    this.errors = {};

                    try {
                        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                        const response = await fetch('/api/admin/manajemen-pengguna/store', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify({
                                ...this.form,
                                // Convert boolean check back if the API expected string 'Aktif', but we already have string 'Aktif'
                            })
                        });

                        const result = await response.json();

                        if (response.ok) {
                            await Swal.fire({
                                title: 'Berhasil',
                                text: result.message || 'Pengguna berhasil ditambahkan',
                                icon: 'success',
                                confirmButtonColor: '#3085d6'
                            });
                            window.location.href = '/manajemen-pengguna';
                        } else if (response.status === 422) {
                            this.errors = result.errors;
                            Swal.fire('Validasi Gagal', 'Silakan periksa kembali isian Anda.', 'warning');
                        } else {
                            Swal.fire('Error', result.message || 'Terjadi kesalahan sistem', 'error');
                        }
                    } catch (error) {
                        console.error(error);
                        Swal.fire('Error', 'Gagal terhubung ke server', 'error');
                    } finally {
                        this.isSubmitting = false;
                    }
                }
            }));
        });
    </script>
@endsection