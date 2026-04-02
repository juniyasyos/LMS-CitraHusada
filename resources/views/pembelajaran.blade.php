@extends('components.layout')
@section('title', 'pembelajaran')
@section('content')

<div class="flex min-h-screen bg-gray-100">

    <!-- SIDEBAR -->
    <aside id="sidebar"
        class="fixed lg:static z-40 top-0 left-0 w-64 min-h-screen bg-white border-r
        transform -translate-x-full lg:translate-x-0
        transition-transform duration-200">

        @include('components.nav')

    </aside>

    <!-- OVERLAY MOBILE -->
    <div id="overlay"
        class="fixed inset-0 bg-black/40 hidden z-30 lg:hidden">
    </div>

    <!-- MAIN CONTENT -->
    <main class="flex-1 p-4 lg:p-8">

        <!-- HEADER -->
        <div class="flex justify-between items-center mb-6">

            <!-- LEFT -->
            <div class="flex items-center gap-3">

                <!-- HAMBURGER -->
                <button id="toggleSidebar"
                    class="lg:hidden text-gray-600 text-xl">
                    <i class="fas fa-bars"></i>
                </button>

                <!-- DESKTOP TEXT -->
                <div class="hidden lg:block">
                    <h2 class="text-2xl font-semibold">
                        Selamat Datang Kembali, Pak Agung
                    </h2>
                    <p class="text-sm text-gray-500">
                        TIK Unit • Kepala Bagian
                    </p>
                </div>

                <!-- MOBILE LOGO -->
                <div class="flex items-center gap-2 lg:hidden">
                    <img src="{{ asset('images/logo-lms.png') }}" alt="Logo" class="w-10 h-10">
                    <div class="leading-tight">
                        <p class="text-red-600 font-bold text-lg">
                            Citra Husada
                        </p>
                        <p class="text-green-600 text-sm font-semibold">
                            Learning Management System
                        </p>
                    </div>
                </div>

            </div>

            <!-- RIGHT -->
            <div class="flex items-center gap-4">

                @include('components.notif')

                <!-- USER INFO DESKTOP -->
                <div class="text-right hidden lg:block">
                    <p class="font-medium">Agung Sunaryo</p>
                    <p class="text-sm text-gray-500">TIK Unit</p>
                </div>

            </div>

        </div>

        <!-- MOBILE WELCOME TEXT -->
        <div class="lg:hidden mb-6">
            <h2 class="text-lg font-semibold">
                Selamat Datang Kembali, Pak Agung
            </h2>
            <p class="text-sm text-gray-500">
                TIK Unit • Kepala Bagian
            </p>
        </div>

        <!-- SEARCH -->
        <div class="mb-6">
            <div class="relative w-full max-w-md">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input 
                    type="text"
                    placeholder="Cari modul..."
                    class="w-full pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                >
            </div>
        </div>

        <!-- FILTER STATUS -->
        <div class="grid grid-cols-3 gap-3 mb-8">

            <a href="/belum-mulai" 
            class="flex items-center justify-between px-4 py-3 bg-white border rounded-xl shadow-sm text-xs sm:text-sm font-medium hover:bg-gray-50 transition">
                <span>Belum Mulai</span>
                <i class="fa-solid fa-exclamation-circle text-gray-400"></i>
            </a>

            <a href="/materi-progress"
            class="flex items-center justify-between px-4 py-3 bg-white border rounded-xl shadow-sm text-xs sm:text-sm font-medium hover:bg-gray-50 transition">
                <span>Sedang Berjalan</span>
                <i class="fa-solid fa-clock text-gray-400"></i>
            </a>

            <a href="/materi-selesai"
            class="flex items-center justify-between px-4 py-3 bg-white border rounded-xl shadow-sm text-xs sm:text-sm font-medium hover:bg-gray-50 transition">
                <span>Selesai</span>
                <i class="fa-solid fa-check-circle text-gray-400"></i>
            </a>

        </div>

        <!-- CARD GRID -->
        <div class="grid md:grid-cols-3 gap-6">

            <!-- CARD 1 -->
            <div class="bg-white rounded-2xl shadow-md overflow-hidden">

                <div class="h-40 bg-gray-300 relative">
                    <span class="absolute top-3 right-3 bg-gray-800 text-white text-xs px-3 py-1 rounded-full">
                        Progres
                    </span>
                </div>

                <div class="p-5">

                    <h3 class="font-semibold text-lg">Judul</h3>
                    <p class="text-sm text-gray-500 mb-4">Sub Judul</p>

                    <div class="flex justify-between items-center text-sm mb-2">
                        <div class="flex items-center gap-1">
                            <i class="fa-solid fa-clock text-gray-400"></i>
                            <p>3 JPL</p>
                        </div>
                        <span class="text-red-500">
                            Due: Oct 15, 2024
                        </span>
                    </div>

                    <div class="w-full bg-gray-200 rounded-full h-2 mb-3">
                        <div class="bg-blue-600 h-2 rounded-full w-2/3"></div>
                    </div>

                    <span class="text-xs bg-blue-100 text-blue-600 px-3 py-1 rounded-full">
                        Dalam Progress
                    </span>

                    <div class="flex gap-3 mt-5">
                        <a href="/lanjutkan-materi"
                        class="flex-1 bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 flex items-center justify-center gap-2">
                            <i class="fas fa-caret-right"></i>
                            Lanjutkan
                        </a>

                        <a href="/detail-materi" 
                        class="flex-1 border py-2 rounded-lg hover:bg-gray-100 flex items-center justify-center gap-2">
                            <i class="fas fa-eye"></i>
                            Lihat Detail
                        </a>
                    </div>

                </div>
            </div>

            <!-- CARD 2 -->
            <div class="bg-white rounded-2xl shadow-md overflow-hidden">

                <div class="h-40 bg-gray-300 relative">
                    <span class="absolute top-3 right-3 bg-green-500 text-white text-xs px-3 py-1 rounded-full">
                        Selesai
                    </span>
                </div>

                <div class="p-5">

                    <h3 class="font-semibold text-lg">Judul</h3>
                    <p class="text-sm text-gray-500 mb-4">Sub Judul</p>

                    <div class="flex justify-between items-center text-sm mb-2">
                        <div class="flex items-center gap-1">
                            <i class="fa-solid fa-clock text-gray-400"></i>
                            <p>3 JPL</p>
                        </div>
                        <span class="text-red-500">
                            Due: Oct 15, 2024
                        </span>
                    </div>

                    <div class="w-full bg-gray-200 rounded-full h-2 mb-3">
                        <div class="bg-blue-500 h-2 rounded-full w-full"></div>
                    </div>

                    <span class="text-xs bg-blue-100 text-blue-600 px-3 py-1 rounded-full">
                        Selesai
                    </span>

                    <div class="flex gap-3 mt-5">
                        <button class="flex-1 bg-blue-600 text-white py-2 rounded-lg">
                            <i class="fas fa-caret-right"></i>
                            Lanjutkan
                        </button>

                        <button class="flex-1 border py-2 rounded-lg">
                            <i class="fas fa-eye"></i>
                            Lihat Detail
                        </button>
                    </div>

                </div>
            </div>

            <!-- CARD 3 -->
            <div class="bg-white rounded-2xl shadow-md overflow-hidden">

                <div class="h-40 bg-gray-300 relative">
                    <span class="absolute top-3 right-3 bg-red-500 text-white text-xs px-3 py-1 rounded-full">
                        Belum Dimulai
                    </span>
                </div>

                <div class="p-5">

                    <h3 class="font-semibold text-lg">Judul</h3>
                    <p class="text-sm text-gray-500 mb-4">Sub Judul</p>

                    <div class="flex justify-between items-center text-sm mb-2">
                        <div class="flex items-center gap-1">
                            <i class="fa-solid fa-clock text-gray-400"></i>
                            <p>3 JPL</p>
                        </div>
                        <span class="text-red-500">
                            Due: Oct 15, 2024
                        </span>
                    </div>

                    <div class="w-full bg-gray-200 rounded-full h-2 mb-3">
                        <div class="bg-blue-500 h-2 rounded-full w-0"></div>
                    </div>

                    <span class="text-xs bg-blue-100 text-blue-600 px-3 py-1 rounded-full">
                        Belum Dimulai
                    </span>

                    <div class="flex gap-3 mt-5">
                        <button class="flex-1 bg-blue-600 text-white py-2 rounded-lg">
                            <i class="fas fa-caret-right"></i>
                            Lanjutkan
                        </button>

                        <button class="flex-1 border py-2 rounded-lg">
                            <i class="fas fa-eye"></i>
                            Lihat Detail
                        </button>
                    </div>

                </div>
            </div>

        </div>

        <!-- LOAD MORE -->
        <div class="text-center mt-10">
            <button class="text-blue-600 hover:underline">
                Lihat Lebih Banyak →
            </button>
        </div>

    </main>

</div>

@endsection