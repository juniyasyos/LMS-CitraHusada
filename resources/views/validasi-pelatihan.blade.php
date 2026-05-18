@extends('components.layout')
@section('title', 'Verifikasi Pelatihan')

@section('content')
<div class="flex h-screen overflow-hidden bg-slate-50 dark:bg-slate-950 transition-colors duration-300" 
    x-data="{ 
        sidebarOpen: false, 
        openVerifikasi: false,
        darkMode: localStorage.getItem('theme') === 'dark' 
    }">
    
    {{-- Sidebar --}}
    <aside id="sidebar"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-slate-900 border-r dark:border-slate-800 transition-transform duration-300 lg:translate-x-0 lg:static lg:inset-0 flex-shrink-0 overflow-y-auto">
        @include('components.nav-superadmin', ['hideSideMenu' => true])
    </aside>

    {{-- Overlay Mobile --}}
    <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-black/50 backdrop-blur-sm lg:hidden" x-cloak></div>

    <div class="flex-1 flex flex-col min-w-0 transition-colors duration-300">
        {{-- Header --}}
        <header class="bg-white dark:bg-slate-900 border-b dark:border-slate-800 h-16 flex items-center justify-between px-4 lg:px-8 flex-shrink-0 z-10">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = true" class="lg:hidden p-2 text-gray-500 dark:text-white"><i class="fa-solid fa-bars text-lg"></i></button>
                <h1 class="text-sm font-semibold text-gray-600 dark:text-white">Laporan & Monitoring</h1>
            </div>
            <div class="flex items-center gap-4">
                @include('components.notif-superadmin')
                <div class="flex items-center gap-3 pl-4 border-l dark:border-slate-800">
                    <div class="text-right hidden sm:block">
                        <p class="text-xs font-bold text-gray-800 dark:text-white leading-tight">Admin</p>
                        <p class="text-[10px] text-gray-500 dark:text-gray-400 font-medium">Administrator</p>
                    </div>
                    <div class="w-8 h-8 bg-gray-200 dark:bg-slate-700 rounded-full overflow-hidden border dark:border-slate-800 flex items-center justify-center">
                        <i class="fa-solid fa-user text-gray-500 dark:text-white text-xs"></i>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-4 lg:p-8 custom-scrollbar">
            {{-- Breadcrumb --}}
            <nav class="mb-6 text-[14px] font-medium">
                <ol class="flex items-center gap-2 text-gray-500 dark:text-gray-400">
                    <li>
                        <a href="/admin/laporan-monitoring" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                            Laporan & Monitoring
                        </a>
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="text-gray-300 dark:text-gray-600"> > </span>
                        <span class="text-gray-800 dark:text-white font-semibold">Validasi Pelatihan</span>
                    </li>
                </ol>
            </nav>

            {{-- Menunggu Verifikasi Bar --}}
            <h1 class="font-bold text-gray-800 dark:text-white mb-6">Menunggu verifikasi</h1>
            <div class="bg-white dark:bg-slate-900 rounded-xl border border-gray-100 dark:border-slate-800 shadow-sm p-4 mb-6 transition-colors">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-gray-200 dark:bg-slate-800 rounded-lg flex items-center justify-center text-gray-400 font-bold text-xl uppercase">AH</div>
                        <div>
                            <h3 class="font-bold text-gray-800 dark:text-white">dr. Ahmad Subarjo</h3>
                            <p class="text-[11px] text-gray-500 dark:text-gray-400 italic font-medium tracking-wide uppercase">Unit Gawat Darurat</p>
                            <p class="text-[10px] text-gray-400 mt-1 transition-colors"><i class="fa-regular fa-clock mr-1"></i> 2 jam yang lalu</p>
                        </div>
                    </div>
                    <div class="text-center">
                        <span class="px-6 py-1 bg-slate-100 dark:bg-slate-800 text-gray-500 dark:text-gray-300 border dark:border-slate-700 rounded-full text-[10px] font-bold uppercase tracking-widest">Pending</span>
                        <p class="mt-2 text-xs font-bold text-gray-700 dark:text-white uppercase tracking-tighter">Skor: <span class="text-blue-600">92</span></p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                {{-- Kolom Kiri: Info Karyawan --}}
                <div class="lg:col-span-4 space-y-6">
                    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm overflow-hidden transition-colors">
                        <div class="p-8 flex flex-col items-center text-center">
                            <div class="w-20 h-20 bg-gray-100 dark:bg-slate-800 rounded-2xl flex items-center justify-center text-gray-400 font-bold text-2xl mb-4 uppercase">AH</div>
                            <h3 class="font-bold text-gray-800 dark:text-white">dr. Ahmad Subarjo</h3>
                            <p class="text-xs text-gray-400 transition-colors">NIK 123456789</p>
                        </div>
                        <div class="border-t dark:border-slate-800 grid grid-cols-2 divide-x dark:divide-slate-800 transition-colors">
                            <div class="p-4 text-center">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Unit Kerja</p>
                                <p class="text-xs font-bold text-gray-700 dark:text-white">Unit Gawat Darurat</p>
                            </div>
                            <div class="p-4 text-center">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Jenis Tenaga</p>
                                <p class="text-xs font-bold text-gray-700 dark:text-white transition-colors">Dokter Spesialis</p>
                            </div>
                        </div>
                        <div class="p-8 border-t dark:border-slate-800 text-center transition-colors">
                            <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-[0.2em] mb-2">Total JPL</p>
                            <p class="text-lg font-bold text-gray-800 dark:text-white">12 Jam</p>
                        </div>
                    </div>
                </div>

                {{-- Kolom Kanan: Detail Pelatihan --}}
                <div class="lg:col-span-8 bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm p-6 lg:p-10 transition-colors">
                    <div class="mb-8">
                        <span class="px-3 py-1 bg-slate-50 dark:bg-slate-800 text-gray-500 dark:text-gray-400 border dark:border-slate-700 rounded-md text-[10px] font-bold uppercase tracking-widest">Pelatihan Internal</span>
                        <h2 class="text-xl font-bold text-gray-800 dark:text-white mt-3 uppercase tracking-tight">Prosedur Resusitasi Jantung Paru (RJP) - Advance</h2>
                        <p class="text-[11px] text-gray-400 mt-1 italic transition-colors"><i class="fa-regular fa-calendar mr-1"></i> 15 Jan - 20 Jan 2026</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        {{-- Timeline Materi --}}
                        <div>
                            <div class="flex items-center gap-3 mb-6">
                                <i class="fa-regular fa-clock text-gray-400 transition-colors"></i>
                                <h4 class="text-xs font-bold text-gray-800 dark:text-white uppercase tracking-widest">Materi</h4>
                            </div>
                            <div class="relative space-y-6 pl-6 border-l-2 border-gray-100 dark:border-slate-800 ml-2 transition-colors">
                                @php $materis = [['Materi 1', '15 Jan 2026, 09:00'], ['Materi 2', '15 Jan 2026, 10:00'], ['Kuis 1', '15 Jan 2026, 11:00'], ['Materi 3', '16 Jan 2026, 12:00'], ['Materi 4', '16 Jan 2026, 13:00'], ['Kuis 2', '16 Jan 2026, 14:00']]; @endphp
                                @foreach($materis as $m)
                                <div class="relative">
                                    <div class="absolute -left-[31px] top-1 w-4 h-4 rounded-full bg-slate-300 border-4 border-white dark:border-slate-900 transition-colors"></div>
                                    <p class="text-xs font-bold text-gray-700 dark:text-white transition-colors">{{ $m[0] }}</p>
                                    <p class="text-[10px] text-gray-400 transition-colors">{{ $m[1] }}</p>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Hasil Penilaian & Sertifikat --}}
                        <div class="space-y-8">
                            <div>
                                <div class="flex items-center gap-3 mb-6">
                                    <i class="fa-regular fa-file-lines text-gray-400 transition-colors"></i>
                                    <h4 class="text-xs font-bold text-gray-800 dark:text-white uppercase tracking-widest">Hasil Penilaian</h4>
                                </div>
                                <div class="space-y-3">
                                    @php $kuis = [['Kuis 1', 75, 75], ['Kuis 2', 75, 80]]; @endphp
                                    @foreach($kuis as $k)
                                    <div class="bg-gray-50 dark:bg-slate-800/50 p-4 rounded-xl border border-gray-100 dark:border-slate-800 flex justify-between items-center transition-colors">
                                        <div>
                                            <p class="text-xs font-bold text-gray-700 dark:text-white transition-colors">{{ $k[0] }}</p>
                                            <p class="text-[9px] text-gray-400 uppercase transition-colors">Minimal: {{ $k[1] }}</p>
                                        </div>
                                        <p class="text-xl font-bold text-blue-600">{{ $k[2] }}</p>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            <div>
                                <div class="flex items-center gap-3 mb-6">
                                    <i class="fa-regular fa-image text-gray-400 transition-colors"></i>
                                    <h4 class="text-xs font-bold text-gray-800 dark:text-white uppercase tracking-widest transition-colors">Pratinjau Sertifikat</h4>
                                </div>
                                <div class="relative group cursor-pointer overflow-hidden rounded-xl border dark:border-slate-800 transition-colors">
                                    <img src="{{ asset('images/sertif-depan.png') }}" class="w-full h-auto transition-all duration-500">
                                    <div class="absolute inset-0 bg-black/20 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                        <i class="fa-solid fa-expand text-white text-xl"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-12 flex justify-end">
                        {{-- Tombol untuk membuka Modal --}}
                        <button @click="openVerifikasi = true" class="bg-blue-600 hover:bg-blue-700 text-white px-10 py-3 rounded-xl text-xs font-bold shadow-lg shadow-blue-200 dark:shadow-none transition-all active:scale-95 tracking-widest">
                            Simpan Verifikasi
                        </button>
                    </div>
                </div>
            </div>
        </main>
    </div>

    {{-- MODAL POP UP: VERIFIKASI KELULUSAN --}}
    <div x-show="openVerifikasi" 
        class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        x-cloak>
        
        <div @click.away="openVerifikasi = false" 
            class="bg-white dark:bg-slate-900 w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden transition-all duration-300"
            x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="scale-95"
            x-transition:enter-end="scale-100">
            
            {{-- Header Modal --}}
            <div class="flex items-center justify-between px-6 py-5 border-b dark:border-slate-800">
                <h3 class="text-sm font-bold text-gray-800 dark:text-white">Verifikasi Kelulusan</h3>
                <button @click="openVerifikasi = false" class="text-gray-400 hover:text-red-500 transition-colors">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            {{-- Body Modal --}}
            <div class="p-6 space-y-5">
                <p class="text-[11px] text-gray-500 dark:text-gray-400 leading-relaxed">
                    Tinjau seluruh kemajuan dan nilai sebelum memberikan keputusan verifikasi untuk penerbitan sertifikat.
                </p>

                {{-- Input Catatan --}}
                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Catatan Hasil Peninjauan</label>
                    <textarea 
                        rows="4" 
                        placeholder="Tuliskan catatan evaluasi di sini..." 
                        class="w-full bg-slate-50 dark:bg-slate-800 border border-gray-100 dark:border-slate-700 rounded-xl p-4 text-xs dark:text-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all resize-none"></textarea>
                </div>

                {{-- Status Keputusan --}}
                <div class="space-y-3">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Status Keputusan</label>
                    <div class="grid grid-cols-2 gap-3">
                        <button class="flex items-center justify-center gap-2 py-3 px-4 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg text-[10px] font-bold uppercase tracking-widest transition-all active:scale-95 shadow-lg shadow-emerald-100 dark:shadow-none">
                            <i class="fa-regular fa-circle-check"></i> Setuju
                        </button>
                        <button class="flex items-center justify-center gap-2 py-3 px-4 bg-red-500 hover:bg-red-600 text-white rounded-lg text-[10px] font-bold uppercase tracking-widest transition-all active:scale-95 shadow-lg shadow-red-100 dark:shadow-none">
                            <i class="fa-regular fa-circle-xmark"></i> Tolak
                        </button>
                    </div>
                </div>

                {{-- Info Box (Biru) --}}
                <div class="bg-blue-50/50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800 p-4 rounded-xl flex gap-3">
                    <i class="fa-solid fa-circle-info text-blue-500 mt-0.5"></i>
                    <p class="text-[10px] text-blue-600/80 dark:text-blue-400 leading-relaxed">
                        Menyetujui akan secara otomatis menerbitkan e-Sertifikat ke profil karyawan dan mencatat JPL ke database sistem.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 10px; }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; }
    [x-cloak] { display: none !important; }
</style>
@endsection