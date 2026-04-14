<div class="flex flex-col h-full">
    @php
        $active = 'bg-blue-600 text-white';
        $inactive = 'hover:bg-gray-100 text-gray-700';
    @endphp

    {{-- Logo + Title --}}
    <div class="p-6 border-b border-gray-100">
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/logo-lms.png') }}" alt="Logo" class="w-10 h-10">
            <div>
                <h1 class="text-red-600 font-bold text-base leading-tight">Citra Husada</h1>
                <p class="text-green-600 text-[10px] font-medium uppercase tracking-wider">Learning Management System</p>
            </div>
        </div>
    </div>
    
    <nav class="flex-1 p-4 space-y-1 overflow-y-auto custom-scrollbar">
        @if(!isset($hideSideMenu) || !$hideSideMenu)
            <a href="/beranda-superadmin"
                class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 {{ request()->is('beranda-superadmin') ? $active : $inactive }}">
                <i class="fa-brands fa-microsoft text-sm"></i>
                Beranda
            </a>

            <a href="/manajemen-pengguna"
                class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 {{ request()->is('manajemen-pengguna*') ? $active : $inactive }}">
                <i class="fa-solid fa-users text-sm"></i>
                Manajemen Pengguna
            </a>

            <a href="/manajemen-unit-kerja"
                class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 {{ request()->is('manajemen-unit-kerja*') ? $active : $inactive }}">
                <i class="fa-solid fa-building text-sm"></i>
                Manajemen Unit Kerja
            </a>

            <a href="/manajemen-pelatihan"
                class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 {{ request()->is('manajemen-pelatihan*') ? $active : $inactive }}">
                <i class="fa-solid fa-circle-play text-sm"></i>
                Manajemen Media Pelatihan
            </a>

            <a href="/manajemen-kategori"
                class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 {{ request()->is('manajemen-kategori*') ? $active : $inactive }}">
                <i class="fa-solid fa-tags text-sm"></i>
                Manajemen Kategori
            </a>

            <a href="/laporan-monitoring"
                class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 {{ request()->is('laporan-monitoring*') ? $active : $inactive }}">
                <i class="fa-solid fa-file-lines text-sm"></i>
                Laporan & Monitoring
            </a>

            <a href="/log-aktivitas"
                class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 {{ request()->is('log-aktivitas*') ? $active : $inactive }}">
                <i class="fa-solid fa-clock-rotate-left text-sm"></i>
                Log Aktivitas
            </a>
        @else
            <div class="px-4 py-2"></div>
        @endif
    </nav>

    <div class="p-4 border-t border-gray-100 space-y-1 flex-shrink-0 bg-white">
        <a href="/cadangan"
            class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 {{ request()->is('cadangan*') ? $active : $inactive }}">
            <i class="fa-solid fa-cloud-arrow-up text-sm"></i>
            Cadangan
        </a>

        <a href="/logout"
            class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-xs font-bold text-red-500 hover:bg-red-50 transition-all duration-200">
            <i class="fa-solid fa-arrow-right-from-bracket text-sm"></i>
            Keluar
        </a>
    </div>
</div>