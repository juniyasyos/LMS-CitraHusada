@extends('components.layout')
@section('title', 'Cadangan')
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
            <h1 class="text-sm font-semibold text-gray-600">Cadangan</h1>
            <div class="flex items-center gap-4">
                <div class="relative">
                    <span class="absolute top-0 right-0 h-2 w-2 bg-red-500 rounded-full border-2 border-white"></span>
                    <i class="fa-solid fa-bell text-gray-500"></i>
                </div>
                <div class="flex items-center gap-3">
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
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h2 class="text-lg font-bold text-gray-800">Cadangan</h2>
                    <p class="text-sm text-gray-500">Lakukan pencadangan secara berkala.</p>
                </div>
                <div class="flex gap-3">
                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg flex items-center gap-2 text-sm font-bold transition shadow-sm">
                        <i class="fa-solid fa-plus text-xs"></i>
                        Create Backup
                    </button>
                    <button class="bg-white hover:bg-gray-50 text-gray-600 border border-gray-200 px-5 py-2 rounded-lg flex items-center gap-2 text-sm font-bold transition shadow-sm">
                        <i class="fa-solid fa-gear text-xs"></i>
                        Backup Settings
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm">
                    <div class="flex items-center gap-3 text-gray-400 mb-2">
                        <i class="fa-solid fa-clock text-sm"></i>
                        <span class="text-[11px] font-bold uppercase tracking-wider">Tugas Aktif</span>
                    </div>
                    <p class="text-xl font-bold text-gray-800">0</p>
                </div>
                <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm">
                    <div class="flex items-center gap-3 text-gray-400 mb-2">
                        <i class="fa-solid fa-circle-check text-sm"></i>
                        <span class="text-[11px] font-bold uppercase tracking-wider">Berhasil</span>
                    </div>
                    <p class="text-xl font-bold text-gray-800">0</p>
                </div>
                <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm">
                    <div class="flex items-center gap-3 text-gray-400 mb-2">
                        <i class="fa-solid fa-circle-xmark text-sm"></i>
                        <span class="text-[11px] font-bold uppercase tracking-wider">Gagal</span>
                    </div>
                    <p class="text-xl font-bold text-gray-800">0</p>
                </div>
                <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm">
                    <div class="flex items-center gap-3 text-gray-400 mb-2">
                        <i class="fa-solid fa-database text-sm"></i>
                        <span class="text-[11px] font-bold uppercase tracking-wider">Penyimpanan</span>
                    </div>
                    <p class="text-sm font-bold text-gray-800">Local</p>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 min-h-[400px] flex flex-col">
                <div class="p-6 border-b flex flex-col md:flex-row justify-between items-center gap-4">
                    <h3 class="font-bold text-gray-800">Tugas Cadangan</h3>
                    <div class="relative w-64">
                        <input type="text" placeholder="Cari..." class="w-full pl-3 pr-8 py-2 border-gray-200 rounded-lg text-xs bg-gray-50 focus:ring-blue-500">
                        <i class="fa-solid fa-magnifying-glass absolute right-3 top-2.5 text-gray-400 text-xs"></i>
                    </div>
                </div>

                <div class="p-6 bg-gray-50/50 flex flex-col md:flex-row justify-between items-center border-b mb-12">
                    <p class="text-xs text-gray-500 italic">Pantau dan kelola tugas cadangan Anda dengan secara real-time.</p>
                    <div class="flex gap-2">
                        <button class="flex items-center gap-2 px-4 py-2 border-2 border-red-400 text-red-500 bg-red-50 rounded-lg text-[11px] font-bold hover:bg-red-100 transition">
                            <i class="fa-solid fa-rotate-left"></i> Reset Semua
                        </button>
                        <button class="flex items-center gap-2 px-4 py-2 border-2 border-amber-300 text-amber-600 bg-amber-50 rounded-lg text-[11px] font-bold hover:bg-amber-100 transition">
                            <i class="fa-solid fa-trash-can"></i> Pembersihan
                        </button>
                        <button class="flex items-center gap-2 px-4 py-2 border-2 border-emerald-400 text-emerald-600 bg-emerald-50 rounded-lg text-[11px] font-bold hover:bg-emerald-100 transition">
                            <i class="fa-solid fa-arrows-rotate"></i> Segarkan
                        </button>
                    </div>
                </div>

                <div class="flex-1 flex flex-col items-center justify-center p-12">
                    <div class="text-gray-300 mb-4">
                        <i class="fa-solid fa-xmark text-7xl font-light"></i>
                    </div>
                    <p class="text-xs text-gray-400 font-medium tracking-wide">Tidak ada data yang ditemukan</p>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection