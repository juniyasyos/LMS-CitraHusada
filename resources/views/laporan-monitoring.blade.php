@extends('components.layout')
@section('title', 'Manajemen Kategori')
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
            <h1 class="text-sm font-semibold text-gray-600">Manajemen Kategori</h1>
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
                    <div class="w-8 h-8 bg-gray-200 rounded-full overflow-hidden border border-gray-100">
                        <img src="https://ui-avatars.com/api/?name=Super+Admin" alt="Profile">
                    </div>
                </div>
            </div>
        </header>

        <main class="p-8">
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-8">
                <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase">Total Kategori</p>
                        <h3 class="text-xl font-bold text-gray-800">6</h3>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center">
                        </div>
                </div>

                <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase">Total Pelatihan Terkait</p>
                        <h3 class="text-xl font-bold text-gray-800">84</h3>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-emerald-500 flex items-center justify-center">
                        </div>
                </div>

                <div class="bg-transparent p-5 rounded-xl border-2 border-dashed border-gray-200 flex items-center">
                    <p class="text-[10px] text-gray-400 leading-relaxed italic">
                        Gunakan kategori untuk mengelompokkan materi pelatihan agar memudahkan staf medis dalam pencarian modul yang relevan dengan unit kerja mereka.
                    </p>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h2 class="text-lg font-bold text-gray-800">Daftar Kategori Pelatihan</h2>
                        <p class="text-sm text-gray-500 mt-1">Kelola klasifikasi taksonomi materi pembelajaran rumah sakit.</p>
                    </div>
                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg flex items-center gap-2 text-sm font-bold transition shadow-sm">
                        <i class="fa-solid fa-plus text-xs"></i>
                        Tambah Kategori
                    </button>
                </div>

                <div class="mb-6">
                    <div class="relative w-full">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <i class="fa-solid fa-magnifying-glass text-xs"></i>
                        </span>
                        <input type="text" 
                            class="block w-full pl-9 pr-3 py-2 border border-gray-200 rounded-lg bg-white focus:ring-blue-500 focus:border-blue-500 text-xs" 
                            placeholder="Cari nama kategori...">
                    </div>
                </div>

                <div class="overflow-x-auto border rounded-lg">
                    <table class="w-full text-left text-xs">
                        <thead class="text-gray-500 font-bold bg-gray-50 border-b">
                            <tr>
                                <th class="py-4 px-6">Nama Kategori</th>
                                <th class="py-4 px-4 text-center">Jumlah Pelatihan</th>
                                <th class="py-4 px-4 text-center">Terakhir Diperbarui</th>
                                <th class="py-4 px-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-gray-700">
                            @php
                                $categories = [
                                    ['name' => 'Keperawatan Dasar', 'count' => '24 Pelatihan', 'date' => '2023-11-20'],
                                    ['name' => 'Gawat Darurat (ER)', 'count' => '15 Pelatihan', 'date' => '2023-11-18'],
                                    ['name' => 'Prosedur Bedah Sentral', 'count' => '8 Pelatihan', 'date' => '2023-11-15'],
                                    ['name' => 'Administrasi Rekam Medis', 'count' => '12 Pelatihan', 'date' => '2023-11-10'],
                                    ['name' => 'Etika & Hukum Kesehatan', 'count' => '6 Pelatihan', 'date' => '2023-11-05'],
                                    ['name' => 'Farmakologi Klinik', 'count' => '19 Pelatihan', 'date' => '2023-10-28'],
                                ];
                            @endphp

                            @foreach($categories as $cat)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-5 px-6 font-bold text-gray-800">{{ $cat['name'] }}</td>
                                <td class="py-5 px-4 text-center">
                                    <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full font-medium text-[10px]">
                                        {{ $cat['count'] }}
                                    </span>
                                </td>
                                <td class="py-5 px-4 text-center text-gray-500">
                                    {{ $cat['date'] }}
                                </td>
                                <td class="py-5 px-6 text-right space-x-3 text-gray-400">
                                    <button class="hover:text-blue-600 transition"><i class="fa-solid fa-pen"></i></button>
                                    <button class="hover:text-red-600 transition"><i class="fa-solid fa-trash-can"></i></button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="flex justify-between items-center mt-6">
                    <p class="text-[10px] text-gray-400 font-medium">Menampilkan <span class="font-bold">6</span> dari 6 data kategori</p>
                    <div class="flex items-center gap-1">
                        <button class="px-3 py-1.5 border border-gray-200 rounded text-[10px] text-gray-400 font-semibold bg-white" disabled>Sebelumnya</button>
                        <button class="w-8 h-8 flex items-center justify-center bg-blue-600 text-white rounded text-[10px] font-bold">1</button>
                        <button class="px-3 py-1.5 border border-gray-200 rounded text-[10px] text-gray-400 font-semibold bg-white" disabled>Berikutnya</button>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection