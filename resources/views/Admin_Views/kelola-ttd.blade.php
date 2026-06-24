@extends('components.layout')
@section('title', 'Kelola Tanda Tangan')

@section('content')
<div class="flex h-screen overflow-hidden bg-slate-50 dark:bg-slate-950 transition-colors duration-300" 
    x-data="kelolaTtdData()">
    
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
        @include('components.header-superadmin', ['title' => 'Laporan & Monitoring'])

        <main class="flex-1 overflow-y-auto p-4 lg:p-8 custom-scrollbar relative">
            {{-- Loading Overlay --}}
            <div x-show="isLoading" class="absolute inset-0 z-50 bg-white/70 dark:bg-slate-900/70 backdrop-blur-sm flex items-center justify-center" x-cloak>
                <i class="fa-solid fa-circle-notch fa-spin text-3xl text-blue-500"></i>
            </div>

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
                        <span class="text-gray-800 dark:text-white font-semibold">Kelola Tanda Tangan</span>
                    </li>
                </ol>
            </nav>

            <div class="grid grid-cols-1 xl:grid-cols-12 gap-8 items-start">
                
                {{-- Kolom Kiri: Form Informasi Direktur --}}
                <div class="xl:col-span-4 bg-white dark:bg-slate-900 rounded-xl border border-gray-100 dark:border-slate-800 shadow-sm p-6 lg:p-8">
                    <div class="mb-8">
                        <h3 class="text-sm font-bold text-gray-800 dark:text-white uppercase tracking-tight">Informasi Direktur</h3>
                        <p class="text-[10px] text-gray-400 mt-1">Detail ini akan dicetak di bagian bawah sertifikat.</p>
                    </div>

                    <form @submit.prevent="submitForm" class="space-y-6">
                        {{-- Upload Tanda Tangan --}}
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase">File Tanda Tangan</label>
                            
                            <template x-if="!form.ttd_preview">
                                <div class="relative border-2 border-dashed border-gray-200 dark:border-slate-700 rounded-xl p-8 flex flex-col items-center justify-center bg-gray-50/50 dark:bg-slate-800/30 group hover:border-blue-400 transition-colors cursor-pointer">
                                    <div class="w-12 h-12 bg-white dark:bg-slate-900 rounded-full shadow-sm flex items-center justify-center mb-3">
                                        <i class="fa-solid fa-upload text-gray-400 group-hover:text-blue-500 transition-colors"></i>
                                    </div>
                                    <p class="text-[11px] font-bold text-gray-600 dark:text-white">Unggah</p>
                                    <input type="file" id="file_ttd" @change="handleFileChange" accept="image/png, image/jpeg, image/jpg" class="absolute inset-0 opacity-0 cursor-pointer">
                                </div>
                            </template>

                            <template x-if="form.ttd_preview">
                                <div class="relative border border-gray-200 dark:border-slate-700 rounded-xl p-4 flex flex-col items-center justify-center bg-white dark:bg-slate-800">
                                    <img :src="form.ttd_preview" alt="Preview Tanda Tangan" class="max-h-32 object-contain mb-3">
                                </div>
                            </template>
                            
                            <div class="flex justify-center" x-show="form.ttd_preview">
                                <button @click="removeTtd" type="button" class="text-[10px] font-bold text-red-500 hover:text-red-600 transition flex items-center gap-1 mt-2">
                                    <i class="fa-solid fa-trash-can"></i> Ganti Tanda Tangan
                                </button>
                            </div>
                        </div>

                        {{-- Input Nama --}}
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase">Nama Lengkap</label>
                            <input type="text" x-model="form.nama" required class="w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg px-3 py-2 text-xs text-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
                        </div>

                        {{-- Input Jabatan --}}
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase">Jabatan</label>
                            <input type="text" x-model="form.jabatan" required class="w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg px-3 py-2 text-xs text-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
                        </div>

                        {{-- Input NIP --}}
                        {{-- <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase">NIP</label>
                            <input type="text" x-model="form.nip" required class="w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg px-3 py-2 text-xs text-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
                        </div> --}}

                        <div class="pt-4">
                            <button type="submit" :disabled="isSaving" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-lg text-xs font-bold shadow-lg shadow-blue-100 dark:shadow-none transition-all active:scale-95 disabled:opacity-50 flex items-center justify-center gap-2">
                                <i x-show="isSaving" class="fa-solid fa-spinner fa-spin"></i>
                                <span x-text="isSaving ? 'Menyimpan...' : 'Simpan Perubahan'"></span>
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Kolom Kanan: Pratinjau Sertifikat --}}
                <div class="xl:col-span-8 bg-white dark:bg-slate-900 rounded-xl border border-gray-100 dark:border-slate-800 shadow-sm p-6 lg:p-10 min-h-[600px]">
                    <div class="mb-10">
                        <h3 class="text-sm font-bold text-gray-800 dark:text-white uppercase tracking-widest">
                            Pratinjau Penempatan Sertifikat
                        </h3>
                    </div>

                    <div class="space-y-8">
                        {{-- Bagian Depan --}}
                        <div>
                            <h4 class="text-xs font-bold text-gray-600 dark:text-gray-400 mb-3 uppercase">Bagian Depan</h4>
                            <div class="flex items-center justify-center bg-gray-50 dark:bg-slate-950/50 rounded-2xl p-4 lg:p-8 border dark:border-slate-800 relative">
                                <div x-show="previewLoading" class="absolute inset-0 z-10 bg-white/50 dark:bg-slate-900/50 backdrop-blur-sm flex items-center justify-center rounded-2xl">
                                    <i class="fa-solid fa-circle-notch fa-spin text-2xl text-blue-500"></i>
                                </div>
                                <img 
                                    :src="previewSertifikatUrl || '{{ Storage::url('materi/Sertifikat/Master_Sertifikat_Depan.png') }}'" 
                                    class="w-full h-auto transition-all duration-500 shadow-lg"
                                    alt="Master Sertifikat Depan"
                                    onerror="this.onerror=null; this.src='https://via.placeholder.com/800x600?text=Gambar+Sertifikat+Depan+Tidak+Ditemukan';"
                                >
                            </div>
                        </div>

                        {{-- Bagian Belakang --}}
                        <div>
                            <h4 class="text-xs font-bold text-gray-600 dark:text-gray-400 mb-3 uppercase">Bagian Belakang</h4>
                            <div class="flex items-center justify-center bg-gray-50 dark:bg-slate-950/50 rounded-2xl p-4 lg:p-8 border dark:border-slate-800 relative">
                                <div x-show="previewLoading" class="absolute inset-0 z-10 bg-white/50 dark:bg-slate-900/50 backdrop-blur-sm flex items-center justify-center rounded-2xl">
                                    <i class="fa-solid fa-circle-notch fa-spin text-2xl text-blue-500"></i>
                                </div>
                                <img 
                                    :src="previewSertifikatUrlBelakang || '{{ Storage::url('materi/Sertifikat/Master_Sertifikat_Belakang.png') }}'" 
                                    class="w-full h-auto transition-all duration-500 shadow-lg"
                                    alt="Master Sertifikat Belakang"
                                    onerror="this.onerror=null; this.src='https://via.placeholder.com/800x600?text=Gambar+Sertifikat+Belakang+Tidak+Ditemukan';"
                                >
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
function kelolaTtdData() {
    return {
        sidebarOpen: false, 
        darkMode: localStorage.getItem('theme') === 'dark',
        form: {
            nama: '',
            jabatan: '',
            nip: '',
            file_ttd: null,
            ttd_preview: null
        },
        isLoading: false,
        isSaving: false,
        previewSertifikatUrl: null,
        previewSertifikatUrlBelakang: null,
        previewLoading: false,
        
        async init() {
            await this.fetchData();
            await this.loadPreviewSertifikat();
        },
        
        async fetchData() {
            this.isLoading = true;
            try {
                const token = localStorage.getItem('access_token');
                const response = await fetch('/api/admin/sertifikat/direktur', {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                });
                const res = await response.json();
                if(res.success && res.data) {
                    this.form.nama = res.data.nama || '';
                    this.form.jabatan = res.data.jabatan || '';
                    this.form.nip = res.data.nip || '';
                    if(res.data.ttd_path) {
                        this.form.ttd_preview = '{{ Storage::url('') }}' + res.data.ttd_path;
                    }
                }
            } catch(e) {
                console.error('Error fetching data:', e);
            } finally {
                this.isLoading = false;
            }
        },

        handleFileChange(event) {
            const file = event.target.files[0];
            if(!file) return;
            this.form.file_ttd = file;
            this.form.ttd_preview = URL.createObjectURL(file);
        },

        removeTtd() {
            this.form.file_ttd = null;
            this.form.ttd_preview = null;
            // Need to reset the input element so the same file can be selected again
            setTimeout(() => {
                const fileInput = document.getElementById('file_ttd');
                if (fileInput) fileInput.value = '';
            }, 50);
        },

        async submitForm() {
            this.isSaving = true;
            try {
                const token = localStorage.getItem('access_token');
                const formData = new FormData();
                formData.append('nama', this.form.nama);
                formData.append('jabatan', this.form.jabatan);
                formData.append('nip', this.form.nip);
                if(this.form.file_ttd) {
                    formData.append('file_ttd', this.form.file_ttd);
                }

                const response = await fetch('/api/admin/sertifikat/direktur', {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                const res = await response.json();
                
                if(res.success) {
                    // Update preview path if newly uploaded
                    if (res.data && res.data.ttd_path) {
                        this.form.ttd_preview = '{{ Storage::url('') }}' + res.data.ttd_path;
                    }
                    this.form.file_ttd = null; // Clear file after upload
                    Toast.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Tanda tangan berhasil diperbarui.',
                    });
                    await this.loadPreviewSertifikat(); // Refresh preview
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: 'Error',
                        text: result.message || 'Terjadi kesalahan saat menyimpan'
                    });
                }
            } catch(e) {
                console.error('Error saving data:', e);
                Toast.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Gagal terhubung ke server'
                });
            } finally {
                this.isSaving = false;
            }
        },

        async loadPreviewSertifikat() {
            this.previewLoading = true;
            try {
                const token = localStorage.getItem('access_token');
                
                // Fetch Depan
                const responseDepan = await fetch('/api/admin/sertifikat/preview?type=depan', {
                    headers: { 'Authorization': `Bearer ${token}` }
                });
                if(responseDepan.ok) {
                    const blobDepan = await responseDepan.blob();
                    if(this.previewSertifikatUrl) URL.revokeObjectURL(this.previewSertifikatUrl);
                    this.previewSertifikatUrl = URL.createObjectURL(blobDepan);
                }

                // Fetch Belakang
                const responseBelakang = await fetch('/api/admin/sertifikat/preview?type=belakang', {
                    headers: { 'Authorization': `Bearer ${token}` }
                });
                if(responseBelakang.ok) {
                    const blobBelakang = await responseBelakang.blob();
                    if(this.previewSertifikatUrlBelakang) URL.revokeObjectURL(this.previewSertifikatUrlBelakang);
                    this.previewSertifikatUrlBelakang = URL.createObjectURL(blobBelakang);
                }

            } catch (e) {
                console.error('Error loading preview:', e);
            } finally {
                this.previewLoading = false;
            }
        }
    }
}
</script>
@endsection