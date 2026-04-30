<div class="flex flex-col h-full bg-white dark:bg-slate-900 transition-colors duration-300 overflow-x-hidden">

    @php
        // PERUBAHAN: Penyesuaian warna aktif/tidak aktif untuk mendukung dark mode dan penambahan shadow
        $active = 'bg-blue-600 text-white shadow-lg shadow-blue-200 dark:shadow-none';
        $inactive = 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-800';
    @endphp

    {{-- Logo + Title --}}
    {{-- PERUBAHAN: Penambahan class dark:border-slate-800 pada border-b --}}
    <div class="p-6 border-b border-gray-100 dark:border-slate-800">
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/logo-lms.png') }}" alt="Logo" class="w-10 h-10">
            <div>
                <h1 class="text-red-600 font-bold text-base leading-tight">Citra Husada</h1>
                <p class="text-green-600 dark:text-green-400 text-[10px] font-medium uppercase tracking-wider">
                    Learning Management System</p>
            </div>
        </div>
    </div>

    <nav class="flex-1 p-4 space-y-1 overflow-y-auto custom-scrollbar">

        <!-- BERANDA -->
        <a href="/beranda-superadmin" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-xs font-bold transition-all duration-200
        {{ request()->is('SuperAdmin_Views.beranda-superadmin') ? $active : $inactive }}">
            <i class="fa-brands fa-microsoft text-sm"></i>
            Beranda
        </a>

        <!-- MANAJEMEN PENGGUNA -->
        <a href="/manajemen-pengguna" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-xs font-bold transition-all duration-200
        {{ request()->is('SuperAdmin_Views.manajemen-pengguna') ? $active : $inactive }}">
            <i class="fa-solid fa-users text-sm"></i>
            Manajemen Pengguna
        </a>

        <!-- UNIT KERJA -->
        <a href="/manajemen-unit-kerja" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-xs font-bold transition-all duration-200
        {{ request()->is('SuperAdmin_Views.manajemen-unit-kerja') ? $active : $inactive }}">
            <i class="fa-solid fa-building text-sm"></i>
            Manajemen Unit Kerja
        </a>

        <!-- MANAJEMEN PELATIHAN -->
        <a href="/manajemen-pelatihan" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-xs font-bold transition-all duration-200
        {{ request()->is('SuperAdmin_Views.manajemen-pelatihan') ? $active : $inactive }}">
            <i class="fa-solid fa-circle-play text-sm"></i>
            Manajemen Media
        </a>

        <!-- KATEGORI -->
        <a href="/manajemen-kategori" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-xs font-bold transition-all duration-200
        {{ request()->is('SuperAdmin_Views.manajemen-kategori') ? $active : $inactive }}">
            <i class="fa-solid fa-tags text-sm"></i>
            Manajemen Kategori
        </a>

        <!-- LAPORAN -->
        <a href="/laporan-monitoring" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-xs font-bold transition-all duration-200
        {{ request()->is('SuperAdmin_Views.laporan-monitoring') ? $active : $inactive }}">
            <i class="fa-solid fa-file-lines text-sm"></i>
            Laporan & Monitoring
        </a>

        <!-- LOG -->
        <a href="/log-aktivitas" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-xs font-bold transition-all duration-200
        {{ request()->is('SuperAdmin_Views.log-aktivitas') ? $active : $inactive }}">
            <i class="fa-solid fa-clock-rotate-left text-sm"></i>
            Log Aktivitas
        </a>

    </nav>

    <!-- FOOTER SIDEBAR -->
    <div
        class="p-4 border-t border-gray-100 dark:border-slate-800 space-y-1 bg-white dark:bg-slate-900 transition-colors duration-300 mt-auto">

        {{-- PENAMBAHAN: Blok kode TOMBOL DARK MODE --}}
        <div x-data="{ 
            isDark: document.documentElement.classList.contains('dark'),
            toggleTheme() {
                this.isDark = !this.isDark;
                if (this.isDark) {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                } else {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                }
            }
        }">
            <button @click="toggleTheme()"
                class="w-full flex items-center justify-between gap-3 px-4 py-2.5 rounded-xl text-xs font-bold text-gray-700 dark:text-white hover:bg-gray-100 dark:hover:bg-slate-800 transition-all duration-200 border border-transparent dark:border-slate-800">
                <div class="flex items-center gap-3">
                    <i :class="isDark ? 'fa-solid fa-moon text-blue-400' : 'fa-solid fa-sun text-amber-500'"
                        class="text-sm"></i>
                    <span x-text="isDark ? 'Mode Gelap' : 'Mode Terang'"></span>
                </div>

                <div
                    class="w-8 h-4 bg-gray-300 dark:bg-blue-600 rounded-full p-1 relative transition-colors duration-300">
                    <div class="bg-white w-2 h-2 rounded-full shadow-sm transition-transform duration-300 transform"
                        :class="isDark ? 'translate-x-4' : 'translate-x-0'"></div>
                </div>
            </button>
        </div>

        <a href="/cadangan" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-xs font-bold transition-all duration-200
        {{ request()->is('SuperAdmin_Views.cadangan') ? $active : $inactive }}">
            <i class="fa-solid fa-cloud-arrow-up text-sm"></i>
            Cadangan
        </a>

        <a href="#" onclick="window.handleLogout(event)"
            class="w-full flex items-center gap-3 px-4 py-2.5 rounded-xl text-xs font-bold text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-all duration-200">
            <i class="fa-solid fa-arrow-right-from-bracket text-sm"></i>
            Keluar
        </a>
    </div>
</div>