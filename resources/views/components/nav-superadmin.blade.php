<div class="flex">

    <!-- SIDEBAR -->
    <aside class="fixed top-0 left-0 w-64 min-h-screen max-h-screen bg-white overflow-y-auto z-50">

        @php
            $active = 'bg-blue-500 text-white';
            $inactive = 'hover:bg-gray-100 text-gray-700';
        @endphp

        {{-- Logo + Title --}}
        <div class="p-1 border-b border-gray-200">
            <div class="flex items-center gap-1 mb-6 mt-6">
                <img src="{{ asset('images/logo-lms.png') }}" alt="Logo" class="w-12 h-12">
                <div>
                    <h1 class="text-red-600 font-bold text-lg">Citra Husada</h1>
                    <p class="text-green-600 text-sm">Learning Management System</p>
                </div>
            </div>
        </div>
        
        <nav class="p-4 space-y-2">

            <!-- BERANDA -->
            <a href="/beranda-superadmin"
            class="flex items-center gap-2 px-4 py-2 rounded-lg transition duration-200
            {{ request()->is('beranda-superadmin') ? $active : $inactive }}">
                <i class="fa-brands fa-microsoft {{ request()->is('beranda-superadmin') ? 'text-white' : '' }}"></i>
                Beranda
            </a>

            <!-- MANAJEMEN PENGGUNA -->
            <a href="/manajemen-pengguna"
            class="flex items-center gap-2 px-4 py-2 rounded-lg transition duration-200
            {{ request()->is('manajemen-pengguna') ? $active : $inactive }}">
                <i class="fa-solid fa-users {{ request()->is('manajemen-pengguna') ? 'text-white' : '' }}"></i>
                Manajemen Pengguna
            </a>

            <!-- UNIT KERJA -->
            <a href="/manajemen-unit-kerja"
            class="flex items-center gap-2 px-4 py-2 rounded-lg transition duration-200
            {{ request()->is('manajemen-unit-kerja') ? $active : $inactive }}">
                <i class="fa-solid fa-building {{ request()->is('manajemen-unit-kerja') ? 'text-white' : '' }}"></i>
                Manajemen Unit Kerja
            </a>

            <!-- MEDIA -->
            <a href="#"
            class="flex items-center gap-2 px-4 py-2 rounded-lg transition duration-200
            {{ request()->is('manajemen-media') ? $active : $inactive }}">
                <i class="fa-solid fa-circle-play {{ request()->is('manajemen-media') ? 'text-white' : '' }}"></i>
                Manajemen Media Pelatihan
            </a>

            <!-- KATEGORI -->
            <a href="/manajemen-kategori"
            class="flex items-center gap-2 px-4 py-2 rounded-lg transition duration-200
            {{ request()->is('manajemen-kategori') ? $active : $inactive }}">
                <i class="fa-solid fa-tags {{ request()->is('manajemen-kategori') ? 'text-white' : '' }}"></i>
                Manajemen Kategori
            </a>

            <!-- LAPORAN -->
            <a href="/laporan-monitoring"
            class="flex items-center gap-2 px-4 py-2 rounded-lg transition duration-200
            {{ request()->is('laporan-monitoring') ? $active : $inactive }}">
                <i class="fa-solid fa-file {{ request()->is('laporan-monitoring') ? 'text-white' : '' }}"></i>
                Laporan Monitoring
            </a>

            <!-- LOG -->
            <a href="/log-aktivitas"
            class="flex items-center gap-2 px-4 py-2 rounded-lg transition duration-200
            {{ request()->is('log-aktivitas') ? $active : $inactive }}">
                <i class="fa-solid fa-clock-rotate-left {{ request()->is('log-aktivitas') ? 'text-white' : '' }}"></i>
                Log Aktivitas
            </a>

        </nav>

        <!-- FOOTER SIDEBAR -->
        <div class="p-4 border-t border-gray-200 mt-auto">
            <a href="/cadangan"
            class="flex items-center gap-2 px-4 py-2 rounded-lg transition duration-200
            {{ request()->is('cadangan') ? $active : $inactive }}">
                <i class="fa-solid fa-cloud-arrow-up {{ request()->is('cadangan') ? 'text-white' : '' }}"></i>
                Cadangan
            </a>

            <a href="/"
            class="flex items-center gap-2 px-4 py-2 text-red-600 hover:text-red-800 transition duration-200">
                <i class="fa-solid fa-arrow-left"></i>
                Keluar
            </a>
        </div>
        
    </aside>

    <!-- MAIN CONTENT -->
    <main class="ml-64 w-full p-6">
        <!-- isi halaman -->
    </main>

</div>