@extends('components.layout')
@section('title', 'Manajemen Unit Kerja')
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
            <h1 class="text-sm font-semibold text-gray-600">Manajemen Unit Kerja</h1>
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
                    <div class="w-8 h-8 bg-gray-200 rounded-full"></div>
                </div>
            </div>
        </header>

        <main class="p-8">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h2 class="text-lg font-bold text-gray-800">Daftar Unit Kerja</h2>
                    <p class="text-sm text-gray-500 mt-1">Kelola struktur organisasi rumah sakit untuk penugasan pelatihan yang tepat.</p>
                </div>
                <button class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg flex items-center gap-2 text-sm font-bold transition shadow-sm">
                    <i class="fa-solid fa-plus"></i>
                    Tambah Unit Kerja
                </button>
            </div>

            <div class="mb-6">
                <p class="text-sm text-gray-500 mb-4">
                    <span class="font-bold text-gray-700">Informasi Unit</span> Total 5 unit terdaftar dalam sistem.
                </p>
                <div class="flex justify-end">
                    <div class="relative w-64">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <i class="fa-solid fa-magnifying-glass text-xs"></i>
                        </span>
                        <input type="text" 
                            class="block w-full pl-9 pr-3 py-2 border border-gray-200 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 text-xs" 
                            placeholder="Cari unit kerja...">
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-8">
                <table class="w-full text-left text-xs">
                    <thead class="text-gray-500 font-bold border-b bg-white">
                        <tr>
                            <th class="py-4 px-6 uppercase tracking-wider">Nama Unit Kerja</th>
                            <th class="py-4 px-4 uppercase tracking-wider text-center">Jumlah Karyawan</th>
                            <th class="py-4 px-4 uppercase tracking-wider">Keterangan</th>
                            <th class="py-4 px-6 uppercase tracking-wider text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @php
                            $units = [
                                ['id' => 1, 'name' => 'Instalasi Gawat Darurat (IGD)', 'count' => '45 Orang', 'desc' => 'Unit pelayanan medis darurat 24 jam'],
                                ['id' => 2, 'name' => 'Intensive Care Unit (ICU)', 'count' => '28 Orang', 'desc' => 'Unit perawatan intensif untuk...'],
                                ['id' => 3, 'name' => 'Farmasi', 'count' => '15 Orang', 'desc' => 'Pengelolaan dan penyediaan'],
                                ['id' => 4, 'name' => 'Radiologi', 'count' => '12 Orang', 'desc' => 'Layanan diagnostik menggunakan'],
                                ['id' => 5, 'name' => 'Poliklinik Anak', 'count' => '22 Orang', 'desc' => 'Layanan kesehatan rawat jalan'],
                            ];
                        @endphp

                        @foreach($units as $unit)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="py-5 px-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                                        <i class="fa-solid fa-building text-blue-500 text-lg"></i>
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-800 text-sm">{{ $unit['name'] }}</p>
                                        <p class="text-gray-400 mt-0.5">ID: {{ $unit['id'] }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-5 px-4 text-center">
                                <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full font-bold text-[10px]">
                                    {{ $unit['count'] }}
                                </span>
                            </td>
                            <td class="py-5 px-4 text-gray-500 max-w-xs truncate">
                                {{ $unit['desc'] }}
                            </td>
                            <td class="py-5 px-6 text-right space-x-3">
                                <button class="text-gray-400 hover:text-blue-600 transition"><i class="fa-solid fa-pen"></i></button>
                                <button class="text-gray-400 hover:text-red-600 transition"><i class="fa-solid fa-trash-can"></i></button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm flex gap-4">
                    <div class="w-10 h-10 bg-gray-100 rounded-full flex-shrink-0 flex items-center justify-center">
                        <i class="fa-solid fa-circle-info text-gray-800"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-gray-800">Organisasi Terpadu</h4>
                        <p class="text-xs text-gray-500 mt-1 leading-relaxed">Data unit kerja akan otomatis disinkronkan dengan modul Manajemen Pengguna.</p>
                    </div>
                </div>
                <div class="bg-green-50 p-6 rounded-xl border border-green-100 shadow-sm flex gap-4">
                    <div class="w-10 h-10 bg-white rounded-full flex-shrink-0 flex items-center justify-center">
                        <i class="fa-solid fa-chevron-right text-green-600"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-gray-800">Target Pelatihan</h4>
                        <p class="text-xs text-gray-500 mt-1 leading-relaxed">Unit kerja digunakan sebagai filter utama saat mendistribusikan media pelatihan.</p>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm flex gap-4">
                    <div class="w-10 h-10 bg-gray-100 rounded-full flex-shrink-0 flex items-center justify-center">
                        <i class="fa-solid fa-hospital text-gray-800"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-gray-800">Hierarki Rumah Sakit</h4>
                        <p class="text-xs text-gray-500 mt-1 leading-relaxed">Pastikan nama unit kerja sesuai dengan standar administrasi rumah sakit.</p>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection