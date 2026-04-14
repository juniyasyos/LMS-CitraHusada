@extends('components.layout')
@section('title', 'Detail Leaderboard')

@section('content')
<div class="flex h-screen overflow-hidden bg-slate-50">
    <aside id="sidebar" class="w-64 h-screen bg-white border-r flex-shrink-0 overflow-y-auto">
        @include('components.nav-superadmin', ['hideSideMenu' => true])
    </aside>

    <div class="flex-1 flex flex-col min-w-0 bg-slate-50">
        
        <header class="bg-white border-b h-16 flex items-center justify-between px-8 flex-shrink-0 z-10">
            <h1 class="text-sm font-semibold text-gray-800">Beranda</h1>
            
            <div class="flex items-center gap-6">
                <div class="relative w-64">
                    <input type="text" placeholder="Cari data..." 
                        class="block w-full pl-4 pr-10 py-1.5 border border-gray-200 rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-xs transition-all">
                    <i class="fa-solid fa-magnifying-glass absolute right-3 top-2.5 text-gray-400 text-[10px]"></i>
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

        <main class="flex-1 overflow-y-auto p-8">
            
            <nav class="mb-6 text-[14px] font-medium">
                <ol class="flex items-center gap-2 text-gray-500">
                    <li>
                        <a href="/beranda-superadmin" class="hover:text-blue-600 transition-colors">
                            Beranda
                        </a>
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="text-gray-300"> > </span>
                        <span class="text-gray-800 font-semibold">Detail Leaderboard</span>
                    </li>
                </ol>
            </nav>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
                <div class="p-6 border-b flex justify-between items-center bg-white">
                    <h3 class="font-bold text-gray-800">Leaderboard Jam Pembelajaran pada Setiap Unit Kerja</h3>
                    <div class="relative inline-block text-left" x-data="{ open: false }">
                        <button @click="open = !open" 
                            class="flex items-center gap-2 px-4 py-2 border border-gray-200 rounded-lg text-xs font-bold text-gray-500 hover:bg-gray-50 transition active:scale-95 bg-white">
                            Terapkan Filter 
                            <i class="fa-solid fa-sliders text-[10px]"></i>
                        </button>

                        <div x-show="open" 
                            @click.away="open = false"
                            x-cloak
                            class="absolute right-0 mt-2 w-48 bg-white border border-gray-100 rounded-xl shadow-xl z-50 overflow-hidden">
                            
                            <div class="p-2 space-y-1">
                                <div class="px-3 py-1 mb-1">
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Status JPL</p>
                                </div>

                                <button class="flex items-center justify-between w-full px-3 py-2 text-[11px] font-bold text-gray-600 rounded-lg hover:bg-emerald-50 hover:text-emerald-600 transition group">
                                    Terpenuhi
                                    <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                                </button>

                                <button class="flex items-center justify-between w-full px-3 py-2 text-[11px] font-bold text-gray-600 rounded-lg hover:bg-amber-50 hover:text-amber-600 transition group">
                                    Belum Terpenuhi
                                    <div class="w-2 h-2 rounded-full bg-amber-500"></div>
                                </button>
                            </div>

                            <div class="border-t border-gray-50 p-2">
                                <button class="w-full py-1 text-[10px] font-bold text-red-400 hover:text-red-600 transition">
                                    Reset Filter
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-gray-50/50 text-gray-500 font-bold border-b">
                            <tr>
                                <th class="py-4 px-6 uppercase tracking-wider">Nama Karyawan</th>
                                <th class="py-4 px-4 uppercase tracking-wider">Unit Kerja</th>
                                <th class="py-4 px-4 uppercase tracking-wider text-center">Pelatihan yang Telah Diikuti</th>
                                <th class="py-4 px-4 uppercase tracking-wider text-center">JPL</th>
                                <th class="py-4 px-6 uppercase tracking-wider text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-gray-700 font-medium">
                            @php
                                $leaderboard = [
                                    ['name' => 'dr. Ahmad Subarjo', 'nip' => '198103101', 'unit' => 'Unit Gawat Darurat', 'count' => 4, 'jpl' => 20, 'status' => 'Terpenuhi'],
                                    ['name' => 'Siti Aminah, S.Kep', 'nip' => '198203201', 'unit' => 'Keperawatan Rawat Inap', 'count' => 1, 'jpl' => 6, 'status' => 'Belum Terpenuhi'],
                                    ['name' => 'Budi Santoso', 'nip' => '198303301', 'unit' => 'Administrasi & Keuangan', 'count' => 7, 'jpl' => 35, 'status' => 'Terpenuhi'],
                                    ['name' => 'dr. Lilis Handayani', 'nip' => '198403401', 'unit' => 'Poliklinik Spesialis', 'count' => 2, 'jpl' => 9, 'status' => 'Belum Terpenuhi'],
                                    ['name' => 'Rahmat Hidayat', 'nip' => '198503501', 'unit' => 'Farmasi', 'count' => 5, 'jpl' => 25, 'status' => 'Terpenuhi'],
                                    ['name' => 'dr. Ahmad Subarjo', 'nip' => '198103101', 'unit' => 'Unit Gawat Darurat', 'count' => 4, 'jpl' => 20, 'status' => 'Terpenuhi'],
                                    ['name' => 'Siti Aminah, S.Kep', 'nip' => '198203201', 'unit' => 'Keperawatan Rawat Inap', 'count' => 1, 'jpl' => 6, 'status' => 'Belum Terpenuhi'],
                                    ['name' => 'Budi Santoso', 'nip' => '198303301', 'unit' => 'Administrasi & Keuangan', 'count' => 7, 'jpl' => 35, 'status' => 'Terpenuhi'],
                                ];
                            @endphp

                            @foreach($leaderboard as $item)
                            <tr class="hover:bg-gray-50 transition border-b border-gray-50 last:border-0">
                                <td class="py-5 px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 bg-gray-200 rounded flex items-center justify-center font-bold text-gray-500 text-[10px] uppercase">
                                            {{ substr($item['name'], 0, 1) }}{{ substr(strrchr($item['name'], " "), 1, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-800">{{ $item['name'] }}</p>
                                            <p class="text-[10px] text-gray-400">NIP: {{ $item['nip'] }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-5 px-4 text-gray-500 leading-tight">{{ $item['unit'] }}</td>
                                <td class="py-5 px-4 text-center font-bold text-gray-800">{{ $item['count'] }}</td>
                                <td class="py-5 px-4 text-center font-bold text-gray-800">{{ $item['jpl'] }}</td>
                                <td class="py-5 px-6 text-center">
                                    <span class="px-3 py-1 rounded-full text-[9px] font-bold {{ $item['status'] == 'Terpenuhi' ? 'bg-emerald-50 text-emerald-500 border border-emerald-100' : 'bg-amber-50 text-amber-500 border border-amber-100' }}">
                                        {{ strtoupper($item['status']) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="flex justify-end gap-3 mb-8">
                <button class="flex items-center gap-2 px-5 py-2.5 border-2 border-blue-600 text-blue-600 bg-white rounded-lg text-[11px] font-bold hover:bg-blue-50 transition active:scale-95">
                    <i class="fa-solid fa-file-excel"></i> Export Excel
                </button>
                <button class="flex items-center gap-2 px-5 py-2.5 border-2 border-red-500 text-red-500 bg-white rounded-lg text-[11px] font-bold hover:bg-red-50 transition active:scale-95">
                    <i class="fa-solid fa-file-pdf"></i> Export PDF
                </button>
            </div>

            <div class="bg-white rounded-xl border border-gray-100 p-6 flex items-start gap-4 shadow-sm mb-10">
                <div class="w-8 h-8 bg-gray-100 rounded-full flex-shrink-0 flex items-center justify-center">
                    <i class="fa-solid fa-check text-gray-600 text-xs"></i>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-gray-800">Informasi</h4>
                    <p class="text-xs text-gray-500 mt-1 leading-relaxed">
                        Status <span class="font-bold text-gray-700">"Terpenuhi"</span> memiliki arti bahwa sudah mencapai batas minimum pencapaian JPL yang telah ditetapkan.
                    </p>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection