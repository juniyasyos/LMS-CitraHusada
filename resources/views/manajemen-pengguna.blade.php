@extends('components.layout')
@section('title', 'Manajemen Pengguna')
@section('content')
{{-- Tambahkan x-data di sini untuk mengontrol modal --}}
<div class="flex h-screen overflow-hidden bg-slate-50" x-data="{ openEdit: false }">
    <aside id="sidebar"
        class="w-64 h-screen bg-white border-r flex-shrink-0 overflow-y-auto">
        @include('components.nav-superadmin')
    </aside>

    <div class="flex-1 flex flex-col min-w-0 bg-slate-50">
        
        <header class="bg-white border-b h-16 flex items-center justify-between px-8 flex-shrink-0 z-10">
            <h1 class="text-sm font-semibold text-gray-600">Manajemen Pengguna</h1>
            <div class="flex items-center gap-4">
                <div class="relative cursor-pointer hover:opacity-70 transition">
                    <span class="absolute top-0 right-0 h-2 w-2 bg-red-500 rounded-full border-2 border-white"></span>
                    <i class="fa-solid fa-bell text-gray-500"></i>
                </div>
                <div class="flex items-center gap-3 pl-4 border-l border-gray-100">
                    <div class="text-right">
                        <p class="text-xs font-bold text-gray-800 leading-tight">Superadmin</p>
                        <p class="text-[10px] text-gray-500 font-medium">Administrator Utama</p>
                    </div>
                    <div class="w-8 h-8 bg-gray-200 rounded-full overflow-hidden border border-gray-100">
                        <img src="https://ui-avatars.com/api/?name=Super+Admin" alt="Profile">
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-8">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h2 class="text-lg font-bold text-gray-800">Daftar Pengguna</h2>
                    <p class="text-sm text-gray-500 mt-1">Kelola data staf dan tenaga medis yang terdaftar dalam sistem pembelajaran.</p>
                </div>
                <a href="/tambah-peran" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg flex items-center gap-2 text-sm font-bold transition shadow-sm active:scale-95">
                    <i class="fa-solid fa-plus text-xs"></i>
                    Tambah Peran
                </a>
            </div>

            <div class="mb-6 flex justify-end">
                <div class="relative w-72">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </span>
                    <input type="text" 
                        class="block w-full pl-9 pr-3 py-2 border border-gray-200 rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-xs transition-all" 
                        placeholder="Cari berdasarkan nama atau NIK...">
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
                <table class="w-full text-left text-xs">
                    <thead class="text-gray-500 font-bold border-b bg-gray-50/50">
                        <tr>
                            <th class="py-4 px-6 uppercase tracking-wider">Nama Lengkap</th>
                            <th class="py-4 px-4 uppercase tracking-wider">NIK</th>
                            <th class="py-4 px-4 uppercase tracking-wider">Jenis Tenaga</th>
                            <th class="py-4 px-4 uppercase tracking-wider">Unit Kerja</th>
                            <th class="py-4 px-4 uppercase tracking-wider">Email</th>
                            <th class="py-4 px-4 uppercase tracking-wider">Status</th>
                            <th class="py-4 px-6 uppercase tracking-wider text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-gray-700">
                        <tr class="hover:bg-gray-50 transition">
                            <td class="py-5 px-6 font-bold text-gray-800">dr. Ahmad Subarjo, Sp.B</td>
                            <td class="py-5 px-4 text-gray-500">198504122010011002</td>
                            <td class="py-5 px-4">
                                <span class="bg-slate-100 text-slate-600 px-3 py-1 rounded-md font-semibold">Dokter Spesialis Bedah</span>
                            </td>
                            <td class="py-5 px-4">
                                <span class="bg-slate-100 text-slate-600 px-3 py-1 rounded-md font-semibold">Instalasi Bedah Central</span>
                            </td>
                            <td class="py-5 px-4 text-gray-500">ahmad.subarjo@hospital.id</td>
                            <td class="py-5 px-4">
                                <span class="bg-blue-100 text-blue-600 px-3 py-1 rounded font-bold text-[10px]">Aktif</span>
                            </td>
                            <td class="py-5 px-6 text-right space-x-3 text-gray-400">
                                <a href="/pembelajaran" class="hover:text-blue-600 transition">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <button @click="openEdit = true" class="hover:text-blue-600 transition"><i class="fa-solid fa-pen"></i></button>
                                <button class="hover:text-red-600 transition"><i class="fa-solid fa-trash-can"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="flex justify-between items-center mb-8">
                <p class="text-[10px] text-gray-400 font-medium uppercase tracking-wider">Menampilkan <span class="font-bold text-gray-600">1-5</span> dari 128 pengguna</p>
                <div class="flex items-center gap-1">
                    <button class="w-8 h-8 flex items-center justify-center border border-gray-200 rounded-lg text-gray-400 hover:bg-white transition"><i class="fa-solid fa-chevron-left text-[10px]"></i></button>
                    <button class="w-8 h-8 flex items-center justify-center bg-blue-600 text-white rounded-lg text-[10px] font-bold">1</button>
                    <button class="w-8 h-8 flex items-center justify-center text-gray-400 text-[10px] font-bold hover:bg-white transition rounded-lg">2</button>
                    <button class="w-8 h-8 flex items-center justify-center border border-gray-200 rounded-lg text-gray-400 hover:bg-white transition"><i class="fa-solid fa-chevron-right text-[10px]"></i></button>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-100 p-6 flex items-start gap-4 shadow-sm mb-10">
                <div class="w-10 h-10 bg-blue-50 rounded-full flex-shrink-0 flex items-center justify-center">
                    <i class="fa-solid fa-lightbulb text-blue-500"></i>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-gray-800">Tips Admin</h4>
                    <p class="text-xs text-gray-500 mt-1 leading-relaxed">Gunakan fitur filter unit kerja untuk mempersempit pencarian data staf secara spesifik.</p>
                </div>
            </div>
        </main>
    </div>

    <div x-show="openEdit" 
        class="fixed inset-0 z-[60] flex items-center justify-center bg-black/50 backdrop-blur-sm"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        x-cloak>
        
        <div @click.away="openEdit = false" class="bg-white w-full max-w-4xl rounded-2xl shadow-2xl overflow-hidden mx-4">
            <div class="flex justify-between items-center px-8 py-5 border-b border-gray-100">
                <h2 class="text-base font-bold text-gray-800">Edit Data Pengguna</h2>
                <button @click="openEdit = false" class="text-gray-400 hover:text-gray-800 transition">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <div class="p-8 space-y-8 max-h-[80vh] overflow-y-auto custom-scrollbar">
                <div>
                    <h3 class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-4">Informasi Personal</h3>
                    <div class="space-y-4 border-t border-gray-50 pt-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-600 mb-2">Nama Lengkap</label>
                            <input type="text" placeholder="Contoh: Agung Sunaryo" class="w-full bg-white border border-gray-200 rounded-lg h-12 px-4 focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all text-sm">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-gray-600 mb-2">Nomor Induk Karyawan</label>
                                <input type="text" placeholder="Contoh: 1234567890" class="w-full bg-white border border-gray-200 rounded-lg h-12 px-4 focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-600 mb-2">Email</label>
                                <input type="email" placeholder="Contoh: asbcv@email.com" class="w-full bg-white border border-gray-200 rounded-lg h-12 px-4 focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all text-sm">
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-4">Akses Sistem</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-t border-gray-50 pt-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-600 mb-2">Unit Kerja</label>
                            <select class="w-full bg-white border border-gray-200 rounded-lg h-12 px-4 focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all text-sm text-gray-400">
                                <option>Pilih Unit Kerja</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-600 mb-2">Role/Peran</label>
                            <select class="w-full bg-white border border-gray-200 rounded-lg h-12 px-4 focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all text-sm text-gray-400">
                                <option>Pilih Role/Peran</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-2">Password Default</label>
                    <div class="flex items-center justify-between border border-gray-200 rounded-lg h-12 px-4 bg-gray-50">
                        <span class="text-sm text-gray-400 italic">Password default: <span class="text-blue-600 font-bold not-italic">123</span></span>
                        <button class="bg-blue-600 hover:bg-blue-700 text-white text-[10px] font-bold px-4 py-1.5 rounded-md transition shadow-sm">Reset Password</button>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <button @click="openEdit = false" class="px-8 py-2.5 border border-gray-200 text-gray-500 text-xs font-bold rounded-lg hover:bg-gray-50 transition">Batal</button>
                    <button class="px-8 py-2.5 bg-blue-600 text-white text-xs font-bold rounded-lg hover:bg-blue-700 shadow-lg shadow-blue-100 transition">Simpan Pengeditan</button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 10px; }
</style>
@endsection