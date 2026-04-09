@extends('components.layout')
@section('title', 'Beranda Dashboard')

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
            <h1 class="text-sm font-semibold text-gray-600">Beranda Dashboard</h1>
            
            <div class="flex items-center gap-6">
                <div class="relative w-48 lg:w-64 hidden sm:block">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <i class="fa-solid fa-magnifying-glass text-[10px]"></i>
                    </span>
                    <input type="text" 
                        class="block w-full pl-8 pr-3 py-1.5 border border-gray-200 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 text-[11px]" 
                        placeholder="Cari data atau laporan...">
                </div>

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
            </div>
        </header>

        <main class="p-8">
            
            <div class="mb-8">
                <h2 class="text-xl font-bold text-gray-800">Selamat Datang, Superadmin</h2>
                <p class="text-sm text-gray-500 mt-1">Pantau statistik pelatihan dan aktivitas sistem <span class="font-semibold text-gray-700">Hospital LMS</span> hari ini.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
                @php
                    $stats = [
                        ['label' => 'Total Pengguna', 'value' => '1,284', 'sub' => 'Karyawan terdaftar aktif', 'icon' => 'fa-users', 'color' => 'text-blue-600'],
                        ['label' => 'Total Unit Kerja', 'value' => '24', 'sub' => 'Departemen terintegrasi', 'icon' => 'fa-building', 'color' => 'text-emerald-600'],
                        ['label' => 'Total Pelatihan', 'value' => '156', 'sub' => 'Modul pelatihan tersedia', 'icon' => 'fa-book-open', 'color' => 'text-purple-600'],
                        ['label' => 'Pelatihan Aktif', 'value' => '42', 'sub' => 'Sedang berjalan saat ini', 'icon' => 'fa-clock', 'color' => 'text-orange-600'],
                        ['label' => 'Pelatihan Selesai', 'value' => '114', 'sub' => 'Sertifikat telah diterbitkan', 'icon' => 'fa-certificate', 'color' => 'text-pink-600'],
                    ];
                @endphp

                @foreach($stats as $stat)
                <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm transition hover:shadow-md group">
                    <div class="flex justify-between items-start mb-3">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">{{ $stat['label'] }}</p>
                        <i class="fa-solid {{ $stat['icon'] }} {{ $stat['color'] }} opacity-20 text-sm group-hover:opacity-100 transition-opacity"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $stat['value'] }}</h3>
                    <p class="text-[10px] text-gray-400 mt-1 font-medium">{{ $stat['sub'] }}</p>
                </div>
                @endforeach
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <div class="lg:col-span-2 bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                    <div class="mb-6">
                        <h3 class="font-bold text-gray-800">Ikhtisar Penyelesaian Pelatihan</h3>
                        <p class="text-xs text-gray-400">Perbandingan modul direncanakan vs selesai (6 bulan terakhir)</p>
                    </div>

                    @php
                        $data = [
                            ['month' => 'Jan', 'plan' => 80, 'done' => 50],
                            ['month' => 'Feb', 'plan' => 60, 'done' => 30],
                            ['month' => 'Mar', 'plan' => 40, 'done' => 45],
                            ['month' => 'Apr', 'plan' => 75, 'done' => 85],
                            ['month' => 'May', 'plan' => 55, 'done' => 50],
                            ['month' => 'Jun', 'plan' => 65, 'done' => 45],
                        ];
                    @endphp

                    <div class="relative h-56 flex items-end justify-between px-2">
                        <div class="absolute inset-0 flex flex-col justify-between py-1 pointer-events-none">
                            <div class="border-t border-gray-50 w-full"></div>
                            <div class="border-t border-gray-50 w-full"></div>
                            <div class="border-t border-gray-50 w-full"></div>
                            <div class="border-t border-gray-50 w-full"></div>
                        </div>

                        @foreach ($data as $item)
                        <div class="relative flex flex-col items-center flex-1 h-full">
                            <div class="flex items-end gap-1.5 h-full">
                                <div class="w-2.5 bg-blue-500 rounded-t-sm transition-all duration-700 hover:bg-blue-600 cursor-pointer" 
                                     style="height: {{ $item['plan'] }}%" title="Rencana: {{ $item['plan'] }}"></div>
                                <div class="w-2.5 bg-emerald-500 rounded-t-sm transition-all duration-700 hover:bg-emerald-600 cursor-pointer" 
                                     style="height: {{ $item['done'] }}%" title="Selesai: {{ $item['done'] }}"></div>
                            </div>
                            <span class="text-[10px] font-bold text-gray-400 mt-3 uppercase tracking-tighter">{{ $item['month'] }}</span>
                        </div>
                        @endforeach
                    </div>

                    <div class="flex items-center gap-6 mt-8 pt-4 border-t border-gray-50">
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 bg-blue-500 rounded-full shadow-sm"></div>
                            <span class="text-[10px] font-bold text-gray-500 uppercase tracking-tighter">Direncanakan</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 bg-emerald-500 rounded-full shadow-sm"></div>
                            <span class="text-[10px] font-bold text-gray-500 uppercase tracking-tighter">Selesai</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm flex flex-col">
                    <div class="mb-6">
                        <h3 class="font-bold text-gray-800">Distribusi Unit Kerja</h3>
                        <p class="text-xs text-gray-400">Partisipasi pelatihan tiap departemen</p>
                    </div>

                    <div class="flex justify-center mb-8">
                        <div class="w-36 h-36 rounded-full relative shadow-inner"
                            style="background: conic-gradient(#3b82f6 0% 35%, #10b981 35% 55%, #f43f5e 55% 70%, #f59e0b 70% 85%, #8b5cf6 85% 100%);">
                            <div class="absolute inset-8 bg-white rounded-full flex items-center justify-center shadow-sm">
                                <span class="text-xs font-bold text-gray-700">100%</span>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-3 flex-1 overflow-y-auto pr-1">
                        @php
                            $items = [
                                ['label' => 'Keperawatan', 'val' => 450, 'color' => 'bg-blue-500'],
                                ['label' => 'Farmasi', 'val' => 300, 'color' => 'bg-emerald-500'],
                                ['label' => 'Administrasi', 'val' => 200, 'color' => 'bg-rose-500'],
                                ['label' => 'Radiologi', 'val' => 150, 'color' => 'bg-amber-500'],
                                ['label' => 'Gawat Darurat', 'val' => 184, 'color' => 'bg-violet-500'],
                            ];
                        @endphp
                        @foreach($items as $unit)
                        <div class="flex justify-between items-center group cursor-default">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 {{ $unit['color'] }} rounded-sm"></div>
                                <span class="text-[11px] font-medium text-gray-600 group-hover:text-gray-800 transition">{{ $unit['label'] }}</span>
                            </div>
                            <span class="text-[11px] font-bold text-gray-800">{{ $unit['val'] }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="font-bold text-gray-800">Log Aktivitas Terkini</h3>
                        <a href="#" class="text-[10px] font-bold text-blue-600 uppercase tracking-widest hover:underline transition">Lihat Semua</a>
                    </div>

                    <div class="space-y-5">
                        <div class="flex gap-4">
                            <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
                                <i class="fa-solid fa-plus text-blue-500 text-xs"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-700 leading-snug"><span class="font-bold text-gray-900">Superadmin</span> menambahkan modul pelatihan <span class="font-semibold text-blue-600 hover:underline cursor-pointer">"Prosedur IGD"</span></p>
                                <p class="text-[10px] text-gray-400 mt-1 font-medium italic">10 menit yang lalu</p>
                            </div>
                        </div>

                        <div class="flex gap-4 border-t border-gray-50 pt-4">
                            <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center flex-shrink-0">
                                <i class="fa-solid fa-rotate text-emerald-500 text-xs"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-700 leading-snug"><span class="font-bold text-gray-900">Admin HR</span> memperbarui data unit <span class="font-semibold text-gray-900">"Farmasi"</span></p>
                                <p class="text-[10px] text-gray-400 mt-1 font-medium italic">45 menit yang lalu</p>
                            </div>
                        </div>

                        <div class="flex gap-4 border-t border-gray-50 pt-4">
                            <div class="w-8 h-8 rounded-lg bg-rose-50 flex items-center justify-center flex-shrink-0">
                                <i class="fa-solid fa-key text-rose-500 text-xs"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-700 leading-snug"><span class="font-bold text-gray-900">Superadmin</span> melakukan reset password pengguna <span class="font-semibold text-gray-800">Dr. Andi Pratama</span></p>
                                <p class="text-[10px] text-gray-400 mt-1 font-medium italic">2 jam yang lalu</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-green-50 p-6 rounded-xl border border-green-100 shadow-sm flex flex-col gap-4">
                    <h3 class="font-bold text-green-800 text-sm flex items-center gap-2">
                        <i class="fa-solid fa-circle-info"></i>
                        Informasi Penting
                    </h3>

                    <div class="bg-white p-4 rounded-lg border border-green-100 shadow-sm hover:shadow-md transition">
                        <p class="text-xs font-bold text-gray-800 mb-1 leading-tight">Pemeliharaan Rutin</p>
                        <p class="text-[10px] text-gray-500 leading-relaxed">
                            Sistem akan menjalani pemeliharaan rutin pada Minggu, 20 Oktober pukul 02.00 - 04.00 WIB.
                        </p>
                    </div>

                    <div class="bg-white p-4 rounded-lg border border-green-100 shadow-sm hover:shadow-md transition">
                        <p class="text-xs font-bold text-gray-800 mb-1 leading-tight">Fitur Baru: Export Laporan PDF</p>
                        <p class="text-[10px] text-gray-500 leading-relaxed">
                            Kini Anda dapat mengunduh laporan monitoring pelatihan dalam format PDF secara langsung.
                        </p>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>
@endsection