@extends('components.layout')
@section('title', 'Sertifikat Saya')

@section('content')
<div class="flex h-screen overflow-hidden bg-slate-50 dark:bg-slate-950 transition-colors duration-300" 
    x-data="sertifikatComponent">
    
    {{-- Sidebar --}}
    <aside id="sidebar"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed inset-y-0 left-0 z-[60] w-64 bg-white dark:bg-slate-900 border-r dark:border-slate-800 transition-transform duration-300 lg:translate-x-0 lg:static lg:inset-0 flex-shrink-0 overflow-y-auto">
        @include('components.nav')
    </aside>

    {{-- Overlay Mobile --}}
    <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-sm lg:hidden" x-cloak></div>

    <div class="flex-1 flex flex-col min-w-0 transition-colors duration-300">
        {{-- Header --}}
        <header class="bg-white dark:bg-slate-900 border-b dark:border-slate-800 h-16 flex items-center justify-between px-4 lg:px-8 flex-shrink-0 z-40">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = true" class="lg:hidden p-2 text-gray-500 dark:text-white hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition">
                    <i class="fa-solid fa-bars text-lg"></i>
                </button>
                <h1 class="text-sm font-semibold text-gray-600 dark:text-white truncate">Sertifikat Saya</h1>
            </div>
            <div class="flex items-center gap-4">
                @include('components.notif-superadmin')
                <div class="flex items-center gap-3 pl-4 border-l dark:border-slate-800">
                    <div class="text-right hidden sm:block">
                        <p class="text-xs font-bold text-gray-800 dark:text-white leading-tight">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] text-gray-500 dark:text-gray-400 font-medium">{{ auth()->user()->unitKerjas->pluck('unit_name')->join(', ') ?: '-' }}</p>
                    </div>
                    <div class="w-8 h-8 bg-gray-200 dark:bg-slate-700 rounded-full flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-user text-gray-500 dark:text-white text-xs"></i>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-4 lg:p-8 custom-scrollbar">
            @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-950/20 text-emerald-600 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/30 rounded-xl text-xs font-bold flex items-center gap-3">
                <i class="fa-solid fa-circle-check text-base text-emerald-500"></i>
                <span>{{ session('success') }}</span>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 dark:bg-red-950/20 text-red-600 dark:text-red-400 border border-red-100 dark:border-red-900/30 rounded-xl text-xs font-bold flex items-center gap-3">
                <i class="fa-solid fa-circle-xmark text-base text-red-500"></i>
                <span>{{ session('error') }}</span>
            </div>
            @endif

            {{-- Title Section --}}
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
                <div>
                    <h2 class="text-lg lg:text-xl font-bold text-gray-800 dark:text-white">Sertifikat Saya</h2>
                    <p class="text-xs lg:text-sm text-gray-500 dark:text-gray-400 mt-1 leading-relaxed">Selamat! Di sini Anda dapat melihat dan mengunduh seluruh sertifikat kompetensi pelatihan internal dan eksternal Anda.</p>
                </div>
                <button @click="openUploadSertifikat = true" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl flex items-center gap-2 text-xs font-bold shadow-lg shadow-blue-100 dark:shadow-none transition active:scale-95 whitespace-nowrap">
                    Upload Sertifikat Eksternal
                </button>
            </div>

            {{-- Toggle Navigasi --}}
            <div class="w-full">
                <div class="flex p-1 bg-gray-100 dark:bg-slate-800 rounded-xl mb-6 border dark:border-slate-700">
                    <button @click="activeTab = 'internal'"
                        :class="activeTab === 'internal' ? 'bg-emerald-500 text-white shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700'"
                        class="flex-1 py-2.5 text-xs font-bold rounded-lg transition-all duration-300 uppercase">
                        Sertifikat Internal
                    </button>
                    <button @click="activeTab = 'external'"
                        :class="activeTab === 'external' ? 'bg-emerald-500 text-white shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700'"
                        class="flex-1 py-2.5 text-xs font-bold rounded-lg transition-all duration-300 uppercase">
                        Sertifikat Eksternal
                    </button>
                </div>
            </div>

            {{-- Table Container --}}
            <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 p-4 lg:p-8 mb-10 transition-colors duration-300">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                    <h3 class="font-bold text-gray-800 dark:text-white transition-colors uppercase tracking-widest text-xs" 
                        x-text="activeTab === 'internal' ? 'Daftar Sertifikat Internal' : 'Daftar Sertifikat Eksternal'"></h3>
                    <div class="relative w-full sm:w-64">
                        <input type="text" placeholder="Cari sertifikat..." class="w-full pl-3 pr-8 py-2 border-gray-200 dark:border-slate-700 rounded-lg text-xs bg-gray-50 dark:bg-slate-800 text-gray-700 dark:text-white outline-none focus:ring-2 focus:ring-blue-500/20 transition-all">
                        <i class="fa-solid fa-magnifying-glass absolute right-3 top-2.5 text-gray-400 dark:text-white text-xs"></i>
                    </div>
                </div>

                <div class="overflow-x-auto border dark:border-slate-800 rounded-lg transition-colors">
                    {{-- 1. TABEL INTERNAL --}}
                    <div x-show="activeTab === 'internal'" x-transition:enter="transition opacity-0" x-transition:enter-end="opacity-100">
                        <table class="w-full text-left text-xs min-w-[700px]">
                            <thead class="bg-gray-50 dark:bg-slate-800/50 text-gray-500 dark:text-white font-bold border-b dark:border-slate-800">
                                <tr>
                                    <th class="py-4 px-6 uppercase tracking-wider">Nama Sertifikat Pelatihan</th>
                                    <th class="py-4 px-4 uppercase tracking-wider text-center">Status</th>
                                    <th class="py-4 px-6 uppercase tracking-wider text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-slate-800 text-gray-700 dark:text-white">
                                @forelse($sertifikatsInternal as $sertif)
                                <tr class="hover:bg-gray-50/80 dark:hover:bg-slate-800/50 transition-colors">
                                    <td class="py-5 px-6">
                                        <div class="flex flex-col gap-1 cursor-pointer group" @click="previewPdf('{{ $sertif->image_path ? Storage::url($sertif->image_path) : '' }}')">
                                            <div class="flex items-center gap-4">
                                                <div class="w-9 h-9 bg-blue-50 dark:bg-blue-900/20 text-blue-600 rounded-lg flex items-center justify-center shrink-0 border border-blue-100 dark:border-blue-900/30">
                                                    <i class="fa-solid fa-building-circle-check"></i>
                                                </div>
                                                <span class="font-bold uppercase tracking-tight group-hover:text-blue-600 transition-colors">{{ $sertif->materi->judul ?? 'Sertifikat Pelatihan' }}</span>
                                            </div>
                                            @if(!empty($sertif->deskripsi))
                                                <span class="text-[10px] text-gray-500 dark:text-gray-400 italic ml-12 block">{{ $sertif->deskripsi }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-5 px-4 text-center">
                                        <span class="px-3 py-1 rounded-full text-[9px] font-bold border uppercase 
                                            {{ $sertif->status === 'Disetujui' ? 'bg-emerald-50 text-emerald-600 border-emerald-100 dark:bg-emerald-950/20' : 'bg-amber-50 text-amber-600 border-amber-100 dark:bg-amber-950/20' }}">
                                            {{ $sertif->status }}
                                        </span>
                                    </td>
                                    <td class="py-5 px-6 text-right">
                                        @if($sertif->status === 'Disetujui')
                                            <a href="{{ $sertif->image_path ? Storage::url($sertif->image_path) : '#' }}" download class="inline-block text-blue-600 hover:text-blue-700 transition p-2">
                                                <i class="fa-solid fa-download"></i>
                                            </a>
                                        @else
                                            <button class="text-gray-300 p-2 cursor-not-allowed" disabled>
                                                <i class="fa-solid fa-download"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="py-8 text-center text-gray-400 italic">Belum ada sertifikat internal yang disetujui.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- 2. TABEL EKSTERNAL --}}
                    <div x-show="activeTab === 'external'" x-cloak x-transition:enter="transition opacity-0" x-transition:enter-end="opacity-100">
                        <table class="w-full text-left text-xs min-w-[700px]">
                            <thead class="bg-gray-50 dark:bg-slate-800/50 text-gray-500 dark:text-white font-bold border-b dark:border-slate-800">
                                <tr>
                                    <th class="py-4 px-6 uppercase tracking-wider">Nama Sertifikat</th>
                                    <th class="py-4 px-4 uppercase tracking-wider text-center">Status Verifikasi</th>
                                    <th class="py-4 px-6 uppercase tracking-wider text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-slate-800 text-gray-700 dark:text-white transition-colors">
                                @forelse($sertifikatsEksternal as $ext)
                                <tr class="hover:bg-gray-50/80 dark:hover:bg-slate-800/50 transition-colors">
                                    <td class="py-5 px-6">
                                        <div class="flex flex-col gap-1 min-w-0 cursor-pointer group" @click="previewPdf('{{ $ext->image_path ? Storage::url($ext->image_path) : '' }}')">
                                            <span class="font-bold uppercase tracking-tight truncate group-hover:text-blue-600 transition-colors">{{ $ext->judul }}</span>
                                            @if($ext->deskripsi)
                                                <span class="text-[10px] text-gray-500 font-medium italic">Catatan: {{ $ext->deskripsi }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-5 px-4 text-center">
                                        <span class="px-3 py-1 rounded-full text-[9px] font-bold border uppercase 
                                            {{ $ext->status === 'Disetujui' ? 'bg-emerald-50 text-emerald-600 border-emerald-100 dark:bg-emerald-950/20' : 'bg-red-50 text-red-600 border-red-100 dark:bg-red-950/20' }}">
                                            {{ $ext->status }}
                                        </span>
                                    </td>
                                    <td class="py-5 px-6 text-right">
                                        @if($ext->status === 'Disetujui')
                                            <a href="{{ $ext->image_path ? Storage::url($ext->image_path) : '#' }}" download class="inline-block text-blue-600 p-2 transition">
                                                <i class="fa-solid fa-download"></i>
                                            </a>
                                        @else
                                            <button class="text-gray-300 p-2 cursor-not-allowed" disabled>
                                                <i class="fa-solid fa-download"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="py-8 text-center text-gray-400 italic">Belum ada sertifikat eksternal yang disetujui atau ditolak.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    {{-- MODAL PREVIEW SERTIFIKAT --}}
    <div x-show="openPreviewSertifikat" 
        class="fixed inset-0 z-[110] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        x-cloak>
        
        <div @click.away="openPreviewSertifikat = false" 
            class="bg-white dark:bg-slate-900 w-full max-w-5xl rounded-2xl shadow-2xl overflow-hidden transition-all duration-300 border dark:border-slate-800">
            
            <div class="flex items-center justify-between px-8 py-5 border-b dark:border-slate-800">
                <h3 class="text-sm font-bold text-gray-800 dark:text-white uppercase tracking-tight">Pratinjau Sertifikat</h3>
                <button @click="openPreviewSertifikat = false" class="text-gray-400 hover:text-red-500 transition-colors">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <div class="p-6 bg-gray-50 dark:bg-slate-950/30">
                <div class="relative w-full flex justify-center min-h-[500px]">
                    <template x-if="previewUrl">
                        <iframe :src="previewUrl" class="w-full h-[600px] rounded-lg shadow-sm border dark:border-slate-800"></iframe>
                    </template>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL POP UP: UPLOAD SERTIFIKAT EKSTERNAL --}}
    <div x-show="openUploadSertifikat" 
        class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        x-cloak>
        
        <form @submit="if(!selectedFileName) { alert('File wajib diunggah!'); $event.preventDefault(); }"
            action="{{ route('sertifikat.eksternal.upload') }}" method="POST" enctype="multipart/form-data" @click.away="openUploadSertifikat = false" 
            class="bg-white dark:bg-slate-900 w-full max-w-2xl rounded-2xl shadow-2xl overflow-hidden transition-all duration-300 border dark:border-slate-800"
            x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="scale-95"
            x-transition:enter-end="scale-100"
            x-data="{ selectedFileName: '' }">
            
            @csrf
            <div class="flex items-center justify-between px-8 py-5 border-b dark:border-slate-800">
                <h3 class="text-sm font-bold text-gray-800 dark:text-white uppercase tracking-tight">Upload Sertifikat Eksternal</h3>
                <button type="button" @click="openUploadSertifikat = false" class="text-gray-400 hover:text-red-500 transition-colors">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <div class="p-10 space-y-6">
                {{-- Input Judul --}}
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-gray-500 dark:text-white mb-2 uppercase tracking-wider">Judul Sertifikat <span class="text-red-500">*</span></label>
                    <input type="text" name="judul" required placeholder="Masukkan judul sertifikat..." 
                        class="w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl h-12 px-4 focus:ring-2 focus:ring-blue-500/10 outline-none transition-all text-xs text-gray-700 dark:text-white">
                </div>

                {{-- Area Upload --}}
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-gray-500 dark:text-white mb-2 uppercase tracking-wider">Berkas Sertifikat (PDF) <span class="text-red-500">*</span></label>
                    <div class="border-2 border-dashed border-gray-200 dark:border-slate-700 rounded-2xl p-12 flex flex-col items-center justify-center bg-slate-50/30 dark:bg-slate-800/20 hover:bg-white dark:hover:bg-slate-800/50 transition-colors group cursor-pointer relative text-center">
                        <input type="file" name="file_sertifikat" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" accept=".pdf"
                            @change="
                                const file = $event.target.files[0];
                                if (file) {
                                    if (file.type !== 'application/pdf') {
                                        alert('Peringatan: Berkas harus berformat PDF!');
                                        $event.target.value = '';
                                        selectedFileName = '';
                                    } else {
                                        selectedFileName = file.name;
                                    }
                                } else {
                                    selectedFileName = '';
                                }
                            ">
                        
                        <div class="space-y-4">
                            <div class="w-12 h-12 bg-blue-50 dark:bg-blue-900/30 rounded-lg flex items-center justify-center mx-auto transition-transform group-hover:scale-110">
                                <i class="fa-solid fa-file-arrow-up text-blue-600 dark:text-blue-400 text-xl"></i>
                            </div>
                            
                            <div class="space-y-1">
                                <p class="text-xs font-bold text-gray-700 dark:text-white uppercase tracking-wider" x-text="selectedFileName || 'Click or drag and drop to upload'"></p>
                                <p class="text-[10px] text-gray-400 uppercase font-medium">Maksimal ukuran file 5MB</p>
                            </div>

                            <p class="text-[10px] text-blue-600 dark:text-blue-400 font-bold uppercase tracking-widest border border-blue-100 dark:border-blue-900/50 px-2 py-0.5 rounded inline-block">
                                Accepted: PDF
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" @click="openUploadSertifikat = false" 
                        class="px-8 py-2.5 border border-gray-200 dark:border-slate-700 rounded-xl text-[10px] font-bold text-gray-400 hover:text-gray-600 dark:hover:text-white transition uppercase tracking-widest">
                        Batal
                    </button>
                    <button type="submit" class="px-10 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-[10px] font-bold shadow-lg shadow-blue-100 dark:shadow-none transition-all active:scale-95 uppercase tracking-widest">
                        Upload
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
    .custom-scrollbar::-webkit-scrollbar { width: 4px; height: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 10px; }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; }
</style>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('sertifikatComponent', () => ({
            sidebarOpen: false,
            darkMode: localStorage.getItem('theme') === 'dark',
            activeTab: 'internal',
            openUploadSertifikat: false,
            openPreviewSertifikat: false,
            previewUrl: null,
            previewPdf(url) {
                this.previewUrl = url;
                this.openPreviewSertifikat = true;
            }
        }))
    })
</script>
@endsection