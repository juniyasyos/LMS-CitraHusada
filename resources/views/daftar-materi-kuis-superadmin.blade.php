@extends('components.layout')
@section('title', 'Daftar Kuis dan Materi')

@section('content')
<div class="flex h-screen overflow-hidden bg-slate-50 dark:bg-slate-950 transition-colors duration-300" 
    x-data="{ 
        openEditFolder: false, 
        openTambahKuis: false, 
        openTambahMateri: false,
        darkMode: localStorage.getItem('theme') === 'dark'
    }">
    
    <aside id="sidebar" class="w-64 h-screen bg-white dark:bg-slate-900 border-r dark:border-slate-800 flex-shrink-0 overflow-y-auto transition-colors duration-300">
        @include('components.nav-superadmin', ['hideSideMenu' => true])
    </aside>

    <div class="flex-1 flex flex-col min-w-0 bg-white dark:bg-slate-900 transition-colors duration-300">
        
        {{-- Header --}}
        <header class="bg-white dark:bg-slate-900 border-b dark:border-slate-800 h-16 flex items-center justify-between px-8 flex-shrink-0 z-10 transition-colors duration-300">
            <h1 class="text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-[0.1em]">Manajemen Media Pelatihan</h1>
            <div class="flex items-center gap-4">
                <div class="relative cursor-pointer hover:opacity-70 transition">
                    <span class="absolute top-0 right-0 h-2 w-2 bg-red-500 rounded-full border-2 border-white dark:border-slate-900"></span>
                    <i class="fa-solid fa-bell text-gray-400 dark:text-gray-300 text-xs"></i>
                </div>
                <div class="flex items-center gap-3 pl-4 border-l border-gray-100 dark:border-slate-800">
                    <div class="text-right">
                        <p class="text-xs font-bold text-gray-800 dark:text-white leading-tight">Superadmin</p>
                        <p class="text-[10px] text-gray-500 dark:text-gray-400 font-medium">Administrator Utama</p>
                    </div>
                    <div class="w-8 h-8 bg-gray-200 dark:bg-slate-700 rounded-full overflow-hidden border border-gray-100 dark:border-slate-800 shadow-sm">
                        <img src="https://ui-avatars.com/api/?name=Super+Admin" alt="Profile">
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-10 custom-scrollbar">
            
            <div class="mb-10">
                <nav class="mb-6 text-[14px] font-medium">
                    <ol class="flex items-center gap-2 text-gray-500 dark:text-gray-400">
                        <li>
                            <a href="/manajemen-pelatihan" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                Manajemen Media Pelatihan
                            </a>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-gray-300 dark:text-gray-600"> > </span>
                            <span class="text-gray-800 dark:text-white font-semibold">Daftar Materi & Kuis</span>
                        </li>
                    </ol>
                </nav>
                <p class="text-xs text-gray-400 dark:text-gray-300 font-medium">Berikut merupakan daftar kuis dan materi pelatihan yang tersedia dalam kategori ini.</p>
            </div>

            <div class="space-y-6 mb-10">
                <div class="flex justify-end">
                    <button @click="openEditFolder = true" class="flex items-center gap-2 px-4 py-1.5 border border-gray-200 dark:border-slate-700 rounded-lg text-[11px] font-bold text-gray-500 dark:text-white hover:bg-gray-50 dark:hover:bg-slate-800 transition shadow-sm active:scale-95">
                        <i class="fa-solid fa-pen text-[9px]"></i> Edit
                    </button>
                </div>
                <div class="flex justify-end gap-3">
                    <button @click="openTambahKuis = true" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg flex items-center gap-2 text-xs font-bold transition shadow-lg shadow-blue-100 dark:shadow-none active:scale-95">
                        <i class="fa-solid fa-plus text-[10px]"></i> Tambah Kuis
                    </button>
                    <button @click="openTambahMateri = true" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg flex items-center gap-2 text-xs font-bold transition shadow-lg shadow-blue-100 dark:shadow-none active:scale-95">
                        <i class="fa-solid fa-plus text-[10px]"></i> Tambah Materi
                    </button>
                </div>
            </div>

            <div class="w-full">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-gray-400 dark:text-gray-500 text-[10px] font-bold uppercase tracking-[0.15em]">
                            <th class="pb-5 px-4 w-1/3">Daftar Kuis dan Materi</th>
                            <th class="pb-5 px-4 text-center">Jumlah Pengerjaan</th>
                            <th class="pb-5 px-4">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="border-t border-gray-100 dark:border-slate-800">
                        @php
                            $items = [
                                ['type' => 'kuis', 'title' => 'Kuis 1', 'sub' => 'Judul kuis dasar', 'count' => '45 Orang', 'desc' => 'Diisi JPL, deskripsi singkat, serta tanggal mulai dan selesai akses.'],
                                ['type' => 'kuis', 'title' => 'Kuis 2', 'sub' => 'Evaluasi lanjutan', 'count' => '28 Orang', 'desc' => 'Unit perawatan intensif untuk evaluasi standar pelayanan ICU.'],
                                ['type' => 'materi', 'title' => 'Materi 1', 'sub' => 'Modul Farmakologi', 'count' => '15 Orang', 'desc' => 'Pengelolaan dan penyediaan obat-obatan standar rumah sakit.'],
                                ['type' => 'materi', 'title' => 'Materi 2', 'sub' => 'Prosedur Radiologi', 'count' => '12 Orang', 'desc' => 'Layanan diagnostik menggunakan alat radiologi terbaru.'],
                                ['type' => 'materi', 'title' => 'Materi 3', 'sub' => 'Layanan Anak', 'count' => '22 Orang', 'desc' => 'Layanan kesehatan rawat jalan khusus poliklinik anak.'],
                            ];
                        @endphp

                        @foreach($items as $item)
                        <tr class="border-b border-gray-50 dark:border-slate-800 hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="py-6 px-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 bg-blue-50 dark:bg-blue-900/30 text-blue-500 dark:text-blue-400 rounded-lg flex items-center justify-center flex-shrink-0 shadow-sm transition-colors">
                                        @if($item['type'] == 'kuis')
                                            <i class="fa-regular fa-lightbulb text-base"></i>
                                        @else
                                            <i class="fa-solid fa-book-open text-base"></i>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-[13px] font-bold text-gray-800 dark:text-white leading-tight transition-colors">{{ $item['title'] }}</p>
                                        <p class="text-[11px] text-gray-400 dark:text-gray-400 mt-0.5 font-medium transition-colors">{{ $item['sub'] }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-6 px-4 text-center">
                                <span class="bg-gray-100 dark:bg-slate-800 text-gray-500 dark:text-gray-300 px-4 py-1.5 rounded-full text-[10px] font-bold italic tracking-tight transition-colors">
                                    {{ $item['count'] }}
                                </span>
                            </td>
                            <td class="py-6 px-4">
                                <p class="text-[11px] text-gray-400 dark:text-gray-300 font-medium leading-relaxed max-w-sm italic transition-colors">
                                    {{ $item['desc'] }}
                                </p>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    {{-- MODAL EDIT FOLDER --}}
    <div x-show="openEditFolder" class="fixed inset-0 z-[70] flex items-center justify-center bg-black/60 backdrop-blur-sm" x-cloak x-transition>
        <div @click.away="openEditFolder = false" class="bg-white dark:bg-slate-900 w-full max-w-4xl rounded-2xl shadow-2xl overflow-hidden mx-4 transition-colors duration-300">
            <div class="flex justify-between items-center px-8 py-5 border-b dark:border-slate-800">
                <h2 class="text-sm font-bold text-gray-800 dark:text-white">Edit Folder</h2>
                <button @click="openEditFolder = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-white transition"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
            <div class="p-8 space-y-5 max-h-[80vh] overflow-y-auto custom-scrollbar">
                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-white mb-2">Nama Materi</label>
                    <input type="text" value="Protokol Keselamatan Radiasi" class="w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg h-11 px-4 text-sm text-gray-800 dark:text-white focus:ring-1 focus:ring-blue-500 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-white mb-2">Deskripsi Pelatihan</label>
                    <textarea class="w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg p-4 text-sm text-gray-800 dark:text-white h-32 resize-none outline-none focus:ring-1 focus:ring-blue-500 transition-all">xxxxxxxxxxxxxxxxx</textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-white mb-2">JPL</label>
                        <input type="text" value="3" class="w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg h-11 px-4 text-sm dark:text-white outline-none focus:ring-1 focus:ring-blue-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-white mb-2">Tanggal Mulai dan Selesai</label>
                        <input type="text" value="3 April - 13 April 2026" class="w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg h-11 px-4 text-sm dark:text-white outline-none focus:ring-1 focus:ring-blue-500 transition-all">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-8 mt-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-white mb-2">Unggah Thumbnail</label>
                        <div class="border-2 border-dashed border-gray-200 dark:border-slate-700 rounded-xl p-8 flex flex-col items-center justify-center bg-slate-50/50 dark:bg-slate-800 relative hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors group cursor-pointer">
                            <i class="fa-solid fa-image text-blue-500 text-2xl mb-2"></i>
                            <p class="text-[11px] font-bold text-gray-700 dark:text-white">Click or drag and drop to upload</p>
                            <p class="text-[9px] text-gray-400 dark:text-gray-400">Rekomendasi ukuran 16:9 (Min. 800x450px)</p>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-white mb-2">Unit Kerja Terkait (Multi-select)</label>
                        <div class="border border-gray-200 dark:border-slate-700 rounded-xl p-4 grid grid-cols-2 gap-2 max-h-40 overflow-y-auto custom-scrollbar bg-slate-50/30 dark:bg-slate-800/30 transition-colors">
                            @foreach(['Bedah','IGD','Rawat Inap','Laboratorium','Ambulans','ICU','Front Office','Sanitasi','Radiologi'] as $u)
                            <label class="flex items-center gap-2 text-[10px] font-bold text-gray-600 dark:text-gray-300 cursor-pointer group">
                                <input type="checkbox" checked class="rounded dark:bg-slate-700 border-gray-300 dark:border-slate-600 text-blue-600 w-3.5 h-3.5"> <span class="group-hover:text-gray-900 dark:group-hover:text-white transition-colors">{{ $u }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="flex justify-end gap-3 pt-6 border-t dark:border-slate-800">
                    <button @click="openEditFolder = false" class="px-8 py-2 border dark:border-slate-700 rounded-lg text-xs font-bold text-gray-500 dark:text-white hover:bg-gray-50 dark:hover:bg-slate-800 transition">Batal</button>
                    <button class="px-8 py-2 bg-blue-600 text-white rounded-lg text-xs font-bold shadow-lg shadow-blue-100 dark:shadow-none hover:bg-blue-700 transition">Simpan Pengeditan</button>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL TAMBAH KUIS (Singkat karena polanya sama) --}}
    <div x-show="openTambahKuis" class="fixed inset-0 z-[70] flex items-center justify-center bg-black/60 backdrop-blur-sm" x-cloak x-transition>
        <div @click.away="openTambahKuis = false" class="bg-white dark:bg-slate-900 w-full max-w-4xl rounded-2xl shadow-2xl overflow-hidden mx-4 transition-colors">
            
            {{-- Header --}}
            <div class="flex justify-between items-center px-8 py-5 border-b dark:border-slate-800">
                <h2 class="text-sm font-bold text-gray-800 dark:text-white">Tambah Kuis</h2>
                <button @click="openTambahKuis = false" class="text-gray-400 dark:hover:text-white">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <div class="p-8 space-y-6">
                {{-- Input Judul --}}
                <div>
                    <label class="block text-[11px] font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-tight">Input Judul Kuis</label>
                    <input type="text" placeholder="Contoh: Protokol Keselamatan Radiasi" class="w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg h-11 px-4 text-sm dark:text-white outline-none focus:ring-1 focus:ring-blue-500 transition-all">
                </div>

                {{-- Card Soal --}}
                <div class="border border-gray-200 dark:border-slate-700 rounded-xl p-5 bg-white dark:bg-slate-800/30">
                    {{-- Baris Pertanyaan & Toggle --}}
                    <div class="flex items-center gap-4 mb-4">
                        <input type="text" placeholder="Soal" class="flex-1 bg-gray-100 dark:bg-slate-800 border-none rounded-lg h-9 px-4 text-xs text-gray-700 dark:text-white focus:ring-1 focus:ring-blue-500">
                        
                        {{-- Toggle Pilihan Ganda --}}
                        <div class="flex items-center gap-2 px-3 py-1.5 border border-gray-200 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800">
                            <div class="relative inline-flex h-4 w-8 shrink-0 cursor-pointer rounded-full border-2 border-transparent bg-gray-200 dark:bg-slate-700 transition-colors duration-200 ease-in-out focus:outline-none">
                                <span class="translate-x-0 pointer-events-none inline-block h-3 w-3 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                            </div>
                            <span class="text-[10px] font-bold text-gray-400 uppercase">Pilihan Ganda</span>
                        </div>

                        <button class="bg-blue-600 text-white w-8 h-8 rounded-lg flex items-center justify-center shadow-md hover:bg-blue-700 transition active:scale-90">
                            <i class="fa-solid fa-plus text-xs"></i>
                        </button>
                    </div>

                    {{-- List Opsi --}}
                    <div class="space-y-3 ml-1 mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-4 h-4 rounded-full border-2 border-gray-300 dark:border-slate-600"></div>
                            <span class="text-xs text-gray-400 italic">Opsi 1</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-4 h-4 rounded-full border-2 border-blue-500 flex items-center justify-center">
                                <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                            </div>
                            <span class="text-xs text-gray-500 font-medium">Opsi 2</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-4 h-4 rounded-full border-2 border-gray-300 dark:border-slate-600"></div>
                            <span class="text-xs text-gray-300 italic">Tambahkan Opsi</span>
                        </div>
                    </div>

                    {{-- Tombol Hapus Soal --}}
                    <div class="flex justify-end">
                        <button class="flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-[11px] font-bold transition active:scale-95 shadow-sm">
                            <i class="fa-solid fa-trash-can text-[10px]"></i>
                            Hapus Soal
                        </button>
                    </div>
                </div>

                {{-- Pengaturan Waktu & Percobaan --}}
                <div class="space-y-4 pt-2">
                    <div class="flex items-center gap-8">
                        <label class="w-32 text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-tight">Set. Waktu</label>
                        <div class="relative">
                            <input type="text" value="10 Menit" class="w-28 border border-gray-800 dark:border-slate-600 rounded-lg px-3 py-1.5 text-center text-xs font-bold text-gray-800 dark:text-white bg-transparent outline-none">
                        </div>
                    </div>
                    <div class="flex items-center gap-8">
                        <label class="w-32 text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-tight">Set. Percobaan</label>
                        <div class="relative">
                            <input type="text" value="3 Kali" class="w-28 border border-gray-800 dark:border-slate-600 rounded-lg px-3 py-1.5 text-center text-xs font-bold text-gray-800 dark:text-white bg-transparent outline-none">
                        </div>
                    </div>
                </div>

                {{-- Footer Buttons --}}
                <div class="flex justify-end gap-3 pt-6 border-t dark:border-slate-800 mt-4">
                    <button @click="openTambahKuis = false" class="px-8 py-2.5 border border-gray-200 dark:border-slate-700 rounded-xl text-xs font-bold text-gray-500 dark:text-white hover:bg-gray-50 dark:hover:bg-slate-800 transition">Batal</button>
                    <button class="px-8 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-lg shadow-blue-100 dark:shadow-none transition active:scale-95">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL TAMBAH MATERI (Sama polanya dengan Edit Folder) --}}
    <div x-show="openTambahMateri" class="fixed inset-0 z-[70] flex items-center justify-center bg-black/60 backdrop-blur-sm" x-cloak x-transition>
        <div @click.away="openTambahMateri = false" class="bg-white dark:bg-slate-900 w-full max-w-4xl rounded-2xl shadow-2xl overflow-hidden mx-4 transition-colors">
            <div class="flex justify-between items-center px-8 py-5 border-b dark:border-slate-800">
                <h2 class="text-sm font-bold text-gray-800 dark:text-white">Tambah Materi</h2>
                <button @click="openTambahMateri = false" class="text-gray-400 dark:hover:text-white transition"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
            <div class="p-8 custom-scrollbar max-h-[80vh] overflow-y-auto">
                <div class="grid grid-cols-2 gap-10">
                    <div class="space-y-6">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 dark:text-white mb-2 uppercase transition-colors">Input Sub Materi</label>
                            <div class="border border-gray-200 dark:border-slate-700 rounded-lg p-4 h-32 text-xs text-gray-400 italic">Contoh: Protokol Keselamatan Radiasi</div>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 dark:text-white mb-2 uppercase transition-colors">Deskripsi Sub Materi</label>
                            <div class="border border-gray-200 dark:border-slate-700 rounded-lg h-40"></div>
                        </div>
                    </div>
                    <div class="space-y-6">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 dark:text-white mb-2 uppercase transition-colors">Unggah Video Materi</label>
                            <div class="border-2 border-dashed border-gray-200 dark:border-slate-700 rounded-xl p-8 flex flex-col items-center justify-center bg-slate-50/50 dark:bg-slate-800/50 transition-colors">
                                <i class="fa-solid fa-video text-blue-500 text-2xl mb-3"></i>
                                <p class="text-[11px] font-bold text-gray-700 dark:text-white transition-colors">Upload MP4 / MKV</p>
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 dark:text-white mb-2 uppercase transition-colors">Unggah Dokumen</label>
                            <div class="border-2 border-dashed border-gray-200 dark:border-slate-700 rounded-xl p-8 flex flex-col items-center justify-center bg-slate-50/50 dark:bg-slate-800/50 transition-colors">
                                <i class="fa-solid fa-file-arrow-up text-blue-500 text-2xl mb-3"></i>
                                <p class="text-[11px] font-bold text-gray-700 dark:text-white transition-colors">Upload PPT / PDF</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end gap-3 pt-8 border-t dark:border-slate-800 mt-6 transition-colors">
                    <button @click="openTambahMateri = false" class="px-8 py-2.5 border dark:border-slate-700 rounded-lg text-xs font-bold text-gray-500 dark:text-white hover:bg-gray-50 dark:hover:bg-slate-800 transition">Batal</button>
                    <button class="px-8 py-2.5 bg-blue-600 text-white rounded-lg text-xs font-bold shadow-lg shadow-blue-100 dark:shadow-none hover:bg-blue-700 transition">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 10px; }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; }
    
    [x-cloak] { display: none !important; }
</style>
@endsection