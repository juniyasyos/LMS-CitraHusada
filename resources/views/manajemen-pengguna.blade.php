@extends('components.layout')
@section('title', 'Manajemen Pengguna')
@section('content')

{{-- Menambahkan state sidebarOpen untuk kontrol menu mobile --}}
<div class="flex h-screen overflow-hidden bg-slate-50 dark:bg-slate-950 transition-colors duration-300" 
    x-data="{ openEdit: false, sidebarOpen: false, darkMode: localStorage.getItem('theme') === 'dark' }">
    
    {{-- Sidebar Responsive Logic --}}
    <aside 
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-slate-900 border-r dark:border-slate-800 transition-transform duration-300 lg:translate-x-0 lg:static lg:inset-0 flex-shrink-0 overflow-y-auto">
        @include('components.nav-superadmin')
    </aside>

    {{-- Overlay untuk menutup sidebar mobile --}}
    <div x-show="sidebarOpen" 
        @click="sidebarOpen = false" 
        x-transition:enter="transition opacity-100 ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:leave="transition opacity-100 ease-in duration-200"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-40 bg-black/50 backdrop-blur-sm lg:hidden" x-cloak>
    </div>

    <div class="flex-1 flex flex-col min-w-0 transition-colors duration-300">
        
        {{-- Header Responsive --}}
        <header class="bg-white dark:bg-slate-900 border-b dark:border-slate-800 h-16 flex items-center justify-between px-4 lg:px-8 flex-shrink-0 z-10 transition-colors duration-300">
            <div class="flex items-center gap-4">
                {{-- Hamburger Button Mobile --}}
                <button @click="sidebarOpen = true" class="lg:hidden p-2 text-gray-500 dark:text-white">
                    <i class="fa-solid fa-bars text-lg"></i>
                </button>
                <h1 class="text-sm font-semibold text-gray-600 dark:text-white truncate">Manajemen Pengguna</h1>
            </div>

            <div class="flex items-center gap-3 lg:gap-6">
                {{-- Search bar disembunyikan di layar sangat kecil --}}
                <div class="relative w-48 lg:w-64 hidden sm:block">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 dark:text-gray-300">
                        <i class="fa-solid fa-magnifying-glass text-[10px]"></i>
                    </span>
                    <input type="text" 
                        class="block w-full pl-8 pr-3 py-1.5 border border-gray-200 dark:border-slate-700 rounded-lg bg-gray-50 dark:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-[11px] text-gray-700 dark:text-white transition-all" 
                        placeholder="Cari data...">
                </div>

                <div class="flex items-center gap-2 lg:gap-4">
                    <div class="">
                        @include('components.notif-superadmin')
                    </div>
                    
                    <div class="flex items-center gap-3 pl-2 lg:pl-4 border-l border-gray-100 dark:border-slate-800">
                        <div class="text-right hidden md:block">
                            <p class="text-xs font-bold text-gray-800 dark:text-white leading-tight">Superadmin</p>
                            <p class="text-[10px] text-gray-500 dark:text-gray-300 font-medium">Utama</p>
                        </div>
                        <div class="w-8 h-8 bg-gray-200 dark:bg-slate-700 rounded-full overflow-hidden border border-gray-100 dark:border-slate-800 flex items-center justify-center">
                            <i class="fa-solid fa-user text-gray-500 dark:text-white text-xs"></i>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-4 lg:p-8 custom-scrollbar">
            <div class="flex flex-col sm:flex-row justify-between items-start gap-4 mb-6">
                <div>
                    <h2 class="text-lg font-bold text-gray-800 dark:text-white transition-colors">Daftar Pengguna</h2>
                    <p class="text-xs lg:text-sm text-gray-500 dark:text-gray-300 mt-1">Kelola data staf dan tenaga medis sistem pembelajaran.</p>
                </div>
                <a href="/tambah-peran" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg flex items-center justify-center gap-2 text-sm font-bold transition shadow-sm active:scale-95">
                    <i class="fa-solid fa-plus text-xs"></i>
                    Tambah Peran
                </a>
            </div>

            <div class="mb-6 flex justify-end">
                <div class="relative w-full sm:w-72">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 dark:text-white">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </span>
                    <input type="text" 
                        class="block w-full pl-9 pr-3 py-2.5 border border-gray-200 dark:border-slate-700 rounded-lg bg-gray-50 dark:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-xs text-gray-700 dark:text-white transition-all placeholder:dark:text-gray-400" 
                        placeholder="Cari nama atau NIK...">
                </div>
            </div>

            {{-- Table Wrapper: overflow-x-auto penting untuk responsivitas --}}
            <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 overflow-x-auto mb-6 transition-colors duration-300">
                <table class="w-full text-left text-xs min-w-[800px]">         
                    <thead class="text-gray-500 dark:text-white font-bold border-b dark:border-slate-800 bg-gray-50/50 dark:bg-slate-800/50">
                        <tr>
                            <th class="py-4 px-6 uppercase tracking-wider">Nama Lengkap</th>
                            <th class="py-4 px-4 uppercase tracking-wider">NIK</th>
                            <th class="py-4 px-4 uppercase tracking-wider">Jenis Tenaga</th>
                            <th class="py-4 px-4 uppercase tracking-wider">Unit Kerja</th>
                            <th class="py-4 px-4 uppercase tracking-wider">JPL</th>
                            <th class="py-4 px-4 uppercase tracking-wider text-center">Status</th>
                            <th class="py-4 px-6 uppercase tracking-wider text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-800 text-gray-700 dark:text-white">
                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-800 transition">
                            <td class="py-5 px-6 font-bold text-gray-800 dark:text-white">dr. Ahmad Subarjo, Sp.B</td>
                            <td class="py-5 px-4 text-gray-500 dark:text-gray-300">198504122010011002</td>
                            <td class="py-5 px-4">
                                <span class="bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-gray-200 px-3 py-1 rounded-md font-semibold transition-colors">Dokter Spesialis</span>
                            </td>
                            <td class="py-5 px-4 whitespace-nowrap">
                                <span class="bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-gray-200 px-3 py-1 rounded-md font-semibold">Bedah Central</span>
                            </td>
                            <td class="py-5 px-4 text-gray-500 dark:text-gray-300">20</td>
                            <td class="py-5 px-4 text-center">
                                <span class="bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 px-3 py-1 rounded font-bold text-[10px]">Aktif</span>
                            </td>
                            <td class="py-5 px-6 text-right space-x-3 text-gray-400 dark:text-white">
                                <div class="flex justify-end gap-3">
                                    <a href="/pembelajaran" class="hover:text-blue-600 transition"><i class="fa-solid fa-eye"></i></a>
                                    <button @click="openEdit = true" class="hover:text-blue-600 transition"><i class="fa-solid fa-pen"></i></button>
                                    <button class="hover:text-red-600 transition"><i class="fa-solid fa-trash-can"></i></button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-8">
                <p class="text-[10px] text-gray-400 dark:text-white font-medium uppercase tracking-wider">Menampilkan 1-5 dari 128 pengguna</p>
                <div class="flex items-center gap-1">
                    <button class="w-8 h-8 flex items-center justify-center border border-gray-200 dark:border-slate-800 rounded-lg text-gray-400 dark:text-white hover:bg-white dark:hover:bg-slate-800 transition"><i class="fa-solid fa-chevron-left text-[10px]"></i></button>
                    <button class="w-8 h-8 flex items-center justify-center bg-blue-600 text-white rounded-lg text-[10px] font-bold shadow-md">1</button>
                    <button class="w-8 h-8 flex items-center justify-center text-gray-400 dark:text-white text-[10px] font-bold hover:bg-white dark:hover:bg-slate-800 transition rounded-lg">2</button>
                    <button class="w-8 h-8 flex items-center justify-center border border-gray-200 dark:border-slate-800 rounded-lg text-gray-400 dark:text-white hover:bg-white dark:hover:bg-slate-800 transition"><i class="fa-solid fa-chevron-right text-[10px]"></i></button>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 rounded-xl border border-gray-100 dark:border-slate-800 p-6 flex items-start gap-4 shadow-sm mb-10 transition-colors">
                <div class="w-10 h-10 bg-blue-50 dark:bg-blue-900/30 rounded-full flex-shrink-0 flex items-center justify-center">
                    <i class="fa-solid fa-lightbulb text-blue-500 dark:text-blue-400"></i>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-gray-800 dark:text-white">Tips Admin</h4>
                    <p class="text-xs text-gray-500 dark:text-gray-300 mt-1 leading-relaxed">Gunakan fitur pencarian untuk mempercepat pelacakan data NIK staf secara instan.</p>
                </div>
            </div>
        </main>
    </div>

    {{-- MODAL EDIT DATA PENGGUNA (Responsive) --}}
    <div x-show="openEdit" 
        class="fixed inset-0 z-[60] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        x-cloak>
        
        <div @click.away="openEdit = false" class="bg-white dark:bg-slate-900 w-full max-w-4xl rounded-2xl shadow-2xl overflow-hidden transition-colors duration-300">
            <div class="flex justify-between items-center px-6 lg:px-8 py-5 border-b border-gray-100 dark:border-slate-800">
                <h2 class="text-base font-bold text-gray-800 dark:text-white">Edit Data Pengguna</h2>
                <button @click="openEdit = false" class="text-gray-400 hover:text-gray-800 dark:hover:text-white transition">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <div class="p-6 lg:p-8 space-y-8 max-h-[80vh] overflow-y-auto custom-scrollbar">
                <div>
                    <h3 class="text-[11px] font-bold text-gray-400 dark:text-white uppercase tracking-widest mb-4">Informasi Personal</h3>
                    <div class="space-y-4 border-t border-gray-50 dark:border-slate-800 pt-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-600 dark:text-white mb-2">Nama Lengkap</label>
                            <input type="text" value="dr. Ahmad Subarjo, Sp.B" class="w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg h-12 px-4 focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all text-sm dark:text-white">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-600 dark:text-white mb-2">NIK</label>
                            <input type="text" value="198504122010011002" class="w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg h-12 px-4 focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all text-sm dark:text-white">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-600 dark:text-white mb-2">JPL</label>
                            <input type="text" value="20" class="w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg h-12 px-4 focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all text-sm dark:text-white">
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-[11px] font-bold text-gray-400 dark:text-white uppercase tracking-widest mb-4">Akses Sistem</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 border-t border-gray-50 dark:border-slate-800 pt-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-600 dark:text-white mb-2">Unit Kerja</label>
                            <select class="w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg h-12 px-4 text-sm dark:text-white outline-none">
                                <option>Instalasi Bedah Central</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-600 dark:text-white mb-2">Jenis Tenaga</label>
                            <select class="w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg h-12 px-4 text-sm dark:text-white outline-none">
                                <option>Dokter Spesialis</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-600 dark:text-white mb-2">Role/Peran</label>
                            <select class="w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg h-12 px-4 text-sm dark:text-white outline-none">
                                <option>Karyawan</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="space-y-6" x-data="{ userStatus: true }">
                    {{-- Input Password dengan Button Reset di Dalam --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-600 dark:text-white mb-2">Password</label>
                        <div class="relative">
                            <input type="text" value="123" 
                                class="w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg h-12 pl-4 pr-32 focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all text-sm dark:text-white"
                                readonly>
                            
                            {{-- Button Reset Password --}}
                            <div class="absolute inset-y-0 right-2 flex items-center">
                                <button type="button" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white text-[10px] font-bold px-3 py-1.5 rounded-md transition-colors active:scale-95">
                                    Reset Password
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Status Pengguna dengan Toggle --}}
                    <div class="flex items-center justify-between bg-gray-50 dark:bg-slate-800/50 p-4 rounded-xl border border-gray-100 dark:border-slate-700">
                        <div class="flex flex-col">
                            <span class="text-xs font-bold text-gray-700 dark:text-white">Status Pengguna</span>
                            <span class="text-[10px] text-gray-500 dark:text-gray-400" x-text="userStatus ? 'Akun ini aktif' : 'Akun ini dinonaktifkan'"></span>
                        </div>

                        {{-- Switch Toggle --}}
                        <button type="button" 
                            @click="userStatus = !userStatus"
                            :class="userStatus ? 'bg-blue-600' : 'bg-gray-300 dark:bg-slate-600'"
                            class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none">
                            
                            {{-- Dot Toggle --}}
                            <span :class="userStatus ? 'translate-x-5' : 'translate-x-0'"
                                class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out">
                            </span>
                        </button>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4 border-t dark:border-slate-800">
                    <button @click="openEdit = false" class="w-full sm:w-auto px-8 py-2.5 border border-gray-200 dark:border-slate-700 text-gray-500 dark:text-white text-xs font-bold rounded-lg hover:bg-gray-50 transition">Batal</button>
                    <button class="w-full sm:w-auto px-8 py-2.5 bg-blue-600 text-white text-xs font-bold rounded-lg hover:bg-blue-700 shadow-lg shadow-blue-100 transition">Simpan Pengeditan</button>
                </div>
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