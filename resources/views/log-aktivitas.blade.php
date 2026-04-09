@extends('components.layout')
@section('title', 'Log Aktivitas')
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
            <h1 class="text-sm font-semibold text-gray-600">Log Aktivitas</h1>
            <div class="flex items-center gap-4">
                <div class="relative">
                    <span class="absolute top-0 right-0 h-2 w-2 bg-red-500 rounded-full border-2 border-white"></span>
                    <i class="fa-solid fa-bell text-gray-500"></i>
                </div>
                <div class="flex items-center gap-3 pl-4 border-l">
                    <div class="text-right">
                        <p class="text-xs font-bold text-gray-800 leading-tight">Superadmin</p>
                        <p class="text-[10px] text-gray-500 font-medium">Administrator Utama</p>
                    </div>
                    <div class="w-8 h-8 bg-gray-200 rounded-full overflow-hidden border">
                        <img src="https://ui-avatars.com/api/?name=Super+Admin" alt="Profile">
                    </div>
                </div>
            </div>
        </header>

        <main class="p-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
                
                <div class="mb-6">
                    <div class="flex items-center gap-2 text-blue-600 mb-1">
                        <i class="fa-solid fa-shield-halved text-xs"></i>
                        <span class="text-[10px] font-bold uppercase tracking-wider">Keamanan & Audit</span>
                    </div>
                    <h2 class="text-xl font-bold text-gray-800">Audit Log Real-time</h2>
                    <p class="text-sm text-gray-500 mt-1 max-w-4xl">
                        Pantau setiap tindakan yang dilakukan oleh administrator dan sistem. Gunakan log ini untuk keperluan audit keamanan, pelacakan perubahan data, dan pertanggungjawaban operasional.
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-3 mb-8">
                    <div class="relative flex-1 min-w-[300px]">
                        <input type="text" 
                            class="w-full pl-4 pr-10 py-2 border border-gray-200 rounded-lg bg-gray-50 text-xs focus:ring-blue-500 focus:border-blue-500" 
                            placeholder="Cari berdasarkan aksi, detail, atau IP...">
                    </div>
                    <button class="flex items-center gap-2 px-4 py-2 border border-gray-200 rounded-lg text-[11px] font-bold text-gray-600 hover:bg-gray-50">
                        <i class="fa-solid fa-filter"></i> Filter Lanjut
                    </button>
                    <button class="flex items-center gap-2 px-4 py-2 border border-gray-200 rounded-lg text-[11px] font-bold text-gray-600 hover:bg-gray-50">
                        <i class="fa-solid fa-calendar-days"></i> Pilih Tanggal
                    </button>
                    <button class="flex items-center gap-2 px-4 py-2 border border-gray-200 rounded-lg text-[11px] font-bold text-gray-600 hover:bg-gray-50">
                        <i class="fa-solid fa-download"></i> Unduh Log
                    </button>
                </div>

                <div class="overflow-x-auto border rounded-xl">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-gray-50 text-gray-500 font-bold border-b">
                            <tr>
                                <th class="py-4 px-6">Tanggal & Waktu</th>
                                <th class="py-4 px-4">Nama Pengguna</th>
                                <th class="py-4 px-4">Aksi</th>
                                <th class="py-4 px-4">Detail Aktivitas</th>
                                <th class="py-4 px-6 text-right">IP Address</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-gray-700">
                            @php
                                $logs = [
                                    ['time' => '2023-11-24 14:22:10', 'user' => 'Budi Santoso', 'role' => 'Superadmin • ADM-001', 'action' => 'Tambah', 'icon' => 'fa-user-plus', 'color' => 'border-gray-200', 'detail' => 'Menambahkan pengguna baru: Siti Aminah (NIP: 19880211)', 'ip' => '192.168.1.105'],
                                    ['time' => '2023-11-24 13:45:05', 'user' => 'dr. Linda Wijaya', 'role' => 'Admin Unit • ADM-042', 'action' => 'Ubah', 'icon' => 'fa-pen-to-square', 'color' => 'border-gray-200', 'detail' => 'Mengubah deskripsi pada modul "Prosedur Sterilisasi Alat"', 'ip' => '192.168.1.112'],
                                    ['time' => '2023-11-24 12:30:11', 'user' => 'Sistem', 'role' => 'System • SYS-AUTO', 'action' => 'Ekspor', 'icon' => 'fa-file-export', 'color' => 'border-gray-200', 'detail' => 'Auto-generate laporan bulanan Unit Bedah (PDF)', 'ip' => '127.0.0.1'],
                                    ['time' => '2023-11-24 11:15:44', 'user' => 'Budi Santoso', 'role' => 'Superadmin • ADM-001', 'action' => 'Hapus', 'icon' => 'fa-trash-can', 'color' => 'bg-red-500 text-white border-red-500', 'detail' => 'Menghapus kategori "Arsip Lama 2019"', 'ip' => '192.168.1.105'],
                                    ['time' => '2023-11-24 09:00:01', 'user' => 'Agus Pratama', 'role' => 'Admin Unit • ADM-015', 'action' => 'Masuk', 'icon' => 'fa-right-to-bracket', 'color' => 'border-gray-200', 'detail' => 'Sesi dimulai untuk Unit Farmasi', 'ip' => '10.20.30.45'],
                                ];
                            @endphp

                            @foreach($logs as $log)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-5 px-6 leading-relaxed">
                                    <p class="font-bold text-gray-800">{{ explode(' ', $log['time'])[0] }}</p>
                                    <p class="text-gray-400">{{ explode(' ', $log['time'])[1] }}</p>
                                </td>
                                <td class="py-5 px-4 leading-tight">
                                    <p class="font-bold text-gray-800">{{ $log['user'] }}</p>
                                    <p class="text-[10px] text-gray-400">{{ $log['role'] }}</p>
                                </td>
                                <td class="py-5 px-4">
                                    <div class="inline-flex items-center gap-2 px-3 py-1.5 border rounded font-bold text-[10px] {{ $log['color'] }}">
                                        <i class="fa-solid {{ $log['icon'] }}"></i>
                                        {{ $log['action'] }}
                                    </div>
                                </td>
                                <td class="py-5 px-4 text-gray-500 max-w-xs leading-relaxed">
                                    {{ $log['detail'] }}
                                </td>
                                <td class="py-5 px-6 text-right text-gray-400 font-medium">
                                    {{ $log['ip'] }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="flex justify-between items-center mt-6">
                    <p class="text-[10px] text-gray-400 font-medium tracking-wide">Menampilkan <span class="font-bold text-gray-600">1-5</span> dari 124 log</p>
                    <div class="flex items-center gap-1">
                        <button class="w-8 h-8 flex items-center justify-center border border-gray-200 rounded-lg text-gray-400"><i class="fa-solid fa-chevron-left text-[10px]"></i></button>
                        <button class="w-8 h-8 flex items-center justify-center bg-blue-600 text-white rounded-lg text-[10px] font-bold">1</button>
                        <button class="w-8 h-8 flex items-center justify-center text-gray-400 text-[10px] font-bold">2</button>
                        <button class="w-8 h-8 flex items-center justify-center text-gray-400 text-[10px] font-bold">3</button>
                        <span class="px-1 text-gray-400 text-[10px]">...</span>
                        <button class="w-8 h-8 flex items-center justify-center text-gray-400 text-[10px] font-bold">12</button>
                        <button class="w-8 h-8 flex items-center justify-center border border-gray-200 rounded-lg text-gray-400"><i class="fa-solid fa-chevron-right text-[10px]"></i></button>
                    </div>
                </div>
            </div>

            <div class="mt-8 bg-white rounded-xl border border-gray-100 p-6 flex items-start gap-4 shadow-sm">
                <div class="w-10 h-10 bg-blue-50 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-circle-info text-blue-600"></i>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-gray-800">Informasi Retensi Data</h4>
                    <p class="text-xs text-gray-500 mt-1 leading-relaxed">
                        Data log aktivitas disimpan secara otomatis selama 365 hari kerja sebelum diarsipkan secara permanen. Jika Anda memerlukan data log yang lebih lama dari periode tersebut, silakan hubungi <span class="font-semibold text-gray-700">Tim IT Infrastruktur Rumah Sakit</span> melalui tiket bantuan internal.
                    </p>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection