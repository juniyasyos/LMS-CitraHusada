<div class="flex flex-col h-full bg-white dark:bg-slate-900 transition-colors duration-300">
    @php
        // Penentuan Role (Silakan sesuaikan dengan sistem Auth Anda, misal: Auth::user()->role)
        $role = $role ?? 'superadmin'; 
        
        $active = 'bg-blue-600 text-white shadow-lg shadow-blue-200 dark:shadow-none';
        $inactive = 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-800';
    @endphp

    {{-- Logo + Title --}}
    <div class="p-6 border-b border-gray-100 dark:border-slate-800">
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/logo-lms.png') }}" alt="Logo" class="w-10 h-10">
            <div>
                <h1 class="text-red-600 font-bold text-base leading-tight">Citra Husada</h1>
                <p class="text-green-600 dark:text-green-400 text-[10px] font-medium uppercase tracking-wider">Learning Management System</p>
            </div>
        </div>
    </div>
    
    <nav class="flex-1 p-4 space-y-1 overflow-y-auto custom-scrollbar">
        @if(!isset($hideSideMenu) || !$hideSideMenu)
            
            {{-- MENU: BERANDA (Tampil di Semua Role) --}}
            {{-- PERBAIKAN: Link diarahkan ke rute masing-masing sesuai role aktif --}}
            <a href="{{ $role == 'admin' ? '/admin/beranda' : ($role == 'teacher' ? '/teacher/beranda' : '/beranda-superadmin') }}"
                class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 {{ request()->is('*/beranda') || request()->is('beranda-superadmin') ? $active : $inactive }}">
                <i class="fa-brands fa-microsoft text-sm"></i>
                Beranda
            </a>

            {{-- MENU: KHUSUS SUPERADMIN --}}
            @if($role == 'superadmin')
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
            @endif

            {{-- MENU: MANAJEMEN MEDIA (Superadmin & Teacher) --}}
            @if($role == 'superadmin' || $role == 'teacher')
                <a href="{{ $role == 'teacher' ? '/teacher/manajemen-pelatihan' : '/manajemen-pelatihan' }}"
                    class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 {{ request()->is('*manajemen-pelatihan') ? $active : $inactive }}">
                    <i class="fa-solid fa-file-lines text-sm"></i>
                    Manajemen Media
                </a>
            @endif

            {{-- MENU: KHUSUS SUPERADMIN --}}
            @if($role == 'superadmin')
                <a href="/manajemen-kategori"
                    class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 {{ request()->is('manajemen-kategori*') ? $active : $inactive }}">
                    <i class="fa-solid fa-tags text-sm"></i>
                    Manajemen Kategori
                </a>
            @endif

            {{-- MENU: LAPORAN & MONITORING (Superadmin & Admin) --}}
            {{-- PERBAIKAN: Menghapus kurung tutup berlebih dan memperbaiki deteksi rute aktif --}}
            @if($role == 'superadmin' || $role == 'admin')
                <a href="{{ $role == 'admin' ? '/admin/laporan-monitoring' : '/laporan-monitoring' }}"
                    class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 {{ request()->is('*laporan-monitoring') ? $active : $inactive }}">
                    <i class="fa-solid fa-file-lines text-sm"></i>
                    Laporan & Monitoring
                </a>
            @endif

            {{-- MENU: KHUSUS SUPERADMIN --}}
            @if($role == 'superadmin')
                <a href="/log-aktivitas"
                    class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 {{ request()->is('log-aktivitas*') ? $active : $inactive }}">
                    <i class="fa-solid fa-clock-rotate-left text-sm"></i>
                    Log Aktivitas
                </a>
            @endif

        @endif
    </nav>

    {{-- FOOTER SIDEBAR --}}
    <div class="p-4 border-t border-gray-100 dark:border-slate-800 space-y-1 shrink-0 bg-white dark:bg-slate-900 transition-colors duration-300">
        
        {{-- DARK MODE TOGGLE (Semua Role) --}}
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
                    <i :class="isDark ? 'fa-solid fa-moon text-blue-400' : 'fa-solid fa-sun text-amber-500'" class="text-sm"></i>
                    <span x-text="isDark ? 'Mode Gelap' : 'Mode Terang'"></span>
                </div>
                <div class="w-8 h-4 bg-gray-300 dark:bg-blue-600 rounded-full p-1 relative transition-colors duration-300">
                    <div class="bg-white w-2 h-2 rounded-full shadow-sm transition-transform duration-300 transform"
                        :class="isDark ? 'translate-x-4' : 'translate-x-0'"></div>
                </div>
            </button>
        </div>

        {{-- MENU: BERALIH AKUN (Semua Role) --}}
        <a href="/beralih-akun"
            class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 {{ request()->is('beralih-akun*') ? $active : $inactive }}">
            <i class="fa-solid fa-user-gear text-sm"></i>
            Beralih Akun
        </a>

        {{-- MENU: CADANGAN (Khusus Superadmin) --}}
        {{-- Tambahkan pengecekan !isset($hideSideMenu) || !$hideSideMenu --}}
        @if(isset($role) && $role === 'superadmin')
            @if(!isset($hideSideMenu) || !$hideSideMenu)
                <a href="/cadangan"
                    class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 {{ request()->is('cadangan*') ? $active : $inactive }}">
                    <i class="fa-solid fa-cloud-arrow-up text-sm"></i>
                    Cadangan
                </a>
            @endif
        @endif

        {{-- KELUAR (Semua Role) --}}
        <a href="/logout"
            class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-xs font-bold text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-all duration-200">
            <i class="fa-solid fa-arrow-right-from-bracket text-sm"></i>
            Keluar
        </a>
    </div>
</div>