@extends('components.layout')
@section('title', 'Review Sertifikat Eksternal')

@section('content')
<div class="flex h-screen overflow-hidden bg-slate-50 dark:bg-slate-950 transition-colors duration-300" 
    x-data="reviewPelatihanData()">
    
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
                <button @click="sidebarOpen = true" class="lg:hidden p-2 text-gray-500 dark:text-white hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition">
                    <i class="fa-solid fa-bars text-lg"></i>
                </button>
                <h1 class="text-sm font-semibold text-gray-600 dark:text-white truncate">Laporan & Monitoring</h1>
            </div>

            <div class="flex items-center gap-3 lg:gap-4">
                @include('components.notif-superadmin')
                <div class="flex items-center gap-3 pl-2 lg:pl-4 border-l border-gray-100 dark:border-slate-800">
                    <div class="text-right hidden sm:block">
                        <p class="text-xs font-bold text-gray-800 dark:text-white leading-tight">Admin</p>
                        <p class="text-[10px] text-gray-500 dark:text-gray-300 font-medium italic">Administrator</p>
                    </div>
                    <div class="w-8 h-8 bg-gray-200 dark:bg-slate-700 rounded-full flex items-center justify-center shrink-0">
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
                        <a href="/laporan-monitoring" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                            Laporan & Monitoring
                        </a>
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="text-gray-300 dark:text-gray-600"> > </span>
                        <span class="text-gray-800 dark:text-white font-semibold">Validasi Pelatihan</span>
                    </li>
                </ol>
            </nav>

            {{-- Main Card Container --}}
            <div class="bg-white dark:bg-slate-900 rounded-xl border border-gray-100 dark:border-slate-800 shadow-sm p-6 lg:p-12 mb-10 transition-colors duration-300 relative">
                
                {{-- Header Inside Card --}}
                <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white uppercase tracking-wider">Detail Sertifikat Eksternal</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            Karyawan: <span class="font-bold text-gray-700 dark:text-gray-200">{{ $user->nama }}</span> ({{ $user->nip }})
                        </p>
                    </div>
                    <span class="font-bold text-xs px-3 py-1.5 rounded-full border uppercase tracking-wider"
                        :class="{
                            'bg-emerald-50 text-emerald-600 border-emerald-100': status === 'Disetujui',
                            'bg-amber-50 text-amber-600 border-amber-100': status === 'Belum Disetujui' || status === 'Menunggu',
                            'bg-red-50 text-red-600 border-red-100 dark:bg-red-900/30 dark:text-red-400 dark:border-red-800': status === 'Ditolak' || status === 'Tidak Disetujui'
                        }"
                        x-text="status"></span>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">
                    {{-- Kolom Kiri: Pratinjau Sertifikat PDF --}}
                    <div class="lg:col-span-7 space-y-6">
                        <div class="rounded-xl overflow-hidden border-2 border-gray-100 dark:border-slate-800 shadow-md bg-white dark:bg-slate-900 h-[650px] relative">
                            @if($sertifikat->image_path)
                                <iframe src="{{ $pdfUrl }}" class="w-full h-full" frameborder="0"></iframe>
                            @else
                                <div class="flex flex-col items-center justify-center h-full text-gray-400">
                                    <i class="fa-solid fa-file-pdf text-5xl mb-3"></i>
                                    <p class="text-xs font-bold">Dokumen sertifikat tidak tersedia</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Kolom Kanan: Form Penilaian --}}
                    <div class="lg:col-span-5 flex flex-col space-y-6">
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 dark:text-white uppercase tracking-wider mb-2 block">Judul Pelatihan</label>
                            <p class="text-sm font-bold text-gray-800 dark:text-white leading-relaxed border-2 border-gray-50 dark:border-slate-800 p-3.5 rounded-xl bg-gray-50/30 dark:bg-slate-900/30">
                                {{ $sertifikat->judul }}
                            </p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-4 rounded-xl border-2 border-gray-100 dark:border-slate-800 text-center bg-white dark:bg-slate-900 shadow-sm transition-colors">
                                <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Internal JPL</p>
                                <p class="text-lg font-black text-gray-800 dark:text-white mt-1">{{ $internalJpl }} <span class="text-xs font-normal text-gray-400">Jam</span></p>
                            </div>
                            <div class="p-4 rounded-xl border-2 border-gray-100 dark:border-slate-800 text-center bg-white dark:bg-slate-900 shadow-sm transition-colors">
                                <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Eksternal JPL</p>
                                <p class="text-lg font-black text-gray-800 dark:text-white mt-1">{{ $eksternalJpl }} <span class="text-xs font-normal text-gray-400">Jam</span></p>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <div class="flex justify-between items-center">
                                <label class="text-[11px] font-bold text-gray-700 dark:text-white uppercase tracking-tight">Total Keseluruhan</label>
                                <span class="text-[11px] font-bold text-blue-600" x-text="totalJpl + ' / 20 Jam Target'"></span>
                            </div>
                            <div class="relative flex items-center justify-between bg-gray-50/50 dark:bg-slate-800/50 border-2 border-gray-100 dark:border-slate-700 rounded-xl px-4 h-[54px] overflow-hidden">
                                <div class="w-full">
                                    <div class="w-full bg-gray-200 dark:bg-slate-700 rounded-full h-2 overflow-hidden">
                                        <div class="bg-blue-600 h-full rounded-full transition-all duration-1000 ease-out" :style="'width: ' + Math.min(100, Math.round((totalJpl / 20) * 100)) + '%'"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-gray-700 dark:text-white uppercase tracking-tight">Komentar/Catatan Peninjauan</label>
                            <textarea rows="3" x-model="comments" placeholder="Masukkan catatan atau revisi..." class="w-full bg-white dark:bg-slate-800 border-2 border-gray-100 dark:border-slate-700 rounded-xl px-4 py-3 text-xs text-gray-700 dark:text-white focus:border-blue-500 focus:outline-none transition-colors resize-none"></textarea>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-gray-700 dark:text-white uppercase tracking-tight">Konfirmasi Jam Pembelajaran (JPL)</label>
                            <input type="number" min="0" x-model="jplInput" placeholder="Masukkan jumlah jam..." class="w-full bg-white dark:bg-slate-800 border-2 border-gray-100 dark:border-slate-700 rounded-xl px-4 py-3 text-xs text-gray-700 dark:text-white focus:border-blue-500 focus:outline-none transition-colors" />
                        </div>

                        <div class="pt-5 flex justify-center"> 
                            <button @click="openModalVerifikasi = true" class="bg-blue-600 hover:bg-blue-700 text-white px-10 py-3 rounded-xl text-[12px] font-bold shadow-lg shadow-blue-200 dark:shadow-none transition-all active:scale-95">
                                Simpan Validasi
                            </button>
                        </div>
                    </div>
                </div>

                {{-- MODAL POP UP: VERIFIKASI KELAYAKAN --}}
                <div x-show="openModalVerifikasi" 
                    class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    x-cloak>
                    
                    <div @click.away="openModalVerifikasi = false" 
                        class="bg-white dark:bg-slate-900 w-full max-w-md rounded-2xl shadow-2xl overflow-hidden transition-all duration-300 border dark:border-slate-800"
                        x-transition:enter="transition ease-out duration-300 transform"
                        x-transition:enter-start="scale-95"
                        x-transition:enter-end="scale-100">
                        
                        {{-- Modal Content --}}
                        <div class="p-8 space-y-6">
                            <div class="space-y-2">
                                <h3 class="text-sm font-bold text-gray-800 dark:text-white">Verifikasi Kelayakan</h3>
                                <p class="text-[11px] text-gray-500 dark:text-gray-400 leading-relaxed">
                                    Tinjau seluruh isi dan nilai sertifikat sebelum memberikan keputusan verifikasi untuk kelayakan sertifikat.
                                </p>
                            </div>

                            <div class="border-y dark:border-slate-800 py-4 space-y-2 text-xs">
                                <div class="flex justify-between">
                                    <span class="text-gray-400">JPL Konfirmasi:</span>
                                    <span class="font-bold text-gray-800 dark:text-white" x-text="(jplInput || 0) + ' Jam'"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Komentar:</span>
                                    <span class="font-bold text-gray-800 dark:text-white italic max-w-[200px] truncate" x-text="comments || '-'"></span>
                                </div>
                            </div>

                            {{-- Status Keputusan --}}
                            <div class="space-y-3">
                                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Status Keputusan</label>
                                <div class="grid grid-cols-2 gap-3">
                                    <button @click="submitVerification('Setuju')" class="flex items-center justify-center gap-2 py-2.5 px-4 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg text-[10px] font-bold uppercase tracking-widest transition-all active:scale-95 shadow-sm">
                                        <i class="fa-regular fa-circle-check"></i> Setuju
                                    </button>
                                    <button @click="submitVerification('Tolak')" class="flex items-center justify-center gap-2 py-2.5 px-4 bg-red-500 hover:bg-red-600 text-white rounded-lg text-[10px] font-bold uppercase tracking-widest transition-all active:scale-95 shadow-sm">
                                        <i class="fa-regular fa-circle-xmark"></i> Tolak
                                    </button>
                                </div>
                            </div>

                            {{-- Info Box --}}
                            <div class="bg-blue-50/50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800 p-4 rounded-xl flex gap-3">
                                <i class="fa-solid fa-circle-info text-blue-500 mt-0.5 text-xs"></i>
                                <p class="text-[10px] text-blue-600/80 dark:text-blue-400 leading-relaxed">
                                    Menyetujui akan secara otomatis mencatat JPL ke database sistem. Menolak akan mengatur JPL ke 0 dan mewajibkan catatan komentar diisi.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 10px; }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; }
    [x-cloak] { display: none !important; }
</style>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('reviewPelatihanData', () => ({
            sidebarOpen: false, 
            darkMode: localStorage.getItem('theme') === 'dark',
            openModalVerifikasi: false,
            
            // Backend values mapped
            internalJpl: {{ $internalJpl }},
            eksternalJpl: {{ $eksternalJpl }},
            totalJpl: {{ $totalJpl }},
            status: '{{ $sertifikat->status }}',
            
            // Inputs
            comments: '{{ $sertifikat->deskripsi }}',
            jplInput: '{{ $sertifikat->jpl }}',

            async submitVerification(decision) {
                // Validation checks
                if (decision === 'Setuju') {
                    if (!this.jplInput || parseInt(this.jplInput) <= 0) {
                        Toast.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Konfirmasi Jam Pembelajaran (JPL) wajib diisi dengan nilai lebih dari 0 untuk menyetujui sertifikat.'
                        });
                        this.openModalVerifikasi = false;
                        return;
                    }
                } else if (decision === 'Tolak') {
                    if (!this.comments || this.comments.trim().length < 5) {
                        Toast.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Komentar/Catatan Peninjauan wajib diisi (minimal 5 karakter) untuk memberikan alasan penolakan sertifikat.'
                        });
                        this.openModalVerifikasi = false;
                        return;
                    }
                    this.jplInput = 0;
                }

                try {
                    const response = await fetch('/api/admin/sertifikat-eksternal/verifikasi/{{ $sertifikat->sertifikat_eksternal_id }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            decision: decision,
                            jpl: this.jplInput,
                            deskripsi: this.comments
                        })
                    });

                    const result = await response.json();
                    if (result.success) {
                        Toast.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Validasi berhasil.',
                        });

                        setTimeout(() => {
                            window.location.href = '/laporan-monitoring';
                        }, 1500);
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Terjadi kesalahan saat memproses verifikasi.'
                        });
                    }
                } catch (error) {
                    console.error('Error submitting verification:', error);
                    Toast.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Gagal terhubung ke server.'
                    });
                } finally {
                    this.openModalVerifikasi = false;
                }
            }
        }));
    });
</script>
@endsection