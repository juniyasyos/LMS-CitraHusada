@extends('components.layout')
@section('title', 'Manajemen Pelatihan')

@section('content')
    <div class="flex h-screen overflow-hidden bg-slate-50 dark:bg-slate-950 transition-colors duration-300"
        x-data="{ 
            openTambahFolder: false, 
            openEditFolder: false,
            sidebarOpen: false, 
            darkMode: localStorage.getItem('theme') === 'dark',
            selectedMateri: {
                id: '',
                judul: '',
                subjudul: '',
                deskripsi: '',
                jam_pelajaran: '',
                tanggal_upload: '',
                tanggal_selesai: '',
                kategori_id: '',
                unit_kerja_ids: [],
                jenis_tenaga_ids: []
            },
            editFolder(materi) {
                this.selectedMateri = materi;
                this.openEditFolder = true;
                // Re-initialize datepickers for edit modal
                this.$nextTick(() => {
                    initDatepickers();
                });
            }
        }">

        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-slate-900 border-r dark:border-slate-800 transition-transform duration-300 lg:translate-x-0 lg:static lg:inset-0 shrink-0 overflow-y-auto">
            @include('components.nav-superadmin')
        </aside>

        <div x-show="sidebarOpen" @click="sidebarOpen = false"
            x-transition:enter="transition opacity-100 ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:leave="transition opacity-100 ease-in duration-200" x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-40 bg-black/50 backdrop-blur-sm lg:hidden" x-cloak></div>

        <div class="flex-1 flex flex-col min-w-0 transition-colors duration-300">
            <header
                class="bg-white dark:bg-slate-900 border-b dark:border-slate-800 h-16 flex items-center justify-between px-4 lg:px-8 shrink-0 z-10 transition-colors duration-300">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = true" class="lg:hidden p-2 text-gray-500 dark:text-white"><i
                            class="fa-solid fa-bars text-lg"></i></button>
                    <h1 class="text-sm font-semibold text-gray-600 dark:text-white truncate">Manajemen Pelatihan</h1>
                </div>
                <div class="flex items-center gap-3 lg:gap-4">
                    @include('components.notif-superadmin')
                    <div class="flex items-center gap-3 pl-2 lg:pl-4 border-l border-gray-100 dark:border-slate-800">
                        <div class="text-right hidden sm:block">
                            <p class="text-xs font-bold text-gray-800 dark:text-white leading-tight">Superadmin</p>
                            <p class="text-[10px] text-gray-500 dark:text-gray-300 font-medium italic">Utama</p>
                        </div>
                        <div
                            class="w-8 h-8 bg-gray-200 dark:bg-slate-700 rounded-full overflow-hidden border border-gray-100 dark:border-slate-800 flex items-center justify-center">
                            <i class="fa-solid fa-user text-gray-500 dark:text-white text-xs"></i>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-4 lg:p-8 custom-scrollbar">
                <div class="flex flex-col sm:flex-row justify-between items-start gap-4 mb-6">
                    <div>
                        <h2 class="text-lg lg:text-xl font-bold text-gray-800 dark:text-white transition-colors">Daftar
                            Pelatihan Aktif</h2>
                        <p class="text-xs lg:text-sm text-gray-500 dark:text-gray-300 mt-1 leading-relaxed">Kelola media
                            pelatihan rumah sakit untuk penugasan yang tepat.</p>
                    </div>
                    <div class="flex flex-wrap items-center gap-3">
                        <a href="{{ route('pelatihan.arsip') }}"
                            class="bg-amber-500 hover:bg-amber-600 text-white px-4 py-2.5 rounded-lg flex items-center gap-2 text-xs font-bold transition shadow-sm active:scale-95">
                            <i class="fa-solid fa-box-archive text-xs"></i> Arsip Pelatihan
                        </a>
                        <a href="{{ route('pelatihan.trash') }}"
                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2.5 rounded-lg flex items-center gap-2 text-xs font-bold transition shadow-sm active:scale-95">
                            <i class="fa-solid fa-trash text-xs"></i> Sampah
                        </a>
                        <button @click="openTambahFolder = true"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg flex items-center gap-2 text-xs font-bold transition shadow-sm active:scale-95">
                            <i class="fa-solid fa-plus text-xs"></i> Tambah Folder
                        </button>
                    </div>
                </div>

                @if(session('success'))
                    <div
                        class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-800 text-emerald-600 dark:text-emerald-400 text-xs font-bold rounded-xl flex items-center gap-3">
                        <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div
                        class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-100 dark:border-red-800 text-red-600 dark:text-red-400 text-xs font-bold rounded-xl flex items-center gap-3">
                        <i class="fa-solid fa-circle-xmark"></i> {{ session('error') }}
                    </div>
                @endif

                <div
                    class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 p-4 lg:p-6 mb-10 transition-colors duration-300">
                    <form action="{{ route('manajemen-pelatihan') }}" method="GET" id="filterForm"
                        class="flex flex-col md:flex-row justify-between items-center gap-4 mb-8">
                        <div class="w-full md:w-auto relative">
                            <select name="sort" onchange="this.form.submit()"
                                class="appearance-none w-full md:w-auto bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg px-4 py-2 pr-10 text-xs font-medium text-gray-600 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 cursor-pointer transition-all">
                                <option value="terbaru" {{ $sort == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                                <option value="terlama" {{ $sort == 'terlama' ? 'selected' : '' }}>Terlama</option>
                            </select>
                            <div
                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-400 dark:text-white">
                                <i class="fa-solid fa-chevron-down text-[10px]"></i></div>
                        </div>
                        <div class="relative w-full md:w-64">
                            <span class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 dark:text-white"><i
                                    class="fa-solid fa-magnifying-glass text-xs"></i></span>
                            <input type="text" name="search" id="searchMateri" value="{{ $search }}"
                                class="block w-full pl-4 pr-10 py-2 border border-gray-200 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 text-xs transition-all placeholder:dark:text-gray-400"
                                placeholder="Cari pelatihan...">
                        </div>
                    </form>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-6">
                        @forelse($materis as $materi)
                            <div class="relative group" x-data="{ menuOpen: false }">

                                <a href="{{ url('/daftar-materi-kuis/' . $materi->materi_id) }}"
                                    class="block border border-gray-100 dark:border-slate-800 rounded-xl overflow-hidden hover:shadow-md transition-all active:scale-[0.98] bg-white dark:bg-slate-900">

                                    <div
                                        class="p-6 flex items-center justify-center bg-gray-50 dark:bg-slate-800/50 group-hover:bg-gray-100 dark:group-hover:bg-slate-800 transition-colors">
                                        <i
                                            class="fa-solid fa-folder text-amber-400 text-6xl lg:text-7xl group-hover:text-amber-500 transition-colors"></i>
                                    </div>

                                    <div class="p-4 text-center">
                                        <p class="text-xs font-bold text-gray-700 dark:text-white mb-1 truncate px-2">
                                            {{ $materi->judul }}
                                        </p>
                                        <p class="text-[10px] text-gray-400 dark:text-gray-500 font-medium italic">
                                            {{ $materi->created_at->format('d M Y') }}
                                        </p>

                                        @if($materi->kategori)
                                            <span
                                                class="inline-block mt-2 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 px-2 py-0.5 rounded text-[9px] font-bold">
                                                {{ $materi->kategori->nama_kategori }}
                                            </span>
                                        @else
                                            <span
                                                class="inline-block mt-2 bg-gray-50 dark:bg-slate-800 text-gray-400 dark:text-gray-500 px-2 py-0.5 rounded text-[9px] font-medium italic border border-gray-100 dark:border-slate-700">
                                                Tidak ada kategori
                                            </span>
                                        @endif
                                    </div>
                                </a>

                                <div class="absolute top-2 right-2 z-20">
                                    <button @click.prevent="menuOpen = !menuOpen"
                                        class="w-7 h-7 rounded-full bg-white/90 dark:bg-slate-800/90 shadow-sm flex items-center justify-center text-gray-500 dark:text-white hover:bg-white dark:hover:bg-slate-700 transition text-xs border border-gray-100 dark:border-slate-700">
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </button>

                                    <div x-show="menuOpen" @click.away="menuOpen = false" x-cloak x-transition
                                        class="absolute right-0 mt-1 w-40 bg-white dark:bg-slate-900 rounded-lg shadow-xl border border-gray-100 dark:border-slate-800 py-1 z-30 overflow-hidden">

                                        {{-- BUTTON EDIT (MENGGANTIKAN MASUK ARSIP) --}}
                                        <button type="button"
                                            @click="editFolder({
                                                id: '{{ $materi->materi_id }}',
                                                judul: '{{ addslashes($materi->judul) }}',
                                                subjudul: '{{ addslashes($materi->subjudul) }}',
                                                deskripsi: '{{ addslashes($materi->deskripsi) }}',
                                                jam_pelajaran: '{{ $materi->jam_pelajaran }}',
                                                tanggal_upload: '{{ $materi->tanggal_upload }}',
                                                tanggal_selesai: '{{ $materi->tanggal_selesai }}',
                                                kategori_id: '{{ $materi->kategori_id }}',
                                                unit_kerja_ids: {{ json_encode($materi->unitKerjas->pluck('unit_kerja_id')) }},
                                                jenis_tenaga_ids: {{ json_encode($materi->jenisTenagas->pluck('jenis_tenaga_id')) }}
                                            })"
                                            class="w-full text-left px-4 py-2 text-xs text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors font-bold">
                                            <i class="fa-solid fa-pen mr-2"></i>Edit Folder
                                        </button>

                                        <form action="{{ route('pelatihan.destroy', $materi->materi_id) }}" method="POST"
                                            onsubmit="return confirm('Pindahkan ke sampah?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="w-full text-left px-4 py-2 text-xs text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                                <i class="fa-solid fa-trash-can mr-2"></i>Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full py-16 text-center">
                                <i class="fa-solid fa-folder-open text-5xl text-gray-200 dark:text-slate-700 mb-4"></i>
                                <p class="text-sm text-gray-400 dark:text-gray-500 font-medium">Belum ada data pelatihan.</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-8">{{ $materis->links() }}</div>
                </div>
            </main>
        </div>

        {{-- MODAL TAMBAH FOLDER --}}
        <div x-show="openTambahFolder"
            class="fixed inset-0 z-60 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" x-cloak>

            <div @click.away="openTambahFolder = false"
                class="bg-white dark:bg-slate-900 w-full max-w-4xl rounded-2xl shadow-2xl overflow-hidden transition-colors duration-300">
                <div
                    class="flex justify-between items-center px-6 lg:px-8 py-5 border-b border-gray-100 dark:border-slate-800">
                    <h2 class="text-base font-bold text-gray-800 dark:text-white">Tambah Pelatihan</h2>
                    <button @click="openTambahFolder = false"
                        class="text-gray-400 hover:text-gray-800 dark:hover:text-white transition"><i
                            class="fa-solid fa-xmark text-lg"></i></button>
                </div>

                <div class="p-6 lg:p-8 max-h-[80vh] overflow-y-auto custom-scrollbar">
                    <form action="{{ route('pelatihan.store') }}" method="POST" enctype="multipart/form-data"
                        class="space-y-5">
                        @csrf
                        <div>
                            <label
                                class="block text-xs font-bold text-gray-500 dark:text-white mb-2 uppercase tracking-tight">Nama
                                Materi <span class="text-red-500">*</span></label>
                            <input type="text" name="judul" value="{{ old('judul') }}" required
                                placeholder="Contoh: Protokol Keselamatan Radiasi"
                                class="w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg h-12 px-4 focus:ring-2 focus:ring-blue-500/10 outline-none transition-all text-sm text-gray-700 dark:text-white">
                            @error('judul') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label
                                class="block text-xs font-bold text-gray-500 dark:text-white mb-2 uppercase tracking-tight">Sub
                                Judul</label>
                            <input type="text" name="subjudul" value="{{ old('subjudul') }}"
                                placeholder="Sub judul opsional"
                                class="w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg h-12 px-4 focus:ring-2 focus:ring-blue-500/10 outline-none transition-all text-sm text-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label
                                class="block text-xs font-bold text-gray-500 dark:text-white mb-2 uppercase tracking-tight">Deskripsi
                                Pelatihan</label>
                            <textarea name="deskripsi" rows="3" placeholder="Keterangan singkat..."
                                class="w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg p-4 focus:ring-2 focus:ring-blue-500/10 outline-none transition-all text-sm text-gray-700 dark:text-white resize-none">{{ old('deskripsi') }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label
                                    class="block text-xs font-bold text-gray-500 dark:text-white mb-2 uppercase tracking-tight">JPL
                                    <span class="text-red-500">*</span></label>
                                <input type="number" name="jam_pelajaran" value="{{ old('jam_pelajaran') }}" required
                                    min="1" placeholder="3"
                                    class="w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg h-12 px-4 text-sm dark:text-white outline-none focus:ring-2 focus:ring-blue-500/10">
                                @error('jam_pelajaran') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}
                                </p> @enderror
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-bold text-gray-500 dark:text-white mb-2 uppercase tracking-tight">Tanggal
                                    Mulai <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <input type="text" name="tanggal_upload" id="tanggal_upload"
                                        value="{{ old('tanggal_upload') }}" required
                                        class="datepicker w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg h-12 px-4 pl-10 text-sm dark:text-white outline-none focus:ring-2 focus:ring-blue-500/10">
                                    <i
                                        class="fa-regular fa-calendar absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                                </div>
                                @error('tanggal_upload') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}
                                </p> @enderror
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-bold text-gray-500 dark:text-white mb-2 uppercase tracking-tight">Tanggal
                                    Selesai <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <input type="text" name="tanggal_selesai" id="tanggal_selesai"
                                        value="{{ old('tanggal_selesai') }}" required
                                        class="datepicker w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg h-12 px-4 pl-10 text-sm dark:text-white outline-none focus:ring-2 focus:ring-blue-500/10">
                                    <i
                                        class="fa-regular fa-calendar absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                                </div>
                                @error('tanggal_selesai') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}
                                </p> @enderror
                            </div>
                        </div>

                        <div>
                            <label
                                class="block text-xs font-bold text-gray-500 dark:text-white mb-2 uppercase tracking-tight">Kategori
                                <span class="text-red-500">*</span></label>
                            <select name="kategori_id" required
                                class="w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg h-12 px-4 text-sm dark:text-white outline-none focus:ring-2 focus:ring-blue-500/10 cursor-pointer">
                                <option disabled selected>Pilih Kategori</option>
                                @foreach($kategoris as $kat)
                                    <option value="{{ $kat->kategori_id }}" {{ old('kategori_id') == $kat->kategori_id ? 'selected' : '' }}>{{ $kat->nama_kategori }}</option>
                                @endforeach
                            </select>
                            @error('kategori_id') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label
                                    class="block text-xs font-bold text-gray-500 dark:text-white mb-2 uppercase tracking-tight">Unggah
                                    Thumbnail <span class="text-gray-400">(maks 3MB)</span></label>
                                <div class="relative border-2 border-dashed border-gray-200 dark:border-slate-700 rounded-xl p-6 flex flex-col items-center justify-center bg-slate-50/50 dark:bg-slate-800/50 hover:bg-slate-50 transition-colors cursor-pointer"
                                    x-data="{ fileName: '' }">
                                    <input type="file" name="thumbnail" accept="image/*"
                                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                        @change="fileName = $event.target.files[0]?.name || ''">
                                    <i class="fa-solid fa-image text-blue-500 text-2xl mb-2"></i>
                                    <p class="text-[11px] font-bold text-gray-700 dark:text-white"
                                        x-text="fileName || 'Klik untuk upload'"></p>
                                    <p class="text-[9px] text-gray-400 mt-1">JPG, PNG, WEBP</p>
                                </div>
                                @error('thumbnail') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label
                                    class="block text-xs font-bold text-gray-500 dark:text-white mb-2 uppercase tracking-tight">Unit
                                    Kerja Terkait</label>
                                <div
                                    class="border border-gray-200 dark:border-slate-700 rounded-xl p-4 grid grid-cols-2 gap-2 bg-gray-50/30 dark:bg-slate-800/30 max-h-40 overflow-y-auto custom-scrollbar">
                                    @foreach($unitKerjas as $uk)
                                        <label class="flex items-center gap-2 cursor-pointer group">
                                            <input type="checkbox" name="unit_kerja_ids[]" value="{{ $uk->unit_kerja_id }}"
                                                class="w-4 h-4 rounded border-gray-300 dark:border-slate-600 text-blue-600" {{ is_array(old('unit_kerja_ids')) && in_array($uk->unit_kerja_id, old('unit_kerja_ids')) ? 'checked' : '' }}>
                                            <span
                                                class="text-[11px] font-bold text-gray-600 dark:text-gray-300 group-hover:text-gray-900 transition-colors">{{ $uk->unit_kerja }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div>
                            <label
                                class="block text-xs font-bold text-gray-500 dark:text-white mb-2 uppercase tracking-tight">Jenis
                                Tenaga Terkait</label>
                            <div
                                class="border border-gray-200 dark:border-slate-700 rounded-xl p-4 grid grid-cols-2 md:grid-cols-3 gap-2 bg-gray-50/30 dark:bg-slate-800/30 max-h-40 overflow-y-auto custom-scrollbar">
                                @foreach($jenisTenagas as $jt)
                                    <label class="flex items-center gap-2 cursor-pointer group">
                                        <input type="checkbox" name="jenis_tenaga_ids[]" value="{{ $jt->jenis_tenaga_id }}"
                                            class="w-4 h-4 rounded border-gray-300 dark:border-slate-600 text-blue-600" {{ is_array(old('jenis_tenaga_ids')) && in_array($jt->jenis_tenaga_id, old('jenis_tenaga_ids')) ? 'checked' : '' }}>
                                        <span
                                            class="text-[11px] font-bold text-gray-600 dark:text-gray-300 group-hover:text-gray-900 transition-colors">{{ $jt->jenis_tenaga }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4">
                            <button @click="openTambahFolder = false" type="button"
                                class="w-full sm:w-auto px-8 py-2.5 border dark:border-slate-700 text-gray-500 dark:text-white font-bold rounded-lg hover:bg-gray-50 dark:hover:bg-slate-800 transition text-xs">Batal</button>
                            <button type="submit"
                                class="w-full sm:w-auto px-8 py-2.5 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 shadow-lg shadow-blue-100 dark:shadow-none transition text-xs active:scale-95">Simpan
                                Pelatihan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- MODAL EDIT FOLDER --}}
        <div x-show="openEditFolder"
            class="fixed inset-0 z-60 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" x-cloak>

            <div @click.away="openEditFolder = false"
                class="bg-white dark:bg-slate-900 w-full max-w-4xl rounded-2xl shadow-2xl overflow-hidden transition-colors duration-300">
                <div
                    class="flex justify-between items-center px-6 lg:px-8 py-5 border-b border-gray-100 dark:border-slate-800">
                    <h2 class="text-base font-bold text-gray-800 dark:text-white">Edit Pelatihan</h2>
                    <button @click="openEditFolder = false"
                        class="text-gray-400 hover:text-gray-800 dark:hover:text-white transition"><i
                            class="fa-solid fa-xmark text-lg"></i></button>
                </div>

                <div class="p-6 lg:p-8 max-h-[80vh] overflow-y-auto custom-scrollbar">
                    <form :action="'{{ route('manajemen-pelatihan') }}/' + selectedMateri.id" method="POST" enctype="multipart/form-data"
                        class="space-y-5">
                        @csrf
                        @method('PUT')
                        <div>
                            <label
                                class="block text-xs font-bold text-gray-500 dark:text-white mb-2 uppercase tracking-tight">Nama
                                Materi <span class="text-red-500">*</span></label>
                            <input type="text" name="judul" x-model="selectedMateri.judul" required
                                placeholder="Contoh: Protokol Keselamatan Radiasi"
                                class="w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg h-12 px-4 focus:ring-2 focus:ring-blue-500/10 outline-none transition-all text-sm text-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label
                                class="block text-xs font-bold text-gray-500 dark:text-white mb-2 uppercase tracking-tight">Sub
                                Judul</label>
                            <input type="text" name="subjudul" x-model="selectedMateri.subjudul"
                                placeholder="Sub judul opsional"
                                class="w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg h-12 px-4 focus:ring-2 focus:ring-blue-500/10 outline-none transition-all text-sm text-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label
                                class="block text-xs font-bold text-gray-500 dark:text-white mb-2 uppercase tracking-tight">Deskripsi
                                Pelatihan</label>
                            <textarea name="deskripsi" rows="3" x-model="selectedMateri.deskripsi" placeholder="Keterangan singkat..."
                                class="w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg p-4 focus:ring-2 focus:ring-blue-500/10 outline-none transition-all text-sm text-gray-700 dark:text-white resize-none"></textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label
                                    class="block text-xs font-bold text-gray-500 dark:text-white mb-2 uppercase tracking-tight">JPL
                                    <span class="text-red-500">*</span></label>
                                <input type="number" name="jam_pelajaran" x-model="selectedMateri.jam_pelajaran" required
                                    min="1" placeholder="3"
                                    class="w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg h-12 px-4 text-sm dark:text-white outline-none focus:ring-2 focus:ring-blue-500/10">
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-bold text-gray-500 dark:text-white mb-2 uppercase tracking-tight">Tanggal
                                    Mulai <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <input type="text" name="tanggal_upload" x-model="selectedMateri.tanggal_upload" required
                                        class="datepicker w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg h-12 px-4 pl-10 text-sm dark:text-white outline-none focus:ring-2 focus:ring-blue-500/10">
                                    <i
                                        class="fa-regular fa-calendar absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                                </div>
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-bold text-gray-500 dark:text-white mb-2 uppercase tracking-tight">Tanggal
                                    Selesai <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <input type="text" name="tanggal_selesai" x-model="selectedMateri.tanggal_selesai" required
                                        class="datepicker w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg h-12 px-4 pl-10 text-sm dark:text-white outline-none focus:ring-2 focus:ring-blue-500/10">
                                    <i
                                        class="fa-regular fa-calendar absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label
                                class="block text-xs font-bold text-gray-500 dark:text-white mb-2 uppercase tracking-tight">Kategori
                                <span class="text-red-500">*</span></label>
                            <select name="kategori_id" x-model="selectedMateri.kategori_id" required
                                class="w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg h-12 px-4 text-sm dark:text-white outline-none focus:ring-2 focus:ring-blue-500/10 cursor-pointer">
                                <option disabled selected>Pilih Kategori</option>
                                @foreach($kategoris as $kat)
                                    <option value="{{ $kat->kategori_id }}">{{ $kat->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label
                                    class="block text-xs font-bold text-gray-500 dark:text-white mb-2 uppercase tracking-tight">Unggah
                                    Thumbnail Baru <span class="text-gray-400">(opsional)</span></label>
                                <div class="relative border-2 border-dashed border-gray-200 dark:border-slate-700 rounded-xl p-6 flex flex-col items-center justify-center bg-slate-50/50 dark:bg-slate-800/50 hover:bg-slate-50 transition-colors cursor-pointer"
                                    x-data="{ fileName: '' }">
                                    <input type="file" name="thumbnail" accept="image/*"
                                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                        @change="fileName = $event.target.files[0]?.name || ''">
                                    <i class="fa-solid fa-image text-blue-500 text-2xl mb-2"></i>
                                    <p class="text-[11px] font-bold text-gray-700 dark:text-white"
                                        x-text="fileName || 'Klik untuk ganti thumbnail'"></p>
                                    <p class="text-[9px] text-gray-400 mt-1">JPG, PNG, WEBP</p>
                                </div>
                            </div>

                            <div>
                                <label
                                    class="block text-xs font-bold text-gray-500 dark:text-white mb-2 uppercase tracking-tight">Unit
                                    Kerja Terkait</label>
                                <div
                                    class="border border-gray-200 dark:border-slate-700 rounded-xl p-4 grid grid-cols-2 gap-2 bg-gray-50/30 dark:bg-slate-800/30 max-h-40 overflow-y-auto custom-scrollbar">
                                    @foreach($unitKerjas as $uk)
                                        <label class="flex items-center gap-2 cursor-pointer group">
                                            <input type="checkbox" name="unit_kerja_ids[]" value="{{ $uk->unit_kerja_id }}"
                                                :checked="selectedMateri.unit_kerja_ids.includes({{ $uk->unit_kerja_id }})"
                                                class="w-4 h-4 rounded border-gray-300 dark:border-slate-600 text-blue-600">
                                            <span
                                                class="text-[11px] font-bold text-gray-600 dark:text-gray-300 group-hover:text-gray-900 transition-colors">{{ $uk->unit_kerja }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div>
                            <label
                                class="block text-xs font-bold text-gray-500 dark:text-white mb-2 uppercase tracking-tight">Jenis
                                Tenaga Terkait</label>
                            <div
                                class="border border-gray-200 dark:border-slate-700 rounded-xl p-4 grid grid-cols-2 md:grid-cols-3 gap-2 bg-gray-50/30 dark:bg-slate-800/30 max-h-40 overflow-y-auto custom-scrollbar">
                                @foreach($jenisTenagas as $jt)
                                    <label class="flex items-center gap-2 cursor-pointer group">
                                        <input type="checkbox" name="jenis_tenaga_ids[]" value="{{ $jt->jenis_tenaga_id }}"
                                            :checked="selectedMateri.jenis_tenaga_ids.includes({{ $jt->jenis_tenaga_id }})"
                                            class="w-4 h-4 rounded border-gray-300 dark:border-slate-600 text-blue-600">
                                        <span
                                            class="text-[11px] font-bold text-gray-600 dark:text-gray-300 group-hover:text-gray-900 transition-colors">{{ $jt->jenis_tenaga }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4">
                            <button @click="openEditFolder = false" type="button"
                                class="w-full sm:w-auto px-8 py-2.5 border dark:border-slate-700 text-gray-500 dark:text-white font-bold rounded-lg hover:bg-gray-50 dark:hover:bg-slate-800 transition text-xs">Batal</button>
                            <button type="submit"
                                class="w-full sm:w-auto px-8 py-2.5 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 shadow-lg shadow-blue-100 dark:shadow-none transition text-xs active:scale-95">Perbarui
                                Pelatihan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/dark.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
        function initDatepickers() {
            flatpickr(".datepicker", {
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "d F Y",
                theme: document.documentElement.classList.contains('dark') ? 'dark' : 'light'
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            initDatepickers();

            let searchInput = document.getElementById('searchMateri');
            let timeout = null;
            if (searchInput) {
                searchInput.addEventListener('input', function () {
                    clearTimeout(timeout);
                    timeout = setTimeout(function () {
                        document.getElementById('filterForm').submit();
                    }, 1000);
                });
            }
        });
    </script>

    <style>
        [x-cloak] { display: none !important; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 10px; }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; }
    </style>
@endsection