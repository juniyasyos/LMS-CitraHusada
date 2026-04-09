@extends('components.layout')
@section('title', 'Manajemen Pengguna')
@section('content')
<div class="flex min-h-screen bg-slate-50">
    <aside id="sidebar"
        class="fixed lg:static z-40 top-0 left-0 w-64 min-h-screen bg-white border-r
        transform -translate-x-full lg:translate-x-0
        transition-transform duration-200">
        @include('components.nav-superadmin')
    </aside>

    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        
        <header class="bg-white border-b h-16 flex items-center justify-between px-8">
            <h1 class="text-sm font-semibold text-gray-600">Manajemen Pengguna</h1>
            <div class="flex items-center gap-4">
                <div class="relative">
                    <span class="absolute top-0 right-0 h-2 w-2 bg-red-500 rounded-full border-2 border-white"></span>
                    <i class="fa-solid fa-bell text-gray-500"></i>
                </div>
                <div class="flex items-center gap-3">
                    <div class="text-right">
                        <p class="text-xs font-bold text-gray-800 leading-tight">Superadmin</p>
                        <p class="text-[10px] text-gray-500 font-medium">Administrator Utama</p>
                    </div>
                    <div class="w-8 h-8 bg-gray-200 rounded-full overflow-hidden">
                        <img src="https://ui-avatars.com/api/?name=Super+Admin" alt="Profile">
                    </div>
                </div>
            </div>
        </header>

        <main class="p-8">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h2 class="text-lg font-bold text-gray-800">Daftar Pengguna</h2>
                    <p class="text-sm text-gray-500 mt-1">Kelola data staf dan tenaga medis yang terdaftar dalam sistem pembelajaran.</p>
                </div>
                <button class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg flex items-center gap-2 text-sm font-bold transition shadow-sm">
                    <i class="fa-solid fa-plus"></i>
                    Tambah Pengguna
                </button>
            </div>

            <div class="mb-6 flex justify-end">
                <div class="relative w-72">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </span>
                    <input type="text" 
                        class="block w-full pl-9 pr-3 py-2 border border-gray-200 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 text-xs" 
                        placeholder="Cari berdasarkan nama atau NIK...">
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
                <table class="w-full text-left text-xs">
                    <thead class="text-gray-500 font-bold border-b bg-white">
                        <tr>
                            <th class="py-4 px-6 uppercase tracking-wider">Nama Lengkap</th>
                            <th class="py-4 px-4 uppercase tracking-wider">NIK</th>
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
                                <span class="bg-slate-100 text-slate-600 px-3 py-1 rounded-md font-semibold">Instalasi Bedah Central</span>
                            </td>
                            <td class="py-5 px-4 text-gray-500">ahmad.subarjo@hospital.id</td>
                            <td class="py-5 px-4">
                                <span class="bg-blue-100 text-blue-600 px-3 py-1 rounded font-bold text-[10px]">Aktif</span>
                            </td>
                            <td class="py-5 px-6 text-right space-x-3 text-gray-400">
                                <button class="hover:text-blue-600 transition"><i class="fa-solid fa-pen"></i></button>
                                <button class="hover:text-red-600 transition"><i class="fa-solid fa-trash-can"></i></button>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="py-5 px-6 font-bold text-gray-800">Siti Aminah, S.Kep, Ners</td>
                            <td class="py-5 px-4 text-gray-500">199208242015032001</td>
                            <td class="py-5 px-4">
                                <span class="bg-slate-100 text-slate-600 px-3 py-1 rounded-md font-semibold">Ruang Rawat Inap A</span>
                            </td>
                            <td class="py-5 px-4 text-gray-500">siti.aminah@hospital.id</td>
                            <td class="py-5 px-4">
                                <span class="bg-blue-100 text-blue-600 px-3 py-1 rounded font-bold text-[10px]">Aktif</span>
                            </td>
                            <td class="py-5 px-6 text-right space-x-3 text-gray-400">
                                <button class="hover:text-blue-600 transition"><i class="fa-solid fa-pen"></i></button>
                                <button class="hover:text-red-600 transition"><i class="fa-solid fa-trash-can"></i></button>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="py-5 px-6 font-bold text-gray-800">Budi Santoso, S.Kom</td>
                            <td class="py-5 px-4 text-gray-500">198812302012011005</td>
                            <td class="py-5 px-4">
                                <span class="bg-slate-100 text-slate-600 px-3 py-1 rounded-md font-semibold">IT Support & SIMRS</span>
                            </td>
                            <td class="py-5 px-4 text-gray-500">budi.santoso@hospital.id</td>
                            <td class="py-5 px-4">
                                <span class="bg-gray-100 text-gray-400 px-3 py-1 rounded font-bold text-[10px]">Tidak Aktif</span>
                            </td>
                            <td class="py-5 px-6 text-right space-x-3 text-gray-400">
                                <button class="hover:text-blue-600 transition"><i class="fa-solid fa-pen"></i></button>
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
                    <button class="w-8 h-8 flex items-center justify-center bg-blue-600 text-white rounded-lg text-[10px] font-bold shadow-sm shadow-blue-200">1</button>
                    <button class="w-8 h-8 flex items-center justify-center text-gray-400 text-[10px] font-bold hover:bg-white rounded-lg transition">2</button>
                    <button class="w-8 h-8 flex items-center justify-center text-gray-400 text-[10px] font-bold hover:bg-white rounded-lg transition">3</button>
                    <span class="px-1 text-gray-400 text-[10px]">...</span>
                    <button class="w-8 h-8 flex items-center justify-center text-gray-400 text-[10px] font-bold hover:bg-white rounded-lg transition">26</button>
                    <button class="w-8 h-8 flex items-center justify-center border border-gray-200 rounded-lg text-gray-400 hover:bg-white transition"><i class="fa-solid fa-chevron-right text-[10px]"></i></button>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-100 p-6 flex items-start gap-4 shadow-sm">
                <div class="w-10 h-10 bg-blue-50 rounded-full flex-shrink-0 flex items-center justify-center">
                    <i class="fa-solid fa-lightbulb text-blue-500"></i>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-gray-800">Tips Admin</h4>
                    <p class="text-xs text-gray-500 mt-1 leading-relaxed">
                        Anda dapat mengunduh daftar pengguna dalam format Excel melalui menu <span class="font-semibold text-gray-700">Laporan & Monitoring</span>. 
                        Gunakan fitur filter unit kerja untuk mempersempit pencarian data staf secara spesifik.
                    </p>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection