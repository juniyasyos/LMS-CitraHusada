@extends('components.layout')
@section('title', 'Beranda Dashboard')

@section('content')

{{-- PENAMBAHAN: Inisialisasi Alpine.js untuk fitur Dark Mode dan Sidebar Mobile --}}
{{-- PERUBAHAN: Penambahan class dark:bg-slate-950 dan transition-colors --}}
<div class="flex h-screen overflow-hidden bg-slate-50 dark:bg-slate-950 transition-colors duration-300"
    x-data="{ 
        darkMode: localStorage.getItem('theme') === 'dark',
        sidebarOpen: false 
    }">
    
    <aside
        {{-- PERUBAHAN: Sidebar sekarang menggunakan logic :class untuk responsive mobile (translate-x) --}}
        {{-- PERUBAHAN: Penambahan class dark:bg-slate-900 dan dark:border-slate-800 --}}
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-slate-900 border-r dark:border-slate-800 transition-transform duration-300 lg:translate-x-0 lg:static lg:inset-0 flex-shrink-0 overflow-y-auto">
        @include('components.nav-superadmin')
    </aside>

    {{-- PENAMBAHAN: Overlay hitam transparan saat sidebar mobile terbuka --}}
    <div x-show="sidebarOpen" 
        @click="sidebarOpen = false" 
        x-transition:enter="transition opacity-100 ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:leave="transition opacity-100 ease-in duration-200"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-40 bg-black/50 backdrop-blur-sm lg:hidden" x-cloak>
    </div>

    <div class="flex-1 flex flex-col min-w-0 transition-colors duration-300">
        
        {{-- PERUBAHAN: Header kini mendukung Dark Mode dan padding px-4 pada mobile --}}
        <header class="bg-white dark:bg-slate-900 border-b dark:border-slate-800 h-16 flex items-center justify-between px-4 lg:px-8 flex-shrink-0 z-10 transition-colors duration-300">
            
            <div class="flex items-center gap-4">
                {{-- PENAMBAHAN: Tombol Hamburger (fa-bars) untuk membuka sidebar di mobile --}}
                <button @click="sidebarOpen = true" class="lg:hidden p-2 text-gray-500 dark:text-white hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition">
                    <i class="fa-solid fa-bars text-lg"></i>
                </button>
                {{-- PERUBAHAN: Penambahan class uppercase, tracking-wider, dan dark:text-white --}}
                <h1 class="text-sm font-semibold text-gray-600 dark:text-white truncate uppercase tracking-wider">Dashboard</h1>
            </div>
            
            <div class="flex items-center gap-3 lg:gap-6">
                {{-- PERUBAHAN: Search bar kini mendukung Dark Mode (bg-slate-800) --}}
                <div class="relative w-48 lg:w-64 hidden md:block">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 dark:text-gray-300">
                        <i class="fa-solid fa-magnifying-glass text-[10px]"></i>
                    </span>
                    <input type="text" 
                        class="block w-full pl-8 pr-3 py-1.5 border border-gray-200 dark:border-slate-700 rounded-lg bg-gray-50 dark:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-[11px] text-gray-700 dark:text-white transition-all" 
                        placeholder="Cari data...">
                </div>

                <div class="flex items-center gap-2 lg:gap-4">
                    {{-- PERUBAHAN: Notifikasi kini dipisah ke dalam komponen blade tersendiri --}}
                    <div class="">
                        @include('components.notif-superadmin')
                    </div>
                    
                    <div class="flex items-center gap-3 pl-2 lg:pl-4 border-l border-gray-100 dark:border-slate-800">
                        <div class="text-right hidden sm:block">
                            <p class="text-xs font-bold text-gray-800 dark:text-white leading-tight">Superadmin</p>
                            {{-- PERUBAHAN: Penambahan class italic dan dark:text-gray-300 --}}
                            <p class="text-[10px] text-gray-500 dark:text-gray-300 font-medium italic">Administrator Utama</p>
                        </div>
                        {{-- PERUBAHAN: Gambar profile (ui-avatars) diganti dengan icon font-awesome (fa-user) --}}
                        <div class="w-8 h-8 bg-gray-200 dark:bg-slate-700 rounded-full overflow-hidden border border-gray-100 dark:border-slate-800 flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-user text-gray-500 dark:text-white text-xs"></i>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        {{-- PERUBAHAN: Penambahan class custom-scrollbar dan padding responsive p-4 --}}
        <main class="flex-1 overflow-y-auto p-4 lg:p-8 custom-scrollbar">

            <div class="mb-8">
                {{-- PERUBAHAN: Ukuran text responsif (text-lg lg:text-xl) dan dark mode --}}
                <h2 class="text-lg lg:text-xl font-bold text-gray-800 dark:text-white transition-colors">Selamat Datang, Superadmin</h2>
                <p class="text-xs lg:text-sm text-gray-500 dark:text-gray-200 mt-1 italic">Pantau statistik pelatihan sistem <span class="font-semibold text-gray-700 dark:text-white">Hospital LMS</span> hari ini.</p>
            </div>

            {{-- PERUBAHAN: Grid cols diubah menjadi grid-cols-2 untuk tampilan mobile yang lebih rapi --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 lg:gap-4 mb-8">
                @php
                    $stats = [
                        ['label' => 'Total Pengguna', 'value' => '1,284', 'sub' => 'Karyawan terdaftar aktif', 'icon' => 'fa-users', 'color' => 'text-blue-600'],
                        ['label' => 'Total Unit', 'value' => '24', 'sub' => 'Departemen terintegrasi', 'icon' => 'fa-building', 'color' => 'text-emerald-600'],
                        ['label' => 'Jenis Tenaga', 'value' => '156', 'sub' => 'Jenis tenaga kerja tersedia', 'icon' => 'fa-id-card-clip', 'color' => 'text-indigo-600'],
                        ['label' => 'Total Modul', 'value' => '156', 'sub' => 'Modul pelatihan tersedia', 'icon' => 'fa-book-open', 'color' => 'text-purple-600'],
                        ['label' => 'Aktif', 'value' => '42', 'sub' => 'Sedang berjalan saat ini', 'icon' => 'fa-clock', 'color' => 'text-orange-600'],
                        ['label' => 'Selesai', 'value' => '114', 'sub' => 'Melewati batas waktu', 'icon' => 'fa-certificate', 'color' => 'text-pink-600'],
                    ];
                @endphp

                @foreach($stats as $stat)
                {{-- PERUBAHAN: Card stat kini mendukung Dark Mode (dark:bg-slate-900) --}}
                <div class="bg-white dark:bg-slate-900 p-4 lg:p-5 rounded-xl border border-gray-100 dark:border-slate-800 shadow-sm transition-all hover:shadow-md group">
                    <div class="flex justify-between items-start mb-3">
                        <p class="text-[10px] lg:text-[10px] font-bold text-gray-400 dark:text-white uppercase tracking-wider">{{ $stat['label'] }}</p>
                        <i class="fa-solid {{ $stat['icon'] }} {{ $stat['color'] }} opacity-20 dark:opacity-60 text-xs lg:text-sm group-hover:opacity-100 transition-opacity"></i>
                    </div>
                    <h3 class="text-lg lg:text-2xl font-bold text-gray-800 dark:text-white">{{ $stat['value'] }}</h3>
                    <p class="text-[9px] lg:text-[10px] text-gray-400 dark:text-gray-200 mt-1 font-medium leading-tight truncate">{{ $stat['sub'] }}</p>
                </div>
                @endforeach
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                {{-- Grafik Keaktifan --}}
                {{-- PERUBAHAN: Container grafik mendukung Dark Mode dan penambahan overflow-hidden --}}
                <div class="lg:col-span-2 bg-white dark:bg-slate-900 p-4 lg:p-6 rounded-xl border border-gray-100 dark:border-slate-800 shadow-sm transition-colors overflow-hidden">
                    <div class="mb-6 flex justify-between items-center">
                        <h3 class="font-bold text-sm lg:text-base text-gray-800 dark:text-white transition-colors">Grafik Keaktifan</h3>
                        {{-- PENAMBAHAN: Badge status "Real-time" --}}
                        <span class="text-[10px] font-bold text-blue-600 dark:text-blue-400 uppercase bg-blue-50 dark:bg-blue-900/30 px-2 py-1 rounded">Real-time</span>
                    </div>

                    {{-- PERUBAHAN: Penambahan overflow-x-auto agar grafik bisa di-scroll secara horizontal di mobile --}}
                    <div class="relative h-56 flex items-end justify-between px-2 overflow-x-auto custom-scrollbar-h pb-2">
                        <div class="absolute inset-0 flex flex-col justify-between py-1 pointer-events-none">
                            {{-- PERUBAHAN: Menggunakan loop @for untuk garis background (sebelumnya div manual) --}}
                            @for ($i = 0; $i < 4; $i++)
                                <div class="border-t border-gray-50 dark:border-slate-800 w-full"></div>
                            @endfor
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

                        @foreach ($data as $item)
                        {{-- PERUBAHAN: Penambahan min-w-[50px] agar bar tidak terlalu gepeng di mobile --}}
                        <div class="relative flex flex-col items-center flex-1 min-w-[50px] h-full">
                            <div class="flex items-end gap-1 lg:gap-1.5 h-full">
                                {{-- PERUBAHAN: Penambahan hover:brightness-125 untuk efek interaksi --}}
                                <div class="w-1.5 lg:w-2.5 bg-red-500 rounded-t-sm transition-all duration-700 hover:brightness-125 cursor-pointer shadow-sm" 
                                    style="height: {{ $item['plan'] }}%" title="Rencana: {{ $item['plan'] }}"></div>
                                <div class="w-1.5 lg:w-2.5 bg-green-500 rounded-t-sm transition-all duration-700 hover:brightness-125 cursor-pointer shadow-sm" 
                                    style="height: {{ $item['done'] }}%" title="Selesai: {{ $item['done'] }}"></div>
                            </div>
                            <span class="text-[9px] lg:text-[10px] font-bold text-gray-400 dark:text-white mt-3 uppercase tracking-tighter">{{ $item['month'] }}</span>
                        </div>
                        @endforeach
                    </div>

                    {{-- PERUBAHAN: Legend grafik kini mendukung Dark Mode --}}
                    <div class="flex items-center justify-center gap-4 lg:gap-6 mt-8 pt-4 border-t border-gray-50 dark:border-slate-800 transition-colors">
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                            <span class="text-[9px] lg:text-[10px] font-bold text-gray-500 dark:text-white uppercase tracking-tighter">Belum Selesai</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                            <span class="text-[9px] lg:text-[10px] font-bold text-gray-500 dark:text-white uppercase tracking-tighter">Selesai</span>
                        </div>
                    </div>
                </div>

                {{-- Leaderboard --}}
                {{-- PERUBAHAN: Container leaderboard kini mendukung Dark Mode --}}
                <div class="bg-white dark:bg-slate-900 p-4 lg:p-6 rounded-xl border border-gray-100 dark:border-slate-800 shadow-sm flex flex-col transition-colors">
                    <div class="mb-6 relative">
                        <h3 class="font-bold text-sm lg:text-base text-gray-800 dark:text-white transition-colors">Leaderboard</h3>
                        <p class="text-[11px] text-gray-400 dark:text-gray-200 italic">Partisipasi Unit Kerja</p>
                        <a href="/detail-leaderboard" class="absolute top-0 right-0 text-[10px] text-blue-600 dark:text-blue-400 font-bold underline hover:text-blue-800 transition-colors">Detail</a>
                    </div>

                    {{-- PERUBAHAN: Ukuran lingkaran grafik (w-28 h-28) sedikit lebih kecil untuk mendukung mobile --}}
                    <div class="flex justify-center mb-6">
                        <div class="w-28 lg:w-32 h-28 lg:h-32 rounded-full relative"
                            style="background: conic-gradient(#3b82f6 0% 35%, #10b981 35% 55%, #f43f5e 55% 70%, #f59e0b 70% 85%, #8b5cf6 85% 100%);">
                            {{-- PERUBAHAN: Center circle mendukung Dark Mode --}}
                            <div class="absolute inset-6 lg:inset-8 bg-white dark:bg-slate-900 rounded-full flex items-center justify-center shadow-inner transition-colors">
                                <span class="text-[10px] lg:text-xs font-bold text-gray-700 dark:text-white">100%</span>
                            </div>
                        </div>
                    </div>

                    {{-- PERUBAHAN: Penambahan class custom-scrollbar dan hover:text-blue-500 pada list unit --}}
                    <div class="space-y-2.5 flex-1 overflow-y-auto pr-1 custom-scrollbar">
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
                                <div class="w-1.5 h-1.5 lg:w-2 lg:h-2 {{ $unit['color'] }} rounded-sm"></div>
                                <span class="text-[10px] lg:text-[11px] font-medium text-gray-600 dark:text-white group-hover:text-blue-500 transition-colors">{{ $unit['label'] }}</span>
                            </div>
                            <span class="text-[10px] lg:text-[11px] font-bold text-gray-800 dark:text-white">{{ $unit['val'] }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Recent Activity --}}
            {{-- PERUBAHAN: Judul disingkat menjadi "Aktivitas Terkini" dan mendukung Dark Mode --}}
            <div class="bg-white dark:bg-slate-900 p-4 lg:p-6 rounded-xl border border-gray-100 dark:border-slate-800 shadow-sm transition-colors">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-bold text-sm lg:text-base text-gray-800 dark:text-white transition-colors">Aktivitas Terkini</h3>
                    <a href="#" class="text-[9px] lg:text-[10px] font-bold text-blue-600 dark:text-blue-400 uppercase tracking-widest hover:underline transition">Semua</a>
                </div>

                <div class="space-y-4">
                    <div class="flex gap-3 lg:gap-4">
                        {{-- PERUBAHAN: Background icon log (bg-blue-50) kini mendukung Dark Mode (dark:bg-blue-900/30) --}}
                        <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center flex-shrink-0 transition-colors">
                            <i class="fa-solid fa-plus text-blue-500 dark:text-blue-400 text-xs"></i>
                        </div>
                        <div class="flex-1">
                            {{-- PERUBAHAN: Penambahan class italic dan dark mode pada text log --}}
                            <p class="text-xs text-gray-700 dark:text-white leading-snug">
                                <span class="font-bold">Superadmin</span> menambahkan modul pelatihan <span class="font-semibold text-blue-600 dark:text-blue-400 italic cursor-pointer">"Prosedur IGD"</span>
                            </p>
                            {{-- PERUBAHAN: Penambahan class uppercase pada label waktu --}}
                            <p class="text-[9px] lg:text-[10px] text-gray-400 dark:text-gray-400 mt-1 font-medium italic transition-colors uppercase">10 menit yang lalu</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

{{-- PENAMBAHAN: Blok style baru untuk kustomisasi scrollbar dan x-cloak Alpine --}}
<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 10px; }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; }
    
    .custom-scrollbar-h::-webkit-scrollbar { height: 4px; }
    .custom-scrollbar-h::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 10px; }
    
    [x-cloak] { display: none !important; }
</style>
@endsection