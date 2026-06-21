<head>
    <script>
    (function () {
        const theme = localStorage.getItem('theme');

        if (
            theme === 'dark' ||
            (!theme && window.matchMedia('(prefers-color-scheme: dark)').matches)
        ) {
            document.documentElement.classList.add('dark');
        }
    })();
    </script>
</head>

<div class="flex h-screen overflow-hidden bg-slate-50 dark:bg-slate-950 transition-colors duration-300">

    {{-- Sidebar --}}
    <aside class="w-64 h-full bg-white dark:bg-slate-900 border-r border-gray-200 dark:border-slate-800 flex flex-col flex-shrink-0 transition-colors duration-300">

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
            @php
                // Definisi variabel helper
                $activeClass = 'bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400';
                $inactiveClass = 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-800';
            @endphp

            <a href="/pembelajaran" 
               class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 {{ request()->is('pembelajaran*') ? $activeClass : $inactiveClass }}">
                <i class="fa-solid fa-book text-sm"></i>
                Pembelajaran Saya
            </a>

            <a href="/sertifikat" 
                class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 {{ request()->is('sertifikat*') ? $activeClass : $inactiveClass }}">
                <i class="fa-solid fa-certificate text-sm"></i>
                Sertifikat
            </a>

            <div x-data="{ open: false }" class="relative pt-2">
                <button 
                    @click="open = !open"
                    :class="open ? 'bg-slate-50 dark:bg-slate-800' : ''"
                    class="w-full flex items-center gap-3 px-4 py-2.5 rounded-xl text-xs font-bold text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-800 transition"
                >
                    <i class="fa-solid fa-circle-user text-sm"></i>
                    Profil
                    <i class="fa-solid fa-chevron-down ml-auto text-[10px] transition-transform" :class="open ? 'rotate-180' : ''"></i>
                </button>

                <div 
                    x-show="open"
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    @click.away="open = false"
                    class="mt-2 bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-700 shadow-xl rounded-2xl p-5 text-sm w-full"
                    x-cloak
                >
                    <div class="flex flex-col items-center text-center mb-4">
                        <div class="w-16 h-16 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center border-2 border-white dark:border-slate-600 shadow-sm">
                            <i class="fa-solid fa-user text-slate-400 text-2xl"></i>
                        </div>

                        <p id="navProfileName"
                            class="font-bold mt-3 text-gray-800 dark:text-white">
                        </p>
                        
                        <p id="navProfileNIK"
                            class="text-[10px] text-gray-400 dark:text-gray-500">
                        </p>

                        <p id="navProfileJabatan"
                            class="mt-2 text-[10px] text-gray-500 dark:text-gray-400 uppercase font-bold tracking-tighter">
                        </p>

                        <div id="navProfileJPL" class="mt-3 inline-flex items-center gap-1.5 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 px-3 py-1.5 rounded-lg">
                            <i class="fa-solid fa-clock text-[10px]"></i>
                            <span class="text-[11px] font-bold">JPL: <span id="navProfileJPLValue">0</span></span>
                        </div>

                    </div>

                    <div class="border-t dark:border-slate-700 pt-3" x-data="{ 
                        isDark: document.documentElement.classList.contains('dark'),
                        toggle() {
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
                        <button @click="toggle()" class="w-full flex items-center justify-between gap-2 px-3 py-2 bg-slate-50 dark:bg-slate-900 rounded-lg border dark:border-slate-700 transition-colors">
                            <span class="text-[10px] font-bold text-gray-600 dark:text-gray-400" x-text="isDark ? 'Mode Gelap' : 'Mode Terang'"></span>
                            <i :class="isDark ? 'fa-solid fa-moon text-blue-400' : 'fa-solid fa-sun text-amber-500'"></i>
                        </button>
                    </div>
                </div>
            </div>
        </nav>

        <div class="p-4 border-t border-gray-100 dark:border-slate-800 space-y-1">
            {{-- PERBAIKAN: Mengubah $active menjadi $activeClass dan $inactive menjadi $inactiveClass --}}
            @if (auth()->user()->role_id == 1 && !session()->has('impersonate_by'))
            <a href="/beranda-superadmin"
            class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 {{ request()->is('beranda-superadmin*') ? $activeClass : $inactiveClass }}">
                <i class="fa-solid fa-user-gear text-sm"></i>
                Beralih Akun
            </a>

            @elseif (auth()->user()->role_id == 2 && !session()->has('impersonate_by'))
            <a href="/beranda-admin"
            class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 {{ request()->is('beranda-admin*') ? $activeClass : $inactiveClass }}">
                <i class="fa-solid fa-user-gear text-sm"></i>
                Beralih Akun
            </a>

            @elseif (auth()->user()->role_id == 3 && !session()->has('impersonate_by'))
            <a href="/beranda-admin"
            class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 {{ request()->is('beranda-admin*') ? $activeClass : $inactiveClass }}">
                <i class="fa-solid fa-user-gear text-sm"></i>
                Beralih Akun
            </a>
            @endif
            <a href="#"
            onclick="handleLogout(event)"
            class="flex items-center gap-3 px-4 py-3 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-xl text-xs font-bold transition-all duration-200">
                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                Keluar
            </a>
        </div>
        
    </aside>

    {{-- Konten Utama --}}
    <main class="flex-1 overflow-y-auto bg-slate-50 dark:bg-slate-950 transition-colors duration-300 custom-scrollbar">
        <div class="p-8">
            {{-- Isi konten halaman --}}
        </div>
    </main>

    <style>
        [x-cloak] { display: none !important; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; }
    </style>

</div>
<script>
document.addEventListener("DOMContentLoaded", loadUserProfile);

async function loadUserProfile(){
    try{
        const response = await axios.get('/api/profile');

        if(response.data.success){
            const user = response.data.data;

            const nama = user.nama;
            const unitKerja = user.unit_kerja?.unit_kerja ?? '-';
            // const jenisTenaga = user.jenis_tenaga?.jenis_tenaga ?? '-';
            const nik = user.nik ?? '-';

                document.getElementById("navProfileName").innerText = nama;
                document.getElementById("navProfileJabatan").innerText = "Unit " + unitKerja;
                document.getElementById("navProfileNIK").innerText = nik;
                document.getElementById("navProfileJPLValue").innerText = (user.total_jpl || 0) + (user.jpl_eksternal || 0);
        }
    }catch(error){
        console.error("Gagal load profile:", error);
    }
}
</script>

<script>
(function () {
    const theme = localStorage.getItem('theme');

    if (
        theme === 'dark' ||
        (!theme && window.matchMedia('(prefers-color-scheme: dark)').matches)
    ) {
        document.documentElement.classList.add('dark');
    }
})();
</script>