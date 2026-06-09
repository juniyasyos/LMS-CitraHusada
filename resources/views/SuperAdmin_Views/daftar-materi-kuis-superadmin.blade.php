@extends('components.layout')
@section('title', 'Detail Materi & Kuis')

@section('content')
    <div class="flex h-screen overflow-hidden bg-slate-50 dark:bg-slate-950 transition-colors duration-300"
        x-data="daftarMateriKuisData({{ $materiId }}, {{ isset($readOnly) && $readOnly ? 'true' : 'false' }})" x-init="initData()">

        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-slate-900 border-r dark:border-slate-800 transition-transform duration-300 lg:translate-x-0 lg:static lg:inset-0 shrink-0 overflow-y-auto">
            @include('components.nav-superadmin')
        </aside>

        <div x-show="sidebarOpen" @click="sidebarOpen = false"
            x-transition:enter="transition opacity-100 ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:leave="transition opacity-100 ease-in duration-200" x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-40 bg-black/50 backdrop-blur-sm lg:hidden" x-cloak></div>

        <div class="flex-1 flex flex-col min-w-0 transition-colors duration-300">
            @include('components.header-superadmin', ['title' => 'Daftar materi dan kuis'])

            <main class="flex-1 overflow-y-auto p-4 lg:p-8 custom-scrollbar">
                {{-- Breadcrumb --}}
                <nav class="mb-6 text-[14px] font-medium">
                    <ol class="flex items-center gap-2 text-gray-500 dark:text-gray-400">
                        @if(isset($readOnly) && $readOnly)
                            <li><a href="{{ route('pelatihan.arsip') }}"
                                    class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Arsip</a>
                            </li>
                        @else
                            <li><a href="{{ route('manajemen-pelatihan') }}"
                                    class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Manajemen Media</a>
                            </li>
                        @endif
                        <li class="flex items-center gap-2"><span class="text-gray-300 dark:text-gray-600"> > </span><span
                                class="text-gray-800 dark:text-white font-semibold">Daftar materi dan kuis</span></li>
                    </ol>
                </nav>

                <div class="flex flex-col sm:flex-row justify-between items-start gap-4 mb-8">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800 dark:text-white" x-text="materiInfo.judul"></h2>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1"
                            x-text="materiInfo.subjudul || 'Manajemen Konten Pembelajaran'"></p>
                    </div>
                    <div class="flex flex-wrap items-center gap-3" x-show="!readOnly">
                        <button @click="openTambahKuisModal()"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg flex items-center gap-2 text-xs font-bold transition shadow-sm active:scale-95">
                            <i class="fa-solid fa-vial"></i> Tambah Kuis
                        </button>
                        <button @click="openTambahMateriModal()"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg flex items-center gap-2 text-xs font-bold transition shadow-sm active:scale-95">
                            <i class="fa-solid fa-file-arrow-up"></i> Tambah Materi
                        </button>
                    </div>
                </div>

                {{-- Combined Table --}}
                <div
                    class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 overflow-hidden mb-10 transition-colors">
                    <div x-show="isLoading" class="flex justify-center items-center py-20">
                        <i class="fa-solid fa-spinner fa-spin text-3xl text-blue-500"></i>
                    </div>

                    <table x-show="!isLoading" class="w-full text-left text-xs min-w-[800px]">
                        <thead
                            class="text-gray-500 dark:text-white font-bold border-b dark:border-slate-800 bg-gray-50/50 dark:bg-slate-800/50">
                            <tr>
                                <th class="py-4 px-6 uppercase tracking-wider">No</th>
                                <th class="py-4 px-6 uppercase tracking-wider">Daftar materi dan kuis</th>
                                <th class="py-4 px-6 uppercase tracking-wider text-center">Jumlah Pengerjaan</th>
                                <th class="py-4 px-6 uppercase tracking-wider" x-show="!readOnly">Keterangan</th>
                                <th class="py-4 px-6 uppercase tracking-wider text-right" x-show="!readOnly">Aksi</th>
                            </tr>
                        </thead>
                        <tbody
                            class="divide-y divide-gray-100 dark:divide-slate-800 text-gray-700 dark:text-white transition-colors">
                            <template x-for="(item, index) in contents" :key="index">
                                <tr class="hover:bg-gray-50 dark:hover:bg-slate-800 transition">
                                    <td class="py-5 px-6 font-bold text-gray-400 dark:text-gray-500" x-text="index + 1">
                                    </td>
                                    <td class="py-5 px-6">
                                        <div class="flex items-center gap-4">
                                            <div :class="item.type === 'materi' ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400' : 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400'"
                                                class="w-10 h-10 rounded-lg flex items-center justify-center shrink-0">
                                                <i class="fa-solid"
                                                    :class="item.type === 'materi' ? 'fa-file-lines' : 'fa-clipboard-question'"></i>
                                            </div>
                                            <div>
                                                <p class="font-bold text-gray-800 dark:text-white text-sm"
                                                    x-text="item.judul"></p>
                                                <div class="flex items-center gap-2 mt-0.5">
                                                    <p class="text-[10px] uppercase font-bold tracking-widest"
                                                        :class="item.type === 'materi' ? 'text-blue-500' : 'text-emerald-500'"
                                                        x-text="item.type"></p>
                                                    <template x-if="item.type === 'kuis' && item.pretest">
                                                        <span class="bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 px-1.5 py-0.5 rounded text-[8px] font-bold uppercase tracking-wider">
                                                            Pretest
                                                        </span>
                                                    </template>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-5 px-6 text-center">
                                        <span
                                            class="bg-gray-100 dark:bg-slate-800 px-3 py-1.5 rounded-full font-bold text-[10px]">
                                            <i class="fa-solid fa-users mr-1 opacity-50"></i> <span
                                                x-text="item.jumlah_pengerjaan"></span> User
                                        </span>
                                    </td>
                                    <td class="py-5 px-6 text-gray-500 dark:text-gray-400 max-w-xs truncate italic" x-show="!readOnly"
                                        x-text="item.type === 'materi' ? (item.deskripsi || 'Materi Pembelajaran') : '-'">
                                    </td>
                                    <td class="py-5 px-6 text-right" x-show="!readOnly">
                                        <div class="flex justify-end gap-4">
                                            <template x-if="item.type === 'materi'">
                                                <div class="flex gap-4">
                                                    <button @click="prepareEditMateri(item)"
                                                        class="hover:bg-blue-50 dark:hover:bg-blue-900/20 text-gray-500 rounded-lg transition-all active:scale-90"
                                                        title="Edit Materi">
                                                        <i class="fa-solid fa-pen"></i>
                                                    </button>
                                                    <button @click="deleteSubMateri(item.sub_materi_id)"
                                                        class="hover:bg-red-50 dark:hover:bg-red-900/20 text-gray-500 rounded-lg transition-all active:scale-90"><i
                                                            class="fa-solid fa-trash-can"></i></button>
                                                </div>
                                            </template>
                                            <template x-if="item.type === 'kuis'">
                                                <div class="flex gap-4">
                                                    <button @click="prepareEditKuis(item)"
                                                        class="hover:bg-emerald-50 dark:hover:bg-emerald-900/20 text-gray-500 rounded-lg transition-all active:scale-90"
                                                        title="Edit Kuis">
                                                        <i class="fa-solid fa-pen"></i>
                                                    </button>
                                                    <button @click="deletePostTest(item.post_test_id)"
                                                        class="hover:bg-red-50 dark:hover:bg-red-900/20 text-gray-500 rounded-lg transition-all active:scale-90"><i
                                                            class="fa-solid fa-trash-can"></i></button>
                                                </div>
                                            </template>
                                        </div>
                                    </td>
                                </tr>
                            </template>

                            <template x-if="contents.length === 0">
                                <tr>
                                    <td colspan="5" class="py-16 text-center text-gray-400 dark:text-gray-500 italic">
                                        Belum ada materi atau kuis di pelatihan ini.
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>

        {{-- MODAL TAMBAH MATERI --}}
        <div x-show="openTambahMateri"
            class="fixed inset-0 z-[60] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4" x-transition
            x-cloak>
            <div @click.away="openTambahMateri = false"
                class="bg-white dark:bg-slate-900 w-full max-w-2xl rounded-2xl shadow-2xl overflow-hidden transition-all duration-300">
                <div class="flex justify-between items-center px-8 py-5 border-b dark:border-slate-800">
                    <h2 class="text-base font-bold text-gray-800 dark:text-white">Tambah Materi Baru</h2>
                    <button @click="openTambahMateri = false"
                        class="text-gray-400 hover:text-gray-800 dark:hover:text-white transition"><i
                            class="fa-solid fa-xmark text-lg"></i></button>
                </div>
                <div class="p-8">
                    <form @submit.prevent="submitTambahSubMateri" class="space-y-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 dark:text-white mb-2 uppercase">Judul
                                Sub-Materi</label>
                            <input type="text" x-model="formTambahMateri.judul" required
                                placeholder="Contoh: Pengenalan Alat Medis"
                                class="w-full bg-white dark:bg-slate-800 border dark:border-slate-700 rounded-lg h-12 px-4 text-sm dark:text-white outline-none focus:ring-2 focus:ring-blue-500/20">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 dark:text-white mb-2 uppercase">Deskripsi
                                Singkat</label>
                            <textarea x-model="formTambahMateri.deskripsi" rows="3"
                                class="w-full bg-white dark:bg-slate-800 border dark:border-slate-700 rounded-lg p-4 text-sm dark:text-white resize-none outline-none focus:ring-2 focus:ring-blue-500/20"></textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 dark:text-white mb-2 uppercase">Unggah File Materi <span class="text-red-500">*</span></label>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mb-3">Pilih salah satu: Video (MP4, MOV) atau Dokumen (PDF, PPT)</p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <label
                                :class="formTambahMateri.uploadType === 'doc' ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer'"
                                class="relative border-2 border-dashed border-gray-200 dark:border-slate-700 rounded-xl p-6 flex flex-col items-center justify-center bg-slate-50/50 dark:bg-slate-800/50 hover:bg-slate-50 transition-colors">
                                <input type="file" accept="video/*" :disabled="formTambahMateri.uploadType === 'doc'"
                                    @change="formTambahMateri.file = $event.target.files[0]; formTambahMateri.uploadType = $event.target.files.length ? 'video' : null"
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                <i class="fa-solid fa-film text-blue-500 text-2xl mb-2"></i>
                                <p class="text-[11px] font-bold text-gray-700 dark:text-white uppercase w-full text-center break-all"
                                    x-text="formTambahMateri.uploadType === 'video' ? formTambahMateri.file.name : 'Upload Video'">
                                </p>
                                <p class="text-[9px] text-gray-400 mt-1">MP4, MOV (Max 10MB)</p>
                            </label>
                            <label
                                :class="formTambahMateri.uploadType === 'video' ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer'"
                                class="relative border-2 border-dashed border-gray-200 dark:border-slate-700 rounded-xl p-6 flex flex-col items-center justify-center bg-slate-50/50 dark:bg-slate-800/50 hover:bg-slate-50 transition-colors">
                                <input type="file" accept=".pdf,.ppt,.pptx"
                                    :disabled="formTambahMateri.uploadType === 'video'"
                                    @change="formTambahMateri.file = $event.target.files[0]; formTambahMateri.uploadType = $event.target.files.length ? 'doc' : null"
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                <i class="fa-solid fa-file-pdf text-red-500 text-2xl mb-2"></i>
                                <p class="text-[11px] font-bold text-gray-700 dark:text-white uppercase w-full text-center break-all"
                                    x-text="formTambahMateri.uploadType === 'doc' ? formTambahMateri.file.name : 'Upload Dokumen'">
                                </p>
                                <p class="text-[9px] text-gray-400 mt-1">PDF, PPT (Max 5MB)</p>
                            </label>
                        </div>

                        <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4">
                            <button @click="openTambahMateri = false" type="button"
                                class="w-full sm:w-auto px-8 py-2.5 border dark:border-slate-700 text-gray-500 dark:text-white font-bold rounded-lg hover:bg-gray-50 dark:hover:bg-slate-800 transition text-xs">Batal</button>
                            <button type="submit" :disabled="isSubmitting || !formTambahMateri.file"
                                :class="!formTambahMateri.file ? 'opacity-50 cursor-not-allowed' : ''"
                                class="w-full sm:w-auto px-8 py-2.5 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition text-xs active:scale-95">Unggah
                                Materi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- MODAL EDIT MATERI --}}
        <div x-show="openEditMateri"
            class="fixed inset-0 z-[60] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4" x-transition
            x-cloak>
            <div @click.away="openEditMateri = false"
                class="bg-white dark:bg-slate-900 w-full max-w-2xl rounded-2xl shadow-2xl overflow-hidden transition-all duration-300">
                <div class="flex justify-between items-center px-8 py-5 border-b dark:border-slate-800">
                    <h2 class="text-base font-bold text-gray-800 dark:text-white">Edit Materi</h2>
                    <button @click="openEditMateri = false"
                        class="text-gray-400 hover:text-gray-800 dark:hover:text-white transition"><i
                            class="fa-solid fa-xmark text-lg"></i></button>
                </div>
                <div class="p-8">
                    <form @submit.prevent="submitEditSubMateri" class="space-y-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 dark:text-white mb-2 uppercase">Judul
                                Sub-Materi</label>
                            <input type="text" x-model="selectedMateri.judul" required
                                class="w-full bg-white dark:bg-slate-800 border dark:border-slate-700 rounded-lg h-12 px-4 text-sm dark:text-white outline-none focus:ring-2 focus:ring-blue-500/20">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 dark:text-white mb-2 uppercase">Deskripsi
                                Singkat</label>
                            <textarea x-model="selectedMateri.deskripsi" rows="3"
                                class="w-full bg-white dark:bg-slate-800 border dark:border-slate-700 rounded-lg p-4 text-sm dark:text-white resize-none outline-none focus:ring-2 focus:ring-blue-500/20"></textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 dark:text-white mb-2 uppercase">Ganti File Materi <span class="text-gray-400 font-normal">(Opsional)</span></label>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mb-3">Pilih file baru jika ingin mengganti materi</p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <label
                                :class="selectedMateri.uploadType === 'doc' ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer'"
                                class="relative border-2 border-dashed border-gray-200 dark:border-slate-700 rounded-xl p-6 flex flex-col items-center justify-center bg-slate-50/50 dark:bg-slate-800/50 hover:bg-slate-50 transition-colors">
                                <input type="file" accept="video/*" :disabled="selectedMateri.uploadType === 'doc'"
                                    @change="selectedMateri.file = $event.target.files[0]; selectedMateri.uploadType = $event.target.files.length ? 'video' : null"
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                <i class="fa-solid fa-film text-blue-500 text-2xl mb-2"></i>
                                <p class="text-[11px] font-bold text-gray-700 dark:text-white uppercase w-full text-center break-all"
                                    x-text="selectedMateri.uploadType === 'video' ? selectedMateri.file.name : 'Ganti Video'">
                                </p>
                                <p class="text-[9px] text-gray-400 mt-1">Kosongkan jika tidak ingin ganti</p>
                            </label>
                            <label
                                :class="selectedMateri.uploadType === 'video' ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer'"
                                class="relative border-2 border-dashed border-gray-200 dark:border-slate-700 rounded-xl p-6 flex flex-col items-center justify-center bg-slate-50/50 dark:bg-slate-800/50 hover:bg-slate-50 transition-colors">
                                <input type="file" accept=".pdf,.ppt,.pptx"
                                    :disabled="selectedMateri.uploadType === 'video'"
                                    @change="selectedMateri.file = $event.target.files[0]; selectedMateri.uploadType = $event.target.files.length ? 'doc' : null"
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                <i class="fa-solid fa-file-pdf text-red-500 text-2xl mb-2"></i>
                                <p class="text-[11px] font-bold text-gray-700 dark:text-white uppercase w-full text-center break-all"
                                    x-text="selectedMateri.uploadType === 'doc' ? selectedMateri.file.name : 'Ganti Dokumen'">
                                </p>
                                <p class="text-[9px] text-gray-400 mt-1">Kosongkan jika tidak ingin ganti</p>
                            </label>
                        </div>

                        <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4">
                            <button @click="openEditMateri = false" type="button"
                                class="w-full sm:w-auto px-8 py-2.5 border dark:border-slate-700 text-gray-500 dark:text-white font-bold rounded-lg hover:bg-gray-50 dark:hover:bg-slate-800 transition text-xs">Batal</button>
                            <button type="submit" :disabled="isSubmitting"
                                class="w-full sm:w-auto px-8 py-2.5 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition text-xs active:scale-95">Perbarui
                                Materi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- MODAL TAMBAH KUIS --}}
        <div x-show="openTambahKuis"
            class="fixed inset-0 z-[60] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4" x-transition
            x-cloak>
            <div
                class="bg-white dark:bg-slate-900 w-full max-w-4xl rounded-2xl shadow-2xl overflow-hidden transition-all duration-300 max-h-[90vh] flex flex-col">
                <div class="flex justify-between items-center px-8 py-5 border-b dark:border-slate-800 shrink-0">
                    <div class="flex items-center gap-3">
                        <h2 class="text-base font-bold text-gray-800 dark:text-white">Tambah Kuis</h2>
                    </div>
                    <button @click="openTambahKuis = false"
                        class="text-gray-400 hover:text-gray-800 dark:hover:text-white transition"><i
                            class="fa-solid fa-xmark text-lg"></i></button>
                </div>

                <div class="p-8 overflow-y-auto custom-scrollbar flex-1">
                    <form @submit.prevent="submitTambahPostTest" class="space-y-8">
                        <div>
                            <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-gray-100 dark:border-slate-800">
                                <div class="flex flex-col">
                                    <span class="text-xs font-bold text-gray-700 dark:text-white uppercase">Pretest</span>
                                    <span class="text-[10px] text-gray-400 dark:text-gray-500">Aktifkan jika kuis ini merupakan ujian pretest sebelum materi dimulai.</span>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer shrink-0">
                                    <input type="checkbox" x-model="formTambahKuis.pretest" class="sr-only peer">

                                    <div class="w-11 h-6 bg-gray-200 dark:bg-slate-700 rounded-full relative peer-checked:bg-emerald-500 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:w-5 after:h-5 after:bg-white after:rounded-full after:transition-all peer-checked:after:translate-x-5">
                                    </div>
                                </label>
                            </div>
                        </div>
                        <div
                            class="bg-slate-50 dark:bg-slate-800/50 p-6 rounded-2xl border border-gray-100 dark:border-slate-800 grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="md:col-span-1">
                                <label
                                    class="block text-[10px] font-bold text-gray-400 dark:text-white uppercase mb-2">Judul Kuis</label>
                                <input type="text" x-model="formTambahKuis.judul" required
                                    class="w-full bg-white dark:bg-slate-800 border dark:border-slate-700 rounded-lg h-10 px-4 text-sm dark:text-white outline-none focus:ring-2 focus:ring-emerald-500/20">
                            </div>
                            <div>
                                <label
                                    class="block text-[10px] font-bold text-gray-400 dark:text-white uppercase mb-2">Waktu (Detik)</label>
                                <input type="number" x-model="formTambahKuis.waktu_pengerjaan" required min="1"
                                    class="w-full bg-white dark:bg-slate-800 border dark:border-slate-700 rounded-lg h-10 px-4 text-sm dark:text-white outline-none focus:ring-2 focus:ring-emerald-500/20">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 dark:text-white uppercase mb-2">Maks Percobaan</label>
                                <input type="number" x-model="formTambahKuis.ulang_post_test" required min="1"
                                    class="w-full bg-white dark:bg-slate-800 border dark:border-slate-700 rounded-lg h-10 px-4 text-sm dark:text-white outline-none focus:ring-2 focus:ring-emerald-500/20">
                            </div>
                        </div>

                        <div class="space-y-6">
                            <template x-for="(q, index) in formTambahKuis.questions" :key="index">
                                <div
                                    class="bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-2xl overflow-hidden shadow-sm relative">
                                    <div
                                        class="bg-slate-50 dark:bg-slate-800/30 px-6 py-3 border-b dark:border-slate-800 flex justify-between items-center">
                                        <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Pertanyaan
                                            #<span x-text="index + 1"></span></span>
                                        <button type="button" @click="removeQuestionFromTambah(index)"
                                            class="text-red-400 hover:text-red-600 transition"><i
                                                class="fa-solid fa-trash-can"></i></button>
                                    </div>
                                    <div class="p-6 space-y-6">
                                        <textarea x-model="q.soal" required placeholder="Tuliskan pertanyaan..."
                                            class="w-full bg-white dark:bg-slate-800 border dark:border-slate-700 rounded-xl p-4 text-sm dark:text-white resize-none h-24"></textarea>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                            <div class="space-y-4">
                                                <div class="flex items-center justify-between">
                                                    <label class="text-[10px] font-bold text-gray-400 uppercase">Pilihan
                                                        Jawaban</label>
                                                    <button type="button" @click="addOptionToQuestion(q)"
                                                        x-show="q.options.length < 5"
                                                        class="text-[10px] font-bold text-emerald-600">+ Tambah
                                                        Opsi</button>
                                                </div>
                                                <div class="space-y-3">
                                                    <template x-for="(opt, oIndex) in q.options" :key="oIndex">
                                                        <div class="flex items-center gap-3">
                                                            <div class="w-6 h-6 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-[10px] font-bold text-gray-400"
                                                                x-text="String.fromCharCode(65 + oIndex)"></div>
                                                            <input type="text" x-model="q.options[oIndex]" required
                                                                class="flex-1 bg-white dark:bg-slate-800 border dark:border-slate-700 rounded-lg h-9 px-3 text-xs dark:text-white">
                                                            <button type="button"
                                                                @click="removeOptionFromQuestion(q, oIndex)"
                                                                x-show="q.options.length > 2"
                                                                class="text-gray-300 hover:text-red-400 transition text-[10px]"><i
                                                                    class="fa-solid fa-xmark"></i></button>
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>
                                            <div class="space-y-6">
                                                <div
                                                    class="flex items-center gap-4 bg-slate-50 dark:bg-slate-800/50 p-2 rounded-lg border dark:border-slate-800">
                                                    <button type="button" @click="q.status_pilihan = 0"
                                                        :class="q.status_pilihan === 0 ? 'bg-white dark:bg-slate-700 text-emerald-600' : 'text-gray-400'"
                                                        class="flex-1 py-1.5 rounded-md text-[10px] font-bold uppercase">Single</button>
                                                    <button type="button" @click="q.status_pilihan = 1"
                                                        :class="q.status_pilihan === 1 ? 'bg-white dark:bg-slate-700 text-emerald-600' : 'text-gray-400'"
                                                        class="flex-1 py-1.5 rounded-md text-[10px] font-bold uppercase">Multiple</button>
                                                </div>
                                                <div>
                                                    <label
                                                        class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Jawaban
                                                        Benar</label>
                                                    <input type="text" x-model="q.jawaban_benar" required
                                                        placeholder="A atau 1"
                                                        class="w-full bg-white dark:bg-slate-800 border dark:border-slate-700 rounded-lg h-10 px-4 text-xs dark:text-white">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <button type="button" @click="addQuestionToTambah()"
                            class="w-full py-4 border-2 border-dashed border-gray-200 dark:border-slate-800 rounded-2xl text-gray-400 hover:text-emerald-500 transition-all font-bold text-xs uppercase tracking-widest bg-white dark:bg-slate-900/50">
                            <i class="fa-solid fa-plus mr-2"></i> Tambah Pertanyaan Baru
                        </button>

                        <div
                            class="flex flex-col sm:flex-row justify-end gap-3 pt-6 border-t dark:border-slate-800 sticky bottom-0 bg-white dark:bg-slate-900 py-4">
                            <button @click="openTambahKuis = false" type="button"
                                class="w-full sm:w-auto px-8 py-2.5 border dark:border-slate-700 text-gray-500 font-bold rounded-lg text-xs">Batal</button>
                            <button type="submit" :disabled="isSubmitting"
                                class="w-full sm:w-auto px-8 py-2.5 bg-emerald-600 text-white font-bold rounded-lg text-xs">Simpan
                                Kuis</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- MODAL EDIT KUIS --}}
        <div x-show="openEditKuis"
            class="fixed inset-0 z-[60] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4" x-transition
            x-cloak>
            <div
                class="bg-white dark:bg-slate-900 w-full max-w-4xl rounded-2xl shadow-2xl overflow-hidden transition-all duration-300 max-h-[90vh] flex flex-col">
                <div class="flex justify-between items-center px-8 py-5 border-b dark:border-slate-800 shrink-0">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 text-blue-600 flex items-center justify-center rounded-lg">
                            <i class="fa-solid fa-pen-to-square text-sm"></i>
                        </div>
                        <h2 class="text-base font-bold text-gray-800 dark:text-white">Edit Quiz Builder</h2>
                    </div>
                    <button @click="openEditKuis = false"
                        class="text-gray-400 hover:text-gray-800 dark:hover:text-white transition"><i
                            class="fa-solid fa-xmark text-lg"></i></button>
                </div>

                <div class="p-8 overflow-y-auto custom-scrollbar flex-1">
                    <form @submit.prevent="submitEditPostTest" class="space-y-8">
                        <div>
                            <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-gray-100 dark:border-slate-800">
                                <div class="flex flex-col">
                                    <span class="text-xs font-bold text-gray-700 dark:text-white uppercase">Jadikan Pretest</span>
                                    <span class="text-[10px] text-gray-400 dark:text-gray-500">Aktifkan jika kuis ini merupakan ujian pretest sebelum materi dimulai.</span>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" x-model="selectedKuis.pretest" class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 dark:bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                                </label>
                            </div>
                        </div>
                        <div
                            class="bg-slate-50 dark:bg-slate-800/50 p-6 rounded-2xl border border-gray-100 dark:border-slate-800 grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="md:col-span-1">
                                <label
                                    class="block text-[10px] font-bold text-gray-400 dark:text-white uppercase mb-2">Judul
                                    Kuis</label>
                                <input type="text" x-model="selectedKuis.judul" required
                                    class="w-full bg-white dark:bg-slate-800 border dark:border-slate-700 rounded-lg h-10 px-4 text-sm dark:text-white">
                            </div>
                            <div>
                                <label
                                    class="block text-[10px] font-bold text-gray-400 dark:text-white uppercase mb-2">Waktu
                                    (Menit)</label>
                                <input type="number" x-model="selectedKuis.waktu_pengerjaan" required min="1"
                                    class="w-full bg-white dark:bg-slate-800 border dark:border-slate-700 rounded-lg h-10 px-4 text-sm dark:text-white">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 dark:text-white uppercase mb-2">Maks
                                    Percobaan</label>
                                <input type="number" x-model="selectedKuis.ulang_post_test" required min="1"
                                    class="w-full bg-white dark:bg-slate-800 border dark:border-slate-700 rounded-lg h-10 px-4 text-sm dark:text-white">
                            </div>
                        </div>

                        <div class="space-y-6">
                            <template x-for="(q, index) in selectedKuis.questions" :key="index">
                                <div
                                    class="bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-2xl overflow-hidden shadow-sm relative">
                                    <div
                                        class="bg-slate-50 dark:bg-slate-800/30 px-6 py-3 border-b dark:border-slate-800 flex justify-between items-center">
                                        <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Pertanyaan
                                            #<span x-text="index + 1"></span></span>
                                        <button type="button" @click="selectedKuis.questions.splice(index, 1)"
                                            class="text-red-400 hover:text-red-600 transition"><i
                                                class="fa-solid fa-trash-can"></i></button>
                                    </div>
                                    <div class="p-6 space-y-6">
                                        <textarea x-model="q.soal" required placeholder="Tuliskan pertanyaan..."
                                            class="w-full bg-white dark:bg-slate-800 border dark:border-slate-700 rounded-xl p-4 text-sm dark:text-white resize-none h-24"></textarea>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                            <div class="space-y-4">
                                                <div class="flex items-center justify-between">
                                                    <label class="text-[10px] font-bold text-gray-400 uppercase">Pilihan
                                                        Jawaban</label>
                                                    <button type="button" @click="addOptionToQuestion(q)"
                                                        x-show="q.options.length < 5"
                                                        class="text-[10px] font-bold text-emerald-600">+ Tambah
                                                        Opsi</button>
                                                </div>
                                                <div class="space-y-3">
                                                    <template x-for="(opt, oIndex) in q.options" :key="oIndex">
                                                        <div class="flex items-center gap-3">
                                                            <div class="w-6 h-6 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-[10px] font-bold text-gray-400"
                                                                x-text="String.fromCharCode(65 + oIndex)"></div>
                                                            <input type="text" x-model="q.options[oIndex]" required
                                                                class="flex-1 bg-white dark:bg-slate-800 border dark:border-slate-700 rounded-lg h-9 px-3 text-xs dark:text-white">
                                                            <button type="button"
                                                                @click="removeOptionFromQuestion(q, oIndex)"
                                                                x-show="q.options.length > 2"
                                                                class="text-gray-300 hover:text-red-400 transition text-[10px]"><i
                                                                    class="fa-solid fa-xmark"></i></button>
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>
                                            <div class="space-y-6">
                                                <div
                                                    class="flex items-center gap-4 bg-slate-50 dark:bg-slate-800/50 p-2 rounded-lg border dark:border-slate-800">
                                                    <button type="button" @click="q.status_pilihan = 0"
                                                        :class="q.status_pilihan === 0 ? 'bg-white dark:bg-slate-700 text-emerald-600' : 'text-gray-400'"
                                                        class="flex-1 py-1.5 rounded-md text-[10px] font-bold uppercase">Single</button>
                                                    <button type="button" @click="q.status_pilihan = 1"
                                                        :class="q.status_pilihan === 1 ? 'bg-white dark:bg-slate-700 text-emerald-600' : 'text-gray-400'"
                                                        class="flex-1 py-1.5 rounded-md text-[10px] font-bold uppercase">Multiple</button>
                                                </div>
                                                <div>
                                                    <label
                                                        class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Jawaban
                                                        Benar</label>
                                                    <input type="text" x-model="q.jawaban_benar" required
                                                        class="w-full bg-white dark:bg-slate-800 border dark:border-slate-700 rounded-lg h-10 px-4 text-xs dark:text-white">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <button type="button"
                            @click="selectedKuis.questions.push({ soal: '', options: ['', ''], status_pilihan: 0, jawaban_benar: '' })"
                            class="w-full py-4 border-2 border-dashed border-gray-200 dark:border-slate-800 rounded-2xl text-gray-400 hover:text-blue-500 transition-all font-bold text-xs uppercase tracking-widest bg-white dark:bg-slate-900/50">
                            <i class="fa-solid fa-plus mr-2"></i> Tambah Pertanyaan Baru
                        </button>

                        <div
                            class="flex flex-col sm:flex-row justify-end gap-3 pt-6 border-t dark:border-slate-800 sticky bottom-0 bg-white dark:bg-slate-900 py-4">
                            <button @click="openEditKuis = false" type="button"
                                class="w-full sm:w-auto px-8 py-2.5 border dark:border-slate-700 text-gray-500 font-bold rounded-lg text-xs">Batal</button>
                            <button type="submit" :disabled="isSubmitting"
                                class="w-full sm:w-auto px-8 py-2.5 bg-blue-600 text-white font-bold rounded-lg text-xs">Simpan
                                Perubahan Kuis</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function daftarMateriKuisData(materiId, readOnly = false) {
            return {
                materiId: materiId,
                readOnly: readOnly,
                sidebarOpen: false,
                darkMode: localStorage.getItem('theme') === 'dark',
                isLoading: true,
                isSubmitting: false,
                materiInfo: {},
                contents: [],

                openTambahMateri: false,
                openTambahKuis: false,
                openEditMateri: false,
                openEditKuis: false,

                formTambahMateri: { judul: '', deskripsi: '', file: null, uploadType: null },
                selectedMateri: { id: '', judul: '', deskripsi: '', file: null, uploadType: null },

                formTambahKuis: {
                    judul: '',
                    waktu_pengerjaan: '',
                    ulang_post_test: '',
                    pretest: false,
                    questions: [{ soal: '', options: ['', ''], status_pilihan: 0, jawaban_benar: '' }]
                },

                selectedKuis: {
                    id: '', judul: '', waktu_pengerjaan: '', ulang_post_test: '', pretest: false, questions: []
                },

                async initData() {
                    this.fetchData();
                },

                async fetchData() {
                    this.isLoading = true;
                    try {
                        const response = await fetch(`/api/admin/manajemen-pelatihan/content/${this.materiId}/data`, {
                            headers: {
                                'Accept': 'application/json',
                                'Authorization': 'Bearer ' + localStorage.getItem('token')
                            }
                        });
                        const res = await response.json();
                        if (res.success) {
                            this.materiInfo = res.data.materi;
                            this.contents = res.data.contents;
                        }
                    } catch (e) { console.error(e); }
                    this.isLoading = false;
                },

                // Sub Materi Methods
                openTambahMateriModal() {
                    this.formTambahMateri = { judul: '', deskripsi: '', file: null, uploadType: null };
                    this.openTambahMateri = true;
                },

                async submitTambahSubMateri() {
                    // Validasi file wajib diisi
                    if (!this.formTambahMateri.file) {
                        Toast.fire({ 
                            icon: 'warning', 
                            title: 'File Belum Dipilih', 
                            text: 'Mohon pilih file materi terlebih dahulu (Video atau Dokumen).' 
                        });
                        return;
                    }

                    this.isSubmitting = true;
                    let formData = new FormData();
                    formData.append('judul', this.formTambahMateri.judul);
                    formData.append('deskripsi', this.formTambahMateri.deskripsi || '');
                    if (this.formTambahMateri.file) formData.append('file_materi', this.formTambahMateri.file);

                    try {
                        const response = await fetch(`/api/admin/manajemen-pelatihan/content/${this.materiId}/sub-materi`, {
                            method: 'POST',
                            headers: { 'Accept': 'application/json', 'Authorization': 'Bearer ' + localStorage.getItem('token') },
                            body: formData
                        });
                        const res = await response.json();
                        if (res.success) {
                            Toast.fire({ icon: 'success', title: 'Berhasil', text: res.message || 'Materi berhasil diunggah!' });
                            this.openTambahMateri = false;
                            this.fetchData();
                        } else { Toast.fire({ icon: 'error', title: 'Gagal', text: res.message || 'Gagal mengunggah materi.' }); }
                    } catch (e) { console.error(e); }
                    this.isSubmitting = false;
                },

                prepareEditMateri(item) {
                    this.selectedMateri = {
                        id: item.sub_materi_id,
                        judul: item.judul,
                        deskripsi: item.deskripsi || '',
                        file: null,
                        file_path: item.file_materi || null,
                        uploadType: null
                    };
                    this.openEditMateri = true;
                },

                async submitEditSubMateri() {
                    // Validasi file wajib diisi saat edit (minimal saat pertama kali ditambah)
                    if (!this.selectedMateri.file && !this.selectedMateri.file_path) {
                        Toast.fire({ 
                            icon: 'warning', 
                            title: 'File Belum Dipilih', 
                            text: 'Mohon pilih file materi terlebih dahulu untuk mengedit materi ini.' 
                        });
                        return;
                    }

                    this.isSubmitting = true;
                    let formData = new FormData();
                    formData.append('_method', 'PUT');
                    formData.append('judul', this.selectedMateri.judul);
                    formData.append('deskripsi', this.selectedMateri.deskripsi || '');
                    if (this.selectedMateri.file) formData.append('file_materi', this.selectedMateri.file);

                    try {
                        const response = await fetch(`/api/admin/manajemen-pelatihan/sub-materi/${this.selectedMateri.id}`, {
                            method: 'POST',
                            headers: { 'Accept': 'application/json', 'Authorization': 'Bearer ' + localStorage.getItem('token') },
                            body: formData
                        });
                        const res = await response.json();
                        if (res.success) {
                            Toast.fire({ icon: 'success', title: 'Berhasil', text: res.message || 'Materi berhasil diperbarui!' });
                            this.openEditMateri = false;
                            this.fetchData();
                        } else { Toast.fire({ icon: 'error', title: 'Gagal', text: res.message || 'Gagal memperbarui materi.' }); }
                    } catch (e) { console.error(e); }
                    this.isSubmitting = false;
                },

                async deleteSubMateri(id) {
                    const result = await this.confirmDeleteDialog("Materi ini akan dihapus secara permanen!");
                    if (result.isConfirmed) {
                        try {
                            const response = await fetch(`/api/admin/manajemen-pelatihan/sub-materi/${id}`, {
                                method: 'DELETE',
                                headers: { 'Accept': 'application/json', 'Authorization': 'Bearer ' + localStorage.getItem('token') }
                            });
                            const res = await response.json();
                            if (res.success) {
                                Toast.fire({ icon: 'success', title: 'Berhasil', text: res.message || 'Materi berhasil dihapus.' });
                                this.fetchData();
                            }
                        } catch (e) { console.error(e); }
                    }
                },

                // Post Test Methods
                openTambahKuisModal() {
                    this.formTambahKuis = {
                        judul: '',
                        waktu_pengerjaan: '',
                        ulang_post_test: '',
                        pretest: false,
                        questions: [{ soal: '', options: ['', ''], status_pilihan: 0, jawaban_benar: '' }]
                    };
                    this.openTambahKuis = true;
                },

                addQuestionToTambah() { this.formTambahKuis.questions.push({ soal: '', options: ['', ''], status_pilihan: 0, jawaban_benar: '' }); },
                removeQuestionFromTambah(index) { if (this.formTambahKuis.questions.length > 1) this.formTambahKuis.questions.splice(index, 1); },

                addOptionToQuestion(q) { if (q.options.length < 5) q.options.push(''); },
                removeOptionFromQuestion(q, index) { if (q.options.length > 2) q.options.splice(index, 1); },

                async submitTambahPostTest() {
                    this.isSubmitting = true;
                    try {
                        const response = await fetch(`/api/admin/manajemen-pelatihan/content/${this.materiId}/quiz`, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'Authorization': 'Bearer ' + localStorage.getItem('token')
                            },
                            body: JSON.stringify(this.formTambahKuis)
                        });
                        const res = await response.json();
                        if (res.success) {
                            Toast.fire({ icon: 'success', title: 'Berhasil', text: res.message || 'Kuis berhasil dibuat!' });
                            this.openTambahKuis = false;
                            this.fetchData();
                        } else { Toast.fire({ icon: 'error', title: 'Gagal', text: res.message || 'Gagal membuat kuis.' }); }
                    } catch (e) { console.error(e); }
                    this.isSubmitting = false;
                },

                prepareEditKuis(item) {
                    this.selectedKuis = {
                        id: item.post_test_id,
                        judul: item.judul,
                        waktu_pengerjaan: item.waktu_pengerjaan,
                        ulang_post_test: item.ulang_post_test,
                        pretest: !!item.pretest,
                        questions: item.soals.map(s => {
                            let opts = [];
                            if (s.pilihan_1) opts.push(s.pilihan_1);
                            if (s.pilihan_2) opts.push(s.pilihan_2);
                            if (s.pilihan_3) opts.push(s.pilihan_3);
                            if (s.pilihan_4) opts.push(s.pilihan_4);
                            if (s.pilihan_5) opts.push(s.pilihan_5);
                            return {
                                soal: s.soal,
                                options: opts,
                                status_pilihan: s.status_pilihan,
                                jawaban_benar: s.jawaban_benar
                            };
                        })
                    };
                    this.openEditKuis = true;
                },

                async submitEditPostTest() {
                    this.isSubmitting = true;
                    try {
                        const response = await fetch(`/api/admin/manajemen-pelatihan/post-test/${this.selectedKuis.id}`, {
                            method: 'PUT',
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'Authorization': 'Bearer ' + localStorage.getItem('token')
                            },
                            body: JSON.stringify(this.selectedKuis)
                        });
                        const res = await response.json();
                        if (res.success) {
                            Toast.fire({ icon: 'success', title: 'Berhasil', text: res.message || 'Kuis berhasil diperbarui!' });
                            this.openEditKuis = false;
                            this.fetchData();
                        } else { Toast.fire({ icon: 'error', title: 'Gagal', text: res.message || 'Gagal memperbarui kuis.' }); }
                    } catch (e) { console.error(e); }
                    this.isSubmitting = false;
                },

                async deletePostTest(id) {
                    const result = await this.confirmDeleteDialog("Kuis ini akan dihapus secara permanen!");
                    if (result.isConfirmed) {
                        try {
                            const response = await fetch(`/api/admin/manajemen-pelatihan/post-test/${id}`, {
                                method: 'DELETE',
                                headers: { 'Accept': 'application/json', 'Authorization': 'Bearer ' + localStorage.getItem('token') }
                            });
                            const res = await response.json();
                            if (res.success) {
                                Toast.fire({ icon: 'success', title: 'Berhasil', text: res.message || 'Kuis berhasil dihapus.' });
                                this.fetchData();
                            }
                        } catch (e) { console.error(e); }
                    }
                },

                async confirmDeleteDialog(text) {
                    return await Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: text,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#64748b',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        reverseButtons: true,
                        customClass: {
                            popup: 'rounded-2xl dark:bg-slate-800 dark:text-white',
                            confirmButton: 'rounded-lg px-6 py-2.5 text-xs font-bold',
                            cancelButton: 'rounded-lg px-6 py-2.5 text-xs font-bold'
                        }
                    });
                }
            }
        }
    </script>

    <style>
        [x-cloak] {
            display: none !important;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #E2E8F0;
            border-radius: 10px;
        }

        .dark .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #334155;
        }
    </style>
@endsection