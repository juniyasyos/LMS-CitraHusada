@extends('components.layout')
@section('title', 'Tambah Peran')

@section('content')
{{-- Root container dengan state utama --}}
<div class="flex h-screen overflow-hidden bg-slate-50 dark:bg-slate-950 transition-colors duration-300" 
    x-data="{ 
        darkMode: localStorage.getItem('theme') === 'dark', 
        sidebarOpen: false,
        userStatus: true 
    }">
    
    {{-- Sidebar Responsive --}}
    <aside 
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-slate-900 border-r dark:border-slate-800 transition-transform duration-300 lg:translate-x-0 lg:static lg:inset-0 shrink-0 overflow-y-auto">
        @include('components.nav-superadmin', ['hideSideMenu' => true])
    </aside>

    {{-- Overlay --}}
    <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-black/50 backdrop-blur-sm lg:hidden" x-cloak></div>

    <div class="flex-1 flex flex-col min-w-0 transition-colors duration-300">
        
        {{-- Header --}}
        <header class="bg-white dark:bg-slate-900 border-b dark:border-slate-800 h-16 flex items-center justify-between px-4 lg:px-8 shrink-0 z-10">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = true" class="lg:hidden p-2 text-gray-500 dark:text-white">
                    <i class="fa-solid fa-bars text-lg"></i>
                </button>
                <h1 class="text-sm font-semibold text-gray-600 dark:text-white truncate">Manajemen Pengguna</h1>
            </div>

            <div class="flex items-center gap-4">
                @include('components.notif-superadmin')
                <div class="flex items-center gap-3 pl-4 border-l border-gray-100 dark:border-slate-800">
                    <div class="text-right hidden sm:block">
                        <p class="text-xs font-bold text-gray-800 dark:text-white leading-tight">Superadmin</p>
                        <p class="text-[10px] text-gray-500 dark:text-gray-400 font-medium">Administrator Utama</p>
                    </div>
                    <div class="w-8 h-8 bg-gray-200 dark:bg-slate-700 rounded-full overflow-hidden border dark:border-slate-800 flex items-center justify-center">
                        <i class="fa-solid fa-user text-gray-500 dark:text-white text-xs"></i>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-4 lg:p-8 custom-scrollbar">
            
            {{-- Breadcrumb --}}
            <nav class="mb-6 text-[14px] font-medium">
                <ol class="flex items-center gap-2 text-gray-500 dark:text-gray-400">
                    <li>
                        <a href="/manajemen-pengguna" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
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
            <form action="{{ route('tambah-peran.store') }}" method="POST" class="bg-white dark:bg-slate-900 rounded-xl border border-gray-100 dark:border-slate-800 shadow-sm p-6 lg:p-10 mb-6 transition-colors">
                @csrf
                <div class="space-y-6">
                    {{-- Input Nama --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 mb-2">Nama</label>
                        <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Masukkan nama lengkap" class="w-full bg-slate-100 dark:bg-slate-800 border @error('nama') border-red-500 @else border-transparent @enderror dark:border-slate-700 rounded-lg h-12 px-4 focus:ring-2 focus:ring-blue-500/20 focus:bg-white dark:focus:bg-slate-800 outline-none transition-all text-sm dark:text-white">
                        @error('nama') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>

                    {{-- Input NIK --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 mb-2">Nomor Induk Karyawan</label>
                        <input type="text" name="nik" value="{{ old('nik') }}" placeholder="Masukkan NIK" class="w-full bg-slate-100 dark:bg-slate-800 border @error('nik') border-red-500 @else border-transparent @enderror dark:border-slate-700 rounded-lg h-12 px-4 focus:ring-2 focus:ring-blue-500/20 focus:bg-white dark:focus:bg-slate-800 outline-none transition-all text-sm dark:text-white">
                        @error('nik') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>

                    {{-- Input Password --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 mb-2">Password</label>
                        <div class="relative group">
                            <input type="text" name="password" value="{{ old('password', '123') }}"
                                class="w-full bg-slate-100 dark:bg-slate-800 border @error('password') border-red-500 @else border-transparent @enderror dark:border-slate-700 rounded-lg h-12 px-4 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all text-sm dark:text-white">
                            @error('password') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Input JPL --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 mb-2">JPL</label>
                        <div class="relative group">
                            <input type="number" name="total_jpl" value="{{ old('total_jpl', 0) }}"
                                class="w-full bg-slate-100 dark:bg-slate-800 border border-transparent dark:border-slate-700 rounded-lg h-12 px-4 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all text-sm dark:text-white">
                        </div>
                    </div>

                    {{-- Row Select Dropdown (3 Kolom) --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 mb-2">Unit Kerja</label>
                            <select name="unit_kerja_id" class="w-full bg-white dark:bg-slate-800 border @error('unit_kerja_id') border-red-500 @else border-gray-200 @enderror dark:border-slate-700 rounded-lg h-12 px-4 text-xs dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all cursor-pointer">
                                <option disabled selected>Pilih Unit Kerja</option>
                                @foreach($unit_kerjas as $uk)
                                    <option value="{{ $uk->unit_kerja_id }}" {{ old('unit_kerja_id') == $uk->unit_kerja_id ? 'selected' : '' }}>{{ $uk->unit_kerja }}</option>
                                @endforeach
                            </select>
                            @error('unit_kerja_id') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 mb-2">Jenis Tenaga</label>
                            <select name="jenis_tenaga_id" class="w-full bg-white dark:bg-slate-800 border @error('jenis_tenaga_id') border-red-500 @else border-gray-200 @enderror dark:border-slate-700 rounded-lg h-12 px-4 text-xs dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all cursor-pointer">
                                <option disabled selected>Pilih Jenis Tenaga</option>
                                @foreach($jenis_tenagas as $jt)
                                    <option value="{{ $jt->jenis_tenaga_id }}" {{ old('jenis_tenaga_id') == $jt->jenis_tenaga_id ? 'selected' : '' }}>{{ $jt->jenis_tenaga }}</option>
                                @endforeach
                            </select>
                            @error('jenis_tenaga_id') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 mb-2">Role/Peran</label>
                            <select name="role_id" class="w-full bg-white dark:bg-slate-800 border @error('role_id') border-red-500 @else border-gray-200 @enderror dark:border-slate-700 rounded-lg h-12 px-4 text-xs dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all cursor-pointer">
                                <option disabled selected>Pilih Role/Peran</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->role_id }}" {{ old('role_id') == $role->role_id ? 'selected' : '' }}>{{ $role->role }}</option>
                                @endforeach
                            </select>
                            @error('role_id') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Status Pengguna dengan Toggle --}}
                    <div class="pt-4">
                        <div class="flex items-center justify-between bg-gray-50 dark:bg-slate-800/40 p-4 rounded-xl border border-gray-100 dark:border-slate-800/60 transition-all">
                            <div class="flex flex-col">
                                <span class="text-xs font-bold text-gray-700 dark:text-white">Status Pengguna</span>
                                <input type="checkbox" name="status" x-model="userStatus" class="hidden">
                                <span class="text-[10px] font-medium transition-colors" 
                                    :class="userStatus ? 'text-emerald-500' : 'text-rose-500'" 
                                    x-text="userStatus ? 'Akun Aktif (Dapat mengakses sistem)' : 'Akun Nonaktif (Akses ditangguhkan)'"></span>
                            </div>

                            {{-- Switch Toggle --}}
                            <button type="button" 
                                @click="userStatus = !userStatus"
                                :class="userStatus ? 'bg-blue-600' : 'bg-gray-300 dark:bg-slate-700'"
                                class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-300 ease-in-out focus:outline-none ring-offset-2 dark:ring-offset-slate-900 focus:ring-2 focus:ring-blue-500/40">
                                
                                {{-- Dot Toggle --}}
                                <span :class="userStatus ? 'translate-x-5' : 'translate-x-0'"
                                    class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow-md ring-0 transition duration-300 ease-in-out">
                                </span>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Footer Button --}}
                <div class="flex justify-end gap-3 mt-10">
                    <a href="{{ route('manajemen-pengguna') }}" class="px-8 py-3 rounded-lg text-sm font-bold text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-800 transition-all flex items-center">
                        Batal
                    </a>
                    <button type="submit" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-10 py-3 rounded-lg text-sm font-bold transition shadow-lg shadow-blue-200 dark:shadow-none active:scale-95">
                        Tambah Pengguna
                    </button>
                </div>
            </form>
        </main>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 10px; }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; }
    
    /* Mencegah input autofill merusak warna dark mode */
    input:-webkit-autofill,
    input:-webkit-autofill:hover, 
    input:-webkit-autofill:focus {
        -webkit-text-fill-color: inherit;
        -webkit-box-shadow: 0 0 0px 1000px transparent inset;
        transition: background-color 5000s ease-in-out 0s;
    }
</style>
@endsection