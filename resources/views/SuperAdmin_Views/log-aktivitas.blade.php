@extends('components.layout')
@section('title', 'Log Aktivitas')
@section('content')

{{-- Root Container dengan state sidebarOpen & darkMode --}}
<div class="flex h-screen overflow-hidden bg-slate-50 dark:bg-slate-950 transition-colors duration-300" 
     x-data="{ sidebarOpen: false, darkMode: localStorage.getItem('theme') === 'dark' }">
    
    {{-- Sidebar Responsive --}}
    <aside id="sidebar"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-slate-900 border-r dark:border-slate-800 transition-transform duration-300 lg:translate-x-0 lg:static lg:inset-0 shrink-0 overflow-y-auto">
        @include('components.nav-superadmin')
    </aside>

    {{-- Overlay untuk mobile --}}
    <div x-show="sidebarOpen" 
         @click="sidebarOpen = false" 
         x-transition:enter="transition opacity-100 ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:leave="transition opacity-100 ease-in duration-200"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-40 bg-black/50 backdrop-blur-sm lg:hidden" x-cloak>
    </div>

    <div class="flex-1 flex flex-col min-w-0 transition-colors duration-300">

        {{-- Header Responsive --}}
        <header class="bg-white dark:bg-slate-900 border-b dark:border-slate-800 h-16 flex items-center justify-between px-4 lg:px-8 shrink-0 z-10 transition-colors duration-300">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = true" class="lg:hidden p-2 text-gray-500 dark:text-white">
                    <i class="fa-solid fa-bars text-lg"></i>
                </button>
                <h1 class="text-sm font-semibold text-gray-600 dark:text-white truncate uppercase tracking-tight">Log Aktivitas</h1>
            </div>

            <div class="flex items-center gap-3 lg:gap-4">
                @include('components.notif-superadmin')
                <div class="flex items-center gap-3 pl-2 lg:pl-4 border-l border-gray-100 dark:border-slate-800">
                    <div class="text-right hidden sm:block">
                        <p class="text-xs font-bold text-gray-800 dark:text-white leading-tight">Superadmin</p>
                        <p class="text-[10px] text-gray-500 dark:text-gray-300 font-medium italic uppercase tracking-wider">Administrator Utama</p>
                    </div>
                    <div class="w-8 h-8 bg-gray-200 dark:bg-slate-700 rounded-full overflow-hidden border border-gray-100 dark:border-slate-800 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-user text-gray-500 dark:text-white text-xs"></i>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-4 lg:p-8 custom-scrollbar">
            
            {{-- Main Audit Card --}}
            <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 p-4 lg:p-8 mb-6 transition-all">
                
                <div class="mb-8">
                    <div class="flex items-center gap-2 text-blue-600 dark:text-blue-400 mb-1">
                        <i class="fa-solid fa-shield-halved text-xs"></i>
                        <span class="text-[10px] font-bold uppercase tracking-wider">Keamanan & Audit</span>
                    </div>
                    <h2 class="text-lg lg:text-xl font-bold text-gray-800 dark:text-white tracking-tight">Audit Log Real-time</h2>
                    <p class="text-xs lg:text-sm text-gray-500 dark:text-gray-400 mt-1 max-w-4xl leading-relaxed">
                        Pantau setiap tindakan administrator dan sistem untuk keperluan audit keamanan dan pelacakan perubahan data operasional secara transparan.
                    </p>
                </div>

                {{-- Toolbar Responsive --}}
                <form action="{{ route('log-aktivitas') }}" method="GET" class="flex flex-col lg:flex-row lg:items-center gap-3 mb-8">
                    {{-- Search Input --}}
                    <div class="relative flex-1">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 dark:text-gray-500">
                            <i class="fa-solid fa-magnifying-glass text-xs"></i>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="w-full pl-9 pr-4 py-2 border border-gray-200 dark:border-slate-700 rounded-lg bg-gray-50 dark:bg-slate-800 text-xs text-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all placeholder:dark:text-gray-500" 
                            placeholder="Cari aksi, detail, atau IP...">
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        {{-- Filter Aksi Popout --}}
                        <div x-data="{ open: false }" class="relative flex-1 lg:flex-none">
                            <button type="button" @click="open = !open" 
                                class="w-full flex items-center justify-center gap-2 px-4 py-2 border border-gray-200 dark:border-slate-700 rounded-lg text-[11px] font-bold text-gray-600 dark:text-white bg-gray-50 dark:bg-slate-800 hover:bg-blue-600 hover:text-white dark:hover:bg-blue-600 transition active:scale-95">
                                <i class="fa-solid fa-filter"></i> Filter Aksi
                            </button>
                            <div x-show="open" @click.outside="open = false" x-cloak
                                 class="absolute z-50 right-0 lg:left-0 mt-2 p-4 bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-xl shadow-xl min-w-[200px]">
                                <label class="block text-xs font-bold text-gray-700 dark:text-white mb-3 uppercase">Pilih Aksi</label>
                                <div class="space-y-2 mb-4">
                                    @php $reqTipe = request('tipe', []); @endphp
                                    @foreach(['Create', 'Update', 'Delete', 'Download'] as $t)
                                    <label class="flex items-center gap-3 text-xs text-gray-600 dark:text-gray-300 cursor-pointer hover:text-blue-600">
                                        <input type="checkbox" name="tipe[]" value="{{ $t }}" 
                                               class="rounded border-gray-300 dark:border-slate-700 text-blue-600 focus:ring-blue-500" 
                                               {{ in_array($t, $reqTipe) ? 'checked' : '' }}>
                                        {{ $t }}
                                    </label>
                                    @endforeach
                                </div>
                                <button type="submit" class="w-full py-2 bg-blue-600 text-white rounded-lg text-[10px] font-bold hover:bg-blue-700 transition">
                                    Apply Filter
                                </button>
                            </div>
                        </div>

                        {{-- Tanggal Popout --}}
                        <div x-data="{ open: false }" class="relative flex-1 lg:flex-none">
                            <button type="button" @click="open = !open" 
                                class="w-full flex items-center justify-center gap-2 px-4 py-2 border border-gray-200 dark:border-slate-700 rounded-lg text-[11px] font-bold text-gray-600 dark:text-white bg-gray-50 dark:bg-slate-800 hover:bg-blue-600 hover:text-white dark:hover:bg-blue-600 transition active:scale-95">
                                <i class="fa-regular fa-calendar"></i>
                                <span x-text="$refs.tanggalInput.value ? $refs.tanggalInput.value : 'Pilih Tanggal'"></span>
                            </button>
                            <div x-show="open" @click.outside="open = false" x-cloak
                                 class="absolute z-50 right-0 mt-2 p-4 bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-xl shadow-xl min-w-[250px]">
                                <label class="block text-xs font-bold text-gray-700 dark:text-white mb-2 uppercase">Pilih Tanggal</label>
                                <input type="date" name="tanggal" x-ref="tanggalInput" value="{{ request('tanggal') }}"
                                    class="w-full px-3 py-2 border border-gray-200 dark:border-slate-700 rounded-lg text-xs text-gray-600 dark:text-white bg-gray-50 dark:bg-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:outline-none mb-4 transition-all">
                                <button type="submit" class="w-full py-2 bg-blue-600 text-white rounded-lg text-[10px] font-bold hover:bg-blue-700 transition">
                                    Apply Date
                                </button>
                            </div>
                        </div>

                        {{-- Unduh Button --}}
                        <a href="{{ route('log-aktivitas.export', request()->query()) }}" 
                           class="flex-1 lg:flex-none flex items-center justify-center gap-2 px-4 py-2 bg-slate-100 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg text-[11px] font-bold text-gray-600 dark:text-white hover:bg-blue-600 hover:text-white dark:hover:bg-blue-600 transition active:scale-95">
                            <i class="fa-solid fa-download"></i> Unduh Log
                        </a>
                    </div>
                </form>

                {{-- Table Wrapper --}}
                <div class="overflow-x-auto border border-gray-100 dark:border-slate-800 rounded-xl mb-6 transition-colors">
                    <table class="w-full text-left text-xs min-w-[850px]">
                        <thead class="bg-gray-50/80 dark:bg-slate-800/50 text-gray-500 dark:text-white font-bold border-b dark:border-slate-800 transition-colors uppercase tracking-wider">
                            <tr>
                                <th class="py-4 px-6">Tanggal & Waktu</th>
                                <th class="py-4 px-4">Nama Pengguna</th>
                                <th class="py-4 px-4 text-center">Aksi</th>
                                <th class="py-4 px-4">Detail Aktivitas</th>
                                <th class="py-4 px-6 text-right">IP Address</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-slate-800 text-gray-700 dark:text-white transition-colors">
                            @forelse($logs as $log)
                            @php
                                $icon = 'fa-circle-info';
                                $color = 'border-gray-200 dark:border-slate-700 text-gray-600 dark:text-gray-300';
                                $label = $log->tipe;

                                switch($log->tipe) {
                                    case 'Create':
                                        $icon = 'fa-user-plus';
                                        $label = 'Tambah';
                                        $color = 'bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 border-blue-100 dark:border-blue-900/50';
                                        break;
                                    case 'Update':
                                        $icon = 'fa-pen-to-square';
                                        $label = 'Ubah';
                                        $color = 'bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 border-amber-100 dark:border-amber-900/50';
                                        break;
                                    case 'Delete':
                                        $icon = 'fa-trash-can';
                                        $label = 'Hapus';
                                        $color = 'bg-red-500 text-white border-red-500 dark:bg-red-900/50 dark:text-red-200 dark:border-red-900';
                                        break;
                                    case 'Download':
                                        $icon = 'fa-file-export';
                                        $label = 'Ekspor';
                                        $color = 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 border-indigo-100 dark:border-indigo-900/50';
                                        break;
                                }

                                $detail = $log->perubahan;
                                if ($log->tipe === 'Update' && str_contains($log->perubahan, '|')) {
                                    $parts = explode('|', $log->perubahan);
                                    if (count($parts) === 2) {
                                        $detail = "Mengubah " . $log->tabel . ": \"" . $parts[0] . "\" menjadi \"" . $parts[1] . "\"";
                                    }
                                }
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-800 transition duration-150">
                                <td class="py-5 px-6 leading-relaxed whitespace-nowrap">
                                    <p class="font-bold text-gray-800 dark:text-white">{{ $log->created_at->format('d-m-Y') }}</p>
                                    <p class="text-gray-400 dark:text-gray-500 font-mono text-[10px]">{{ $log->created_at->format('H:i:s') }}</p>
                                </td>
                                <td class="py-5 px-4 leading-tight min-w-[200px]">
                                    <p class="font-bold text-gray-800 dark:text-white">{{ $log->user->nama ?? 'System' }}</p>
                                    <p class="text-[10px] text-gray-400 dark:text-gray-500 italic truncate">{{ $log->user->role->role_name ?? 'Staff' }} • {{ $log->user->nik ?? 'N/A' }}</p>
                                </td>
                                <td class="py-5 px-4 text-center">
                                    <div class="inline-flex items-center gap-2 px-3 py-1.5 border rounded-lg font-bold text-[9px] uppercase tracking-tighter transition-colors {{ $color }}">
                                        <i class="fa-solid {{ $icon }} text-[10px]"></i>
                                        {{ $label }}
                                    </div>
                                </td>
                                <td class="py-5 px-4 text-gray-500 dark:text-gray-300 max-w-xs leading-relaxed italic">
                                    {{ $detail }}
                                </td>
                                <td class="py-5 px-6 text-right text-gray-400 dark:text-gray-500 font-mono font-medium">
                                    {{ $log->ip_address ?? '0.0.0.0' }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-gray-400 dark:text-gray-500 italic font-medium transition-colors">
                                    <div class="flex flex-col items-center gap-2">
                                        <i class="fa-solid fa-inbox text-4xl opacity-20"></i>
                                        <p>Belum ada log aktivitas yang tercatat sesuai kriteria.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination Responsive --}}
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4 transition-colors">
                    <p class="text-[10px] text-gray-400 dark:text-gray-500 font-medium italic order-2 sm:order-1 uppercase tracking-widest">
                        Menampilkan <span class="text-gray-700 dark:text-white font-bold">{{ $logs->firstItem() ?? 0 }}-{{ $logs->lastItem() ?? 0 }}</span> dari {{ $logs->total() }} log
                    </p>
                    <div class="flex items-center gap-1 order-1 sm:order-2">
                        {{ $logs->links() }}
                    </div>
                </div>
            </div>

            {{-- Footer Info Responsive --}}
            <div class="bg-white dark:bg-slate-900 rounded-xl border border-gray-100 dark:border-slate-800 p-6 flex items-start gap-4 shadow-sm mb-10 transition-all hover:shadow-md">
                <div class="w-10 h-10 bg-blue-50 dark:bg-blue-900/30 rounded-full flex items-center justify-center shrink-0 shadow-inner">
                    <i class="fa-solid fa-circle-info text-blue-600 dark:text-blue-400"></i>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-gray-800 dark:text-white transition-colors uppercase tracking-tight">Informasi Retensi Data</h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 leading-relaxed transition-colors">
                        Data log aktivitas disimpan otomatis selama <span class="font-bold text-gray-700 dark:text-white">365 hari</span> kerja. Untuk akses data historis lebih lama, silakan hubungi <span class="font-bold text-gray-700 dark:text-white underline decoration-blue-200">Tim IT Infrastruktur Rumah Sakit</span>.
                    </p>
                </div>
            </div>
        </main>
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