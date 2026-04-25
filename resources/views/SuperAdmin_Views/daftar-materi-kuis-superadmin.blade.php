@extends('components.layout')
@section('title', 'Detail Materi & Kuis')

@section('content')
<div class="flex h-screen overflow-hidden bg-slate-50 dark:bg-slate-950 transition-colors duration-300" 
     x-data="{ 
        sidebarOpen: false, 
        darkMode: localStorage.getItem('theme') === 'dark',
        openTambahMateri: false,
        openTambahKuis: false,
        openEditMateri: false,
        openEditKuis: false,
        selectedMateri: { id: '', judul: '', deskripsi: '' },
        selectedKuis: { 
            id: '', 
            judul: '', 
            waktu_pengerjaan: '', 
            ulang_post_test: '',
            questions: []
        },
        editMateri(item) {
            this.selectedMateri = {
                id: item.sub_materi_id,
                judul: item.judul,
                deskripsi: item.deskripsi || ''
            };
            this.openEditMateri = true;
        },
        editKuis(item) {
            this.selectedKuis = {
                id: item.post_test_id,
                judul: item.judul,
                waktu_pengerjaan: item.waktu_pengerjaan,
                ulang_post_test: item.ulang_post_test,
                questions: item.soals.map(s => {
                    let opts = [];
                    if(s.pilihan_1) opts.push(s.pilihan_1);
                    if(s.pilihan_2) opts.push(s.pilihan_2);
                    if(s.pilihan_3) opts.push(s.pilihan_3);
                    if(s.pilihan_4) opts.push(s.pilihan_4);
                    if(s.pilihan_5) opts.push(s.pilihan_5);
                    
                    // Map back numbers to letters for UI consistency if needed, 
                    // but the controller handles both now. Let's keep numbers for consistency.
                    return {
                        soal: s.soal,
                        options: opts,
                        status_pilihan: s.status_pilihan,
                        jawaban_benar: s.jawaban_benar
                    };
                })
            };
            this.openEditKuis = true;
        }
     }">
    
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-slate-900 border-r dark:border-slate-800 transition-transform duration-300 lg:translate-x-0 lg:static lg:inset-0 shrink-0 overflow-y-auto">
        @include('components.nav-superadmin')
    </aside>

    <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition:enter="transition opacity-100 ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:leave="transition opacity-100 ease-in duration-200" x-transition:leave-end="opacity-0" class="fixed inset-0 z-40 bg-black/50 backdrop-blur-sm lg:hidden" x-cloak></div>

    <div class="flex-1 flex flex-col min-w-0 transition-colors duration-300">
        <header class="bg-white dark:bg-slate-900 border-b dark:border-slate-800 h-16 flex items-center justify-between px-4 lg:px-8 shrink-0 z-10 transition-colors duration-300">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = true" class="lg:hidden p-2 text-gray-500 dark:text-white"><i class="fa-solid fa-bars text-lg"></i></button>
                <h1 class="text-sm font-semibold text-gray-600 dark:text-white truncate">Detail Isi Pelatihan</h1>
            </div>
            <div class="flex items-center gap-3 lg:gap-4">
                @include('components.notif-superadmin')
                <div class="flex items-center gap-3 pl-2 lg:pl-4 border-l border-gray-100 dark:border-slate-800">
                    <div class="text-right hidden sm:block">
                        <p class="text-xs font-bold text-gray-800 dark:text-white leading-tight">Superadmin</p>
                        <p class="text-[10px] text-gray-500 dark:text-gray-300 font-medium italic">Utama</p>
                    </div>
                    <div class="w-8 h-8 bg-gray-200 dark:bg-slate-700 rounded-full overflow-hidden border border-gray-100 dark:border-slate-800 flex items-center justify-center">
                        <i class="fa-solid fa-user text-gray-500 dark:text-white text-xs"></i>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-4 lg:p-8 custom-scrollbar">
            {{-- Breadcrumb --}}
            <nav class="mb-6 text-[14px] font-medium">
                <ol class="flex items-center gap-2 text-gray-500 dark:text-gray-400">
                    <li><a href="{{ route('manajemen-pelatihan') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Manajemen Pelatihan</a></li>
                    <li class="flex items-center gap-2"><span class="text-gray-300 dark:text-gray-600"> > </span><span class="text-gray-800 dark:text-white font-semibold">Isi Pelatihan</span></li>
                </ol>
            </nav>

            <div class="flex flex-col sm:flex-row justify-between items-start gap-4 mb-8">
                <div>
                    <h2 class="text-xl font-bold text-gray-800 dark:text-white">{{ $materi->judul }}</h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $materi->subjudul ?? 'Manajemen Konten Pembelajaran' }}</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <button @click="openTambahMateri = true" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg flex items-center gap-2 text-xs font-bold transition shadow-sm active:scale-95">
                        <i class="fa-solid fa-file-arrow-up"></i> Tambah Materi
                    </button>
                    <button @click="openTambahKuis = true" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2.5 rounded-lg flex items-center gap-2 text-xs font-bold transition shadow-sm active:scale-95">
                        <i class="fa-solid fa-vial"></i> Buat Kuis
                    </button>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-800 text-emerald-600 dark:text-emerald-400 text-xs font-bold rounded-xl flex items-center gap-3">
                    <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-100 dark:border-red-800 text-red-600 dark:text-red-400 text-xs font-bold rounded-xl flex items-center gap-3">
                    <i class="fa-solid fa-circle-xmark"></i> {{ session('error') }}
                </div>
            @endif

            {{-- Combined Table --}}
            <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 overflow-hidden mb-10 transition-colors">
                <table class="w-full text-left text-xs min-w-[800px]">
                    <thead class="text-gray-500 dark:text-white font-bold border-b dark:border-slate-800 bg-gray-50/50 dark:bg-slate-800/50">
                        <tr>
                            <th class="py-4 px-6 uppercase tracking-wider">No</th>
                            <th class="py-4 px-6 uppercase tracking-wider">Daftar Kuis dan Materi</th>
                            <th class="py-4 px-6 uppercase tracking-wider text-center">Jumlah Pengerjaan</th>
                            <th class="py-4 px-6 uppercase tracking-wider">Keterangan</th>
                            <th class="py-4 px-6 uppercase tracking-wider text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-800 text-gray-700 dark:text-white transition-colors">
                        @forelse($contents as $item)
                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-800 transition">
                            <td class="py-5 px-6 font-bold text-gray-400 dark:text-gray-500">{{ $loop->iteration }}</td>
                            <td class="py-5 px-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 {{ $item->type === 'materi' ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400' : 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400' }} rounded-lg flex items-center justify-center shrink-0">
                                        <i class="fa-solid {{ $item->type === 'materi' ? 'fa-file-lines' : 'fa-clipboard-question' }} text-lg"></i>
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-800 dark:text-white text-sm">{{ $item->judul }}</p>
                                        <p class="text-[10px] uppercase font-bold tracking-widest mt-0.5 {{ $item->type === 'materi' ? 'text-blue-500' : 'text-emerald-500' }}">
                                            {{ $item->type }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-5 px-6 text-center">
                                <span class="bg-gray-100 dark:bg-slate-800 px-3 py-1.5 rounded-full font-bold text-[10px]">
                                    <i class="fa-solid fa-users mr-1 opacity-50"></i> {{ $item->jumlah_pengerjaan }} User
                                </span>
                            </td>
                            <td class="py-5 px-6 text-gray-500 dark:text-gray-400 max-w-xs truncate italic">
                                {{ $item->type === 'materi' ? ($item->deskripsi ?? 'Materi Pembelajaran') : '-' }}
                            </td>
                            <td class="py-5 px-6 text-right">
                                <div class="flex justify-end gap-4">
                                    @if($item->type === 'materi')
                                                                                <button @click="editMateri({{ json_encode($item) }})" class="text-gray-400 hover:text-blue-600 transition" title="Edit Materi">
                                            <i class="fa-solid fa-pen"></i>
                                        </button>
                                        <form action="{{ route('pelatihan.destroySubMateri', $item->sub_materi_id) }}" method="POST" onsubmit="return confirm('Hapus materi ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-gray-400 hover:text-red-600 transition"><i class="fa-solid fa-trash-can"></i></button>
                                        </form>
                                    @else
                                        <button @click="editKuis({{ json_encode($item) }})" class="text-gray-400 hover:text-emerald-600 transition" title="Edit Kuis">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                        <form action="{{ route('pelatihan.destroyPostTest', $item->post_test_id) }}" method="POST" onsubmit="return confirm('Hapus kuis ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-gray-400 hover:text-red-600 transition"><i class="fa-solid fa-trash-can"></i></button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-16 text-center text-gray-400 dark:text-gray-500 italic">
                                Belum ada materi atau kuis di pelatihan ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    {{-- MODAL TAMBAH MATERI --}}
    <div x-show="openTambahMateri" class="fixed inset-0 z-60 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4" x-transition x-cloak>
        <div @click.away="openTambahMateri = false" class="bg-white dark:bg-slate-900 w-full max-w-2xl rounded-2xl shadow-2xl overflow-hidden transition-all duration-300">
            <div class="flex justify-between items-center px-8 py-5 border-b dark:border-slate-800">
                <h2 class="text-base font-bold text-gray-800 dark:text-white">Tambah Materi Baru</h2>
                <button @click="openTambahMateri = false" class="text-gray-400 hover:text-gray-800 dark:hover:text-white transition"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
            <div class="p-8" x-data="{ uploadType: null }">
                <form action="{{ route('pelatihan.storeSubMateri', $materi->materi_id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-white mb-2 uppercase">Judul Sub-Materi</label>
                        <input type="text" name="judul" required placeholder="Contoh: Pengenalan Alat Medis" class="w-full bg-white dark:bg-slate-800 border dark:border-slate-700 rounded-lg h-12 px-4 text-sm dark:text-white outline-none focus:ring-2 focus:ring-blue-500/20">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-white mb-2 uppercase">Deskripsi Singkat</label>
                        <textarea name="deskripsi" rows="3" class="w-full bg-white dark:bg-slate-800 border dark:border-slate-700 rounded-lg p-4 text-sm dark:text-white resize-none outline-none focus:ring-2 focus:ring-blue-500/20"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <label :class="uploadType === 'doc' ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer'" class="relative border-2 border-dashed border-gray-200 dark:border-slate-700 rounded-xl p-6 flex flex-col items-center justify-center bg-slate-50/50 dark:bg-slate-800/50 hover:bg-slate-50 transition-colors">
                            <input type="file" name="file_materi" accept="video/*" :disabled="uploadType === 'doc'" @change="uploadType = $event.target.files.length ? 'video' : null" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            <i class="fa-solid fa-film text-blue-500 text-2xl mb-2"></i>
                            <p class="text-[11px] font-bold text-gray-700 dark:text-white uppercase">Upload Video</p>
                            <p class="text-[9px] text-gray-400 mt-1">MP4, MOV (Max 50MB)</p>
                        </label>
                        <label :class="uploadType === 'video' ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer'" class="relative border-2 border-dashed border-gray-200 dark:border-slate-700 rounded-xl p-6 flex flex-col items-center justify-center bg-slate-50/50 dark:bg-slate-800/50 hover:bg-slate-50 transition-colors">
                            <input type="file" name="file_materi" accept=".pdf,.ppt,.pptx" :disabled="uploadType === 'video'" @change="uploadType = $event.target.files.length ? 'doc' : null" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            <i class="fa-solid fa-file-pdf text-red-500 text-2xl mb-2"></i>
                            <p class="text-[11px] font-bold text-gray-700 dark:text-white uppercase">Upload Dokumen</p>
                            <p class="text-[9px] text-gray-400 mt-1">PDF, PPT (Max 5MB)</p>
                        </label>
                    </div>

                    <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4">
                        <button @click="openTambahMateri = false" type="button" class="w-full sm:w-auto px-8 py-2.5 border dark:border-slate-700 text-gray-500 dark:text-white font-bold rounded-lg hover:bg-gray-50 dark:hover:bg-slate-800 transition text-xs">Batal</button>
                        <button type="submit" class="w-full sm:w-auto px-8 py-2.5 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition text-xs active:scale-95">Unggah Materi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL EDIT MATERI --}}
    <div x-show="openEditMateri" class="fixed inset-0 z-60 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4" x-transition x-cloak>
        <div @click.away="openEditMateri = false" class="bg-white dark:bg-slate-900 w-full max-w-2xl rounded-2xl shadow-2xl overflow-hidden transition-all duration-300">
            <div class="flex justify-between items-center px-8 py-5 border-b dark:border-slate-800">
                <h2 class="text-base font-bold text-gray-800 dark:text-white">Edit Materi</h2>
                <button @click="openEditMateri = false" class="text-gray-400 hover:text-gray-800 dark:hover:text-white transition"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
            <div class="p-8" x-data="{ uploadType: null }">
                <form :action="'{{ url('/sub-materi') }}/' + selectedMateri.id" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf @method('PUT')
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-white mb-2 uppercase">Judul Sub-Materi</label>
                        <input type="text" name="judul" x-model="selectedMateri.judul" required class="w-full bg-white dark:bg-slate-800 border dark:border-slate-700 rounded-lg h-12 px-4 text-sm dark:text-white outline-none focus:ring-2 focus:ring-blue-500/20">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-white mb-2 uppercase">Deskripsi Singkat</label>
                        <textarea name="deskripsi" x-model="selectedMateri.deskripsi" rows="3" class="w-full bg-white dark:bg-slate-800 border dark:border-slate-700 rounded-lg p-4 text-sm dark:text-white resize-none outline-none focus:ring-2 focus:ring-blue-500/20"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <label :class="uploadType === 'doc' ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer'" class="relative border-2 border-dashed border-gray-200 dark:border-slate-700 rounded-xl p-6 flex flex-col items-center justify-center bg-slate-50/50 dark:bg-slate-800/50 hover:bg-slate-50 transition-colors">
                            <input type="file" name="file_materi" accept="video/*" :disabled="uploadType === 'doc'" @change="uploadType = $event.target.files.length ? 'video' : null" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            <i class="fa-solid fa-film text-blue-500 text-2xl mb-2"></i>
                            <p class="text-[11px] font-bold text-gray-700 dark:text-white uppercase">Ganti Video</p>
                            <p class="text-[9px] text-gray-400 mt-1">Kosongkan jika tidak ingin ganti</p>
                        </label>
                        <label :class="uploadType === 'video' ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer'" class="relative border-2 border-dashed border-gray-200 dark:border-slate-700 rounded-xl p-6 flex flex-col items-center justify-center bg-slate-50/50 dark:bg-slate-800/50 hover:bg-slate-50 transition-colors">
                            <input type="file" name="file_materi" accept=".pdf,.ppt,.pptx" :disabled="uploadType === 'video'" @change="uploadType = $event.target.files.length ? 'doc' : null" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            <i class="fa-solid fa-file-pdf text-red-500 text-2xl mb-2"></i>
                            <p class="text-[11px] font-bold text-gray-700 dark:text-white uppercase">Ganti Dokumen</p>
                            <p class="text-[9px] text-gray-400 mt-1">Kosongkan jika tidak ingin ganti</p>
                        </label>
                    </div>

                    <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4">
                        <button @click="openEditMateri = false" type="button" class="w-full sm:w-auto px-8 py-2.5 border dark:border-slate-700 text-gray-500 dark:text-white font-bold rounded-lg hover:bg-gray-50 dark:hover:bg-slate-800 transition text-xs">Batal</button>
                        <button type="submit" class="w-full sm:w-auto px-8 py-2.5 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition text-xs active:scale-95">Perbarui Materi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL TAMBAH KUIS --}}
    <div x-show="openTambahKuis" class="fixed inset-0 z-60 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4" x-transition x-cloak>
        <div class="bg-white dark:bg-slate-900 w-full max-w-4xl rounded-2xl shadow-2xl overflow-hidden transition-all duration-300 max-h-[90vh] flex flex-col">
            <div class="flex justify-between items-center px-8 py-5 border-b dark:border-slate-800 shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 flex items-center justify-center rounded-lg">
                        <i class="fa-solid fa-brain text-sm"></i>
                    </div>
                    <h2 class="text-base font-bold text-gray-800 dark:text-white">Dynamic Quiz Builder</h2>
                </div>
                <button @click="openTambahKuis = false" class="text-gray-400 hover:text-gray-800 dark:hover:text-white transition"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
            
            <div class="p-8 overflow-y-auto custom-scrollbar flex-1" 
                 x-data="{ 
                    questions: [
                        { soal: '', options: ['', ''], status_pilihan: 0, jawaban_benar: '' }
                    ],
                    addQuestion() {
                        this.questions.push({ soal: '', options: ['', ''], status_pilihan: 0, jawaban_benar: '' });
                    },
                    removeQuestion(index) {
                        if(this.questions.length > 1) this.questions.splice(index, 1);
                    },
                    addOption(qIndex) {
                        if(this.questions[qIndex].options.length < 5) this.questions[qIndex].options.push('');
                    },
                    removeOption(qIndex, optIndex) {
                        if(this.questions[qIndex].options.length > 2) this.questions[qIndex].options.splice(optIndex, 1);
                    }
                 }">
                
                <form action="{{ route('pelatihan.storePostTest', $materi->materi_id) }}" method="POST" class="space-y-8">
                    @csrf
                    <div class="bg-slate-50 dark:bg-slate-800/50 p-6 rounded-2xl border border-gray-100 dark:border-slate-800 grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-1">
                            <label class="block text-[10px] font-bold text-gray-400 dark:text-white uppercase mb-2">Judul Kuis</label>
                            <input type="text" name="judul" required class="w-full bg-white dark:bg-slate-800 border dark:border-slate-700 rounded-lg h-10 px-4 text-sm dark:text-white outline-none focus:ring-2 focus:ring-emerald-500/20">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 dark:text-white uppercase mb-2">Waktu (Menit)</label>
                            <input type="number" name="waktu_pengerjaan" required min="1" class="w-full bg-white dark:bg-slate-800 border dark:border-slate-700 rounded-lg h-10 px-4 text-sm dark:text-white outline-none focus:ring-2 focus:ring-emerald-500/20">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 dark:text-white uppercase mb-2">Maks Percobaan</label>
                            <input type="number" name="ulang_post_test" required min="1" class="w-full bg-white dark:bg-slate-800 border dark:border-slate-700 rounded-lg h-10 px-4 text-sm dark:text-white outline-none focus:ring-2 focus:ring-emerald-500/20">
                        </div>
                    </div>

                    <div class="space-y-6">
                        <template x-for="(q, index) in questions" :key="index">
                            <div class="bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-2xl overflow-hidden shadow-sm relative">
                                <div class="bg-slate-50 dark:bg-slate-800/30 px-6 py-3 border-b dark:border-slate-800 flex justify-between items-center">
                                    <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Pertanyaan #<span x-text="index + 1"></span></span>
                                    <button type="button" @click="removeQuestion(index)" class="text-red-400 hover:text-red-600 transition"><i class="fa-solid fa-trash-can"></i></button>
                                </div>
                                <div class="p-6 space-y-6">
                                    <textarea :name="'questions['+index+'][soal]'" x-model="q.soal" required placeholder="Tuliskan pertanyaan..." class="w-full bg-white dark:bg-slate-800 border dark:border-slate-700 rounded-xl p-4 text-sm dark:text-white resize-none h-24"></textarea>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                        <div class="space-y-4">
                                            <div class="flex items-center justify-between">
                                                <label class="text-[10px] font-bold text-gray-400 uppercase">Pilihan Jawaban</label>
                                                <button type="button" @click="addOption(index)" x-show="q.options.length < 5" class="text-[10px] font-bold text-emerald-600">+ Tambah Opsi</button>
                                            </div>
                                            <div class="space-y-3">
                                                <template x-for="(opt, oIndex) in q.options" :key="oIndex">
                                                    <div class="flex items-center gap-3">
                                                        <div class="w-6 h-6 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-[10px] font-bold text-gray-400" x-text="String.fromCharCode(65 + oIndex)"></div>
                                                        <input type="text" :name="'questions['+index+'][options][]'" x-model="q.options[oIndex]" required class="flex-1 bg-white dark:bg-slate-800 border dark:border-slate-700 rounded-lg h-9 px-3 text-xs dark:text-white">
                                                        <button type="button" @click="removeOption(index, oIndex)" x-show="q.options.length > 2" class="text-gray-300 hover:text-red-400 transition text-[10px]"><i class="fa-solid fa-xmark"></i></button>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                        <div class="space-y-6">
                                            <div class="flex items-center gap-4 bg-slate-50 dark:bg-slate-800/50 p-2 rounded-lg border dark:border-slate-800">
                                                <button type="button" @click="q.status_pilihan = 0" :class="q.status_pilihan === 0 ? 'bg-white dark:bg-slate-700 text-emerald-600' : 'text-gray-400'" class="flex-1 py-1.5 rounded-md text-[10px] font-bold uppercase">Single</button>
                                                <button type="button" @click="q.status_pilihan = 1" :class="q.status_pilihan === 1 ? 'bg-white dark:bg-slate-700 text-emerald-600' : 'text-gray-400'" class="flex-1 py-1.5 rounded-md text-[10px] font-bold uppercase">Multiple</button>
                                                <input type="hidden" :name="'questions['+index+'][status_pilihan]'" :value="q.status_pilihan">
                                            </div>
                                            <div>
                                                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Jawaban Benar</label>
                                                <input type="text" :name="'questions['+index+'][jawaban_benar]'" x-model="q.jawaban_benar" required placeholder="A atau 1" class="w-full bg-white dark:bg-slate-800 border dark:border-slate-700 rounded-lg h-10 px-4 text-xs dark:text-white">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <button type="button" @click="addQuestion()" class="w-full py-4 border-2 border-dashed border-gray-200 dark:border-slate-800 rounded-2xl text-gray-400 hover:text-emerald-500 transition-all font-bold text-xs uppercase tracking-widest bg-white dark:bg-slate-900/50">
                        <i class="fa-solid fa-plus mr-2"></i> Tambah Pertanyaan Baru
                    </button>

                    <div class="flex flex-col sm:flex-row justify-end gap-3 pt-6 border-t dark:border-slate-800 sticky bottom-0 bg-white dark:bg-slate-900 py-4">
                        <button @click="openTambahKuis = false" type="button" class="w-full sm:w-auto px-8 py-2.5 border dark:border-slate-700 text-gray-500 font-bold rounded-lg text-xs">Batal</button>
                        <button type="submit" class="w-full sm:w-auto px-8 py-2.5 bg-emerald-600 text-white font-bold rounded-lg text-xs">Simpan Kuis</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL EDIT KUIS --}}
    <div x-show="openEditKuis" class="fixed inset-0 z-60 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4" x-transition x-cloak>
        <div class="bg-white dark:bg-slate-900 w-full max-w-4xl rounded-2xl shadow-2xl overflow-hidden transition-all duration-300 max-h-[90vh] flex flex-col">
            <div class="flex justify-between items-center px-8 py-5 border-b dark:border-slate-800 shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 text-blue-600 flex items-center justify-center rounded-lg">
                        <i class="fa-solid fa-pen-to-square text-sm"></i>
                    </div>
                    <h2 class="text-base font-bold text-gray-800 dark:text-white">Edit Quiz Builder</h2>
                </div>
                <button @click="openEditKuis = false" class="text-gray-400 hover:text-gray-800 dark:hover:text-white transition"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
            
            <div class="p-8 overflow-y-auto custom-scrollbar flex-1">
                <form :action="'{{ url('/post-test') }}/' + selectedKuis.id" method="POST" class="space-y-8">
                    @csrf @method('PUT')
                    <div class="bg-slate-50 dark:bg-slate-800/50 p-6 rounded-2xl border border-gray-100 dark:border-slate-800 grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-1">
                            <label class="block text-[10px] font-bold text-gray-400 dark:text-white uppercase mb-2">Judul Kuis</label>
                            <input type="text" name="judul" x-model="selectedKuis.judul" required class="w-full bg-white dark:bg-slate-800 border dark:border-slate-700 rounded-lg h-10 px-4 text-sm dark:text-white">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 dark:text-white uppercase mb-2">Waktu (Menit)</label>
                            <input type="number" name="waktu_pengerjaan" x-model="selectedKuis.waktu_pengerjaan" required min="1" class="w-full bg-white dark:bg-slate-800 border dark:border-slate-700 rounded-lg h-10 px-4 text-sm dark:text-white">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 dark:text-white uppercase mb-2">Maks Percobaan</label>
                            <input type="number" name="ulang_post_test" x-model="selectedKuis.ulang_post_test" required min="1" class="w-full bg-white dark:bg-slate-800 border dark:border-slate-700 rounded-lg h-10 px-4 text-sm dark:text-white">
                        </div>
                    </div>

                    <div class="space-y-6">
                        <template x-for="(q, index) in selectedKuis.questions" :key="index">
                            <div class="bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-2xl overflow-hidden shadow-sm relative">
                                <div class="bg-slate-50 dark:bg-slate-800/30 px-6 py-3 border-b dark:border-slate-800 flex justify-between items-center">
                                    <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Pertanyaan #<span x-text="index + 1"></span></span>
                                    <button type="button" @click="selectedKuis.questions.splice(index, 1)" class="text-red-400 hover:text-red-600 transition"><i class="fa-solid fa-trash-can"></i></button>
                                </div>
                                <div class="p-6 space-y-6">
                                    <textarea :name="'questions['+index+'][soal]'" x-model="q.soal" required placeholder="Tuliskan pertanyaan..." class="w-full bg-white dark:bg-slate-800 border dark:border-slate-700 rounded-xl p-4 text-sm dark:text-white resize-none h-24"></textarea>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                        <div class="space-y-4">
                                            <div class="flex items-center justify-between">
                                                <label class="text-[10px] font-bold text-gray-400 uppercase">Pilihan Jawaban</label>
                                                <button type="button" @click="if(q.options.length < 5) q.options.push('')" class="text-[10px] font-bold text-emerald-600">+ Tambah Opsi</button>
                                            </div>
                                            <div class="space-y-3">
                                                <template x-for="(opt, oIndex) in q.options" :key="oIndex">
                                                    <div class="flex items-center gap-3">
                                                        <div class="w-6 h-6 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-[10px] font-bold text-gray-400" x-text="String.fromCharCode(65 + oIndex)"></div>
                                                        <input type="text" :name="'questions['+index+'][options][]'" x-model="q.options[oIndex]" required class="flex-1 bg-white dark:bg-slate-800 border dark:border-slate-700 rounded-lg h-9 px-3 text-xs dark:text-white">
                                                        <button type="button" @click="if(q.options.length > 2) q.options.splice(oIndex, 1)" class="text-gray-300 hover:text-red-400 transition text-[10px]"><i class="fa-solid fa-xmark"></i></button>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                        <div class="space-y-6">
                                            <div class="flex items-center gap-4 bg-slate-50 dark:bg-slate-800/50 p-2 rounded-lg border dark:border-slate-800">
                                                <button type="button" @click="q.status_pilihan = 0" :class="q.status_pilihan === 0 ? 'bg-white dark:bg-slate-700 text-emerald-600' : 'text-gray-400'" class="flex-1 py-1.5 rounded-md text-[10px] font-bold uppercase">Single</button>
                                                <button type="button" @click="q.status_pilihan = 1" :class="q.status_pilihan === 1 ? 'bg-white dark:bg-slate-700 text-emerald-600' : 'text-gray-400'" class="flex-1 py-1.5 rounded-md text-[10px] font-bold uppercase">Multiple</button>
                                                <input type="hidden" :name="'questions['+index+'][status_pilihan]'" :value="q.status_pilihan">
                                            </div>
                                            <div>
                                                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Jawaban Benar</label>
                                                <input type="text" :name="'questions['+index+'][jawaban_benar]'" x-model="q.jawaban_benar" required class="w-full bg-white dark:bg-slate-800 border dark:border-slate-700 rounded-lg h-10 px-4 text-xs dark:text-white">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <button type="button" @click="selectedKuis.questions.push({ soal: '', options: ['', ''], status_pilihan: 0, jawaban_benar: '' })" class="w-full py-4 border-2 border-dashed border-gray-200 dark:border-slate-800 rounded-2xl text-gray-400 hover:text-blue-500 transition-all font-bold text-xs uppercase tracking-widest bg-white dark:bg-slate-900/50">
                        <i class="fa-solid fa-plus mr-2"></i> Tambah Pertanyaan Baru
                    </button>

                    <div class="flex flex-col sm:flex-row justify-end gap-3 pt-6 border-t dark:border-slate-800 sticky bottom-0 bg-white dark:bg-slate-900 py-4">
                        <button @click="openEditKuis = false" type="button" class="w-full sm:w-auto px-8 py-2.5 border dark:border-slate-700 text-gray-500 font-bold rounded-lg text-xs">Batal</button>
                        <button type="submit" class="w-full sm:w-auto px-8 py-2.5 bg-blue-600 text-white font-bold rounded-lg text-xs">Simpan Perubahan Kuis</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 10px; }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; }
</style>
@endsection