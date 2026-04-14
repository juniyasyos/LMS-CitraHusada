@extends('components.layout')
@section('title', 'Tambah Peran')

@section('content')
<div class="flex h-screen overflow-hidden bg-slate-50">
    <aside id="sidebar" class="w-64 h-screen bg-white border-r flex-shrink-0 overflow-y-auto">
        @include('components.nav-superadmin', ['hideSideMenu' => true])
    </aside>

    <div class="flex-1 flex flex-col min-w-0">
        
        <header class="bg-white border-b h-16 flex items-center justify-between px-8 flex-shrink-0 z-10">
            <h1 class="text-sm font-semibold text-gray-800">Manajemen Pengguna</h1>
            <div class="flex items-center gap-4">
                <div class="relative cursor-pointer">
                    <span class="absolute top-0 right-0 h-2 w-2 bg-red-500 rounded-full border-2 border-white"></span>
                    <i class="fa-solid fa-bell text-gray-400"></i>
                </div>
                <div class="flex items-center gap-3 pl-4 border-l border-gray-100">
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

        <main class="flex-1 overflow-y-auto p-8">
            
            <nav class="mb-6 text-[14px] font-medium">
                <ol class="flex items-center gap-2 text-gray-500">
                    <li>
                        <a href="/manajemen-pengguna" class="hover:text-blue-600 transition-colors">
                            Manajemen Pengguna
                        </a>
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="text-gray-300"> > </span>
                        <span class="text-gray-800 font-semibold">Tambah Peran</span>
                    </li>
                </ol>
            </nav>

            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-8 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2">Label</label>
                        <input type="text" class="w-full bg-gray-100 border border-gray-200 rounded-lg h-12 px-4 focus:bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2">Nama</label>
                        <input type="text" class="w-full bg-gray-100 border border-gray-200 rounded-lg h-12 px-4 focus:bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2">Nama Penjaga</label>
                        <input type="text" class="w-full bg-gray-100 border border-gray-200 rounded-lg h-12 px-4 focus:bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 transition-all">
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <button type="button" class="w-9 h-5 bg-gray-200 rounded-full relative transition-colors focus:outline-none">
                        <div class="absolute left-1 top-1 bg-white w-3 h-3 rounded-full shadow-sm"></div>
                    </button>
                    <div>
                        <span class="block text-xs font-bold text-gray-700">Pilih Semua</span>
                        <p class="text-[10px] text-gray-400">Aktifkan semua izin yang tersedia untuk peran ini.</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden mb-8">
                <div class="flex border-b">
                    <button class="px-8 py-4 text-xs font-bold text-blue-600 border-b-2 border-blue-600">Sumber Daya</button>
                    <button class="px-8 py-4 text-xs font-medium text-gray-500 hover:text-gray-700">Halaman</button>
                    <button class="px-8 py-4 text-xs font-medium text-gray-500 hover:text-gray-700">Widget</button>
                </div>

                <div class="p-8 space-y-12">
                    
                    {{-- Loop Izin --}}
                    @php
                        $sections = [
                            ['title' => 'Audit & Log Aktivitas', 'path' => 'aaaa/bbbb/cccc'],
                            ['title' => 'Laporan harian', 'path' => 'aaaa/bbbb/cccc'],
                            ['title' => 'Laporan harian', 'path' => 'aaaa/bbbb/cccc']
                        ];
                    @endphp

                    @foreach ($sections as $section)
                    <div>
                        <div class="mb-4">
                            <h3 class="text-sm font-bold text-gray-800">{{ $section['title'] }}</h3>
                            <p class="text-xs text-gray-400 font-mono">{{ $section['path'] }}</p>
                        </div>
                        
                        <div class="border-t pt-4">
                            <button class="text-xs font-bold text-blue-600 mb-4 hover:underline uppercase">Pilih Semua</button>
                            <div class="grid grid-cols-2 md:grid-cols-5 gap-y-4">
                                @php
                                    $perms = ($loop->first) ? ['Lihat', 'Lihat Apa Saja'] : array_fill(0, 8, 'Lihat');
                                @endphp
                                @foreach ($perms as $index => $label)
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-0">
                                    <span class="text-xs text-gray-700 font-medium">
                                        {{ $index % 2 != 0 && !$loop->parent->first ? 'Lihat Apa Saja' : $label }}
                                    </span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endforeach

                </div>
            </div>

            <div class="flex justify-end gap-3 mb-10">
                <button class="px-6 py-2 text-xs font-bold text-gray-500">Batal</button>
                <button class="px-8 py-2 bg-blue-600 text-white rounded-lg text-xs font-bold hover:bg-blue-700 transition">Simpan</button>
            </div>
        </main>
    </div>
</div>
@endsection