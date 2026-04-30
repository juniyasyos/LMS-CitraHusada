@extends('components.layout')
@section('title', 'Manajemen Pengguna')
@section('content')

{{-- Menambahkan state sidebarOpen untuk kontrol menu mobile --}}
<div class="flex h-screen overflow-hidden bg-slate-50 dark:bg-slate-950 transition-colors duration-300" 
    x-data="{ openEdit: false, sidebarOpen: false, darkMode: localStorage.getItem('theme') === 'dark' }">
    
    {{-- Sidebar Responsive Logic --}}
    <aside 
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-slate-900 border-r dark:border-slate-800 transition-transform duration-300 lg:translate-x-0 lg:static lg:inset-0 shrink-0 overflow-y-auto">
        @include('components.nav-superadmin')
    </aside>

    {{-- Overlay untuk menutup sidebar mobile --}}
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
        @include('components.header-superadmin', ['title' => 'Manajemen Pengguna'])

        <main class="flex-1 overflow-y-auto p-4 lg:p-8 custom-scrollbar">
            <div class="flex flex-col sm:flex-row justify-between items-start gap-4 mb-6">
                <div>
                    <h2 class="text-lg font-bold text-gray-800 dark:text-white transition-colors">Daftar Pengguna</h2>
                    <p class="text-xs lg:text-sm text-gray-500 dark:text-gray-300 mt-1">Kelola data staf dan tenaga medis sistem pembelajaran.</p>
                </div>
                <a href="{{ route('tambah-peran') }}" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg flex items-center justify-center gap-2 text-sm font-bold transition shadow-sm active:scale-95">
                    <i class="fa-solid fa-plus text-xs"></i>
                    Tambah Peran
                </a>
            </div>



            <form action="{{ route('manajemen-pengguna') }}" method="GET" id="filterForm" class="mb-6 flex flex-wrap items-center justify-between gap-4 bg-white dark:bg-slate-900 p-4 rounded-xl border border-gray-100 dark:border-slate-800 shadow-sm transition-colors duration-300">
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-2">
                        <label for="per_page" class="text-xs font-bold text-gray-600 dark:text-gray-400">Baris:</label>
                        <select name="per_page" id="per_page" onchange="this.form.submit()" 
                            class="bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-700 dark:text-white text-[11px] rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-16 p-1.5 outline-none">
                            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                            <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="all" id="show_all" value="true" onchange="this.form.submit()"
                            {{ request('all') ? 'checked' : '' }}
                            class="w-4 h-4 text-blue-600 bg-gray-100 dark:bg-slate-800 border-gray-300 dark:border-slate-700 rounded focus:ring-blue-500">
                        <label for="show_all" class="text-xs font-bold text-gray-600 dark:text-gray-400 cursor-pointer">Tampilkan Semua Data</label>
                    </div>
                </div>

                <div class="relative w-full sm:w-72">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 dark:text-white">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </span>
                    <input type="text" name="search" id="searchUser" value="{{ request('search') }}"
                        class="block w-full pl-9 pr-3 py-2.5 border border-gray-200 dark:border-slate-700 rounded-lg bg-gray-50 dark:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-xs text-gray-700 dark:text-white transition-all placeholder:dark:text-gray-400" 
                        placeholder="Cari nama atau NIK...">
                </div>
            </form>

            {{-- Table Wrapper --}}
            <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 overflow-x-auto mb-6 transition-colors duration-300">
                <table class="w-full text-left text-xs min-w-[800px]">         
                    <thead class="text-gray-500 dark:text-white font-bold border-b dark:border-slate-800 bg-gray-50/50 dark:bg-slate-800/50">
                        <tr>
                            <th class="py-4 px-6 uppercase tracking-wider">Nama Lengkap</th>
                            <th class="py-4 px-4 uppercase tracking-wider">NIK</th>
                            <th class="py-4 px-4 uppercase tracking-wider">Jenis Tenaga</th>
                            <th class="py-4 px-4 uppercase tracking-wider">Unit Kerja</th>
                            <th class="py-4 px-4 uppercase tracking-wider">JPL</th>
                            <th class="py-4 px-4 uppercase tracking-wider text-center">Status</th>
                            <th class="py-4 px-6 uppercase tracking-wider text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-800 text-gray-700 dark:text-white">
                        @forelse($users as $user)
                            @php
                                $statusColor = "bg-gray-100 dark:bg-slate-800 text-gray-600 dark:text-gray-300";
                                if ($user->status === "Aktif") $statusColor = "bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400";
                                if ($user->status === "Tidak Aktif") $statusColor = "bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400";
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-800 transition">
                                <td class="py-5 px-6 font-bold text-gray-800 dark:text-white">{{ $user->nama ?? '-' }}</td>
                                <td class="py-5 px-4 text-gray-500 dark:text-gray-300">{{ $user->nik ?? '-' }}</td>
                                <td class="py-5 px-4">
                                    <span class="bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-gray-200 px-3 py-1 rounded-md font-semibold transition-colors">{{ $user->jenisTenaga->jenis_tenaga ?? '-' }}</span>
                                </td>
                                <td class="py-5 px-4 whitespace-nowrap">
                                    <span class="bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-gray-200 px-3 py-1 rounded-md font-semibold">{{ $user->unitKerja->unit_kerja ?? '-' }}</span>
                                </td>
                                <td class="py-5 px-4 text-gray-500 dark:text-gray-300">{{ $user->total_jpl ?? 0 }}</td>
                                <td class="py-5 px-4 text-center">
                                    <span class="{{ $statusColor }} px-3 py-1 rounded font-bold text-[10px] uppercase transition-colors">{{ $user->status ?? '-' }}</span>
                                </td>
                                <td class="py-5 px-6 text-right">
                                    <div class="flex justify-end gap-3">
                                        {{-- Impersonate --}}
                                        <a href="{{ route('manajemen-pengguna.impersonate', $user->user_id) }}" 
                                           class="p-1.5 hover:bg-amber-50 dark:hover:bg-amber-900/20 text-amber-500 rounded-lg transition-all active:scale-90"
                                           title="Masuk sebagai user ini">
                                            <i class="fa-solid fa-eye text-sm"></i>
                                        </a>

                                        {{-- Edit --}}
                                        <button 
                                            @click="openEdit = true" 
                                            onclick="editUser({{ json_encode($user) }})"
                                            class="p-1.5 hover:bg-blue-50 dark:hover:bg-blue-900/20 text-blue-500 rounded-lg transition-all active:scale-90"
                                            title="Edit data">
                                            <i class="fa-solid fa-pen text-sm"></i>
                                        </button>

                                        {{-- Delete --}}
                                        <form action="{{ route('manajemen-pengguna.destroy', $user->user_id) }}" method="POST" class="inline-block delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" 
                                                onclick="confirmDelete(this)"
                                                class="p-1.5 hover:bg-red-50 dark:hover:bg-red-900/20 text-red-500 rounded-lg transition-all active:scale-90"
                                                title="Hapus user">
                                                <i class="fa-solid fa-trash-can text-sm"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-6 text-gray-500 italic border-t border-gray-100 dark:border-slate-800">Data tidak ditemukan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4 px-4">
                @if($users instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-8">
                        <p class="text-[10px] text-gray-400 dark:text-white font-medium uppercase tracking-wider">
                            Menampilkan {{ $users->firstItem() ?? 0 }}-{{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} pengguna
                        </p>
                        <div class="flex items-center gap-1">
                            {{ $users->links('vendor.pagination.custom-tailwind') }}
                        </div>
                    </div>
                @else
                    <div class="flex justify-between items-center mb-8 mt-4">
                        <p class="text-[10px] text-gray-400 dark:text-white font-medium uppercase tracking-wider">
                            Menampilkan semua <span class="font-bold text-gray-600 dark:text-white">{{ $users->count() }}</span> pengguna
                        </p>
                    </div>
                @endif
            </div>

            <div class="bg-white dark:bg-slate-900 rounded-xl border border-gray-100 dark:border-slate-800 p-6 flex items-start gap-4 shadow-sm mb-10 transition-colors">
                <div class="w-10 h-10 bg-blue-50 dark:bg-blue-900/30 rounded-full shrink-0 flex items-center justify-center">
                    <i class="fa-solid fa-lightbulb text-blue-500 dark:text-blue-400"></i>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-gray-800 dark:text-white">Tips Admin</h4>
                    <p class="text-xs text-gray-500 dark:text-gray-300 mt-1 leading-relaxed">Gunakan fitur pencarian untuk mempercepat pelacakan data NIK staf secara instan.</p>
                </div>
            </div>
        </main>
    </div>

    {{-- MODAL EDIT DATA PENGGUNA --}}
    <div x-show="openEdit" 
        class="fixed inset-0 z-60 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        x-cloak>
        
        <div @click.away="openEdit = false" class="bg-white dark:bg-slate-900 w-full max-w-4xl rounded-2xl shadow-2xl overflow-hidden transition-colors duration-300">
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                
                <div class="flex justify-between items-center px-6 lg:px-8 py-5 border-b border-gray-100 dark:border-slate-800">
                    <h2 class="text-base font-bold text-gray-800 dark:text-white">Edit Data Pengguna</h2>
                    <button type="button" @click="openEdit = false" class="text-gray-400 hover:text-gray-800 dark:hover:text-white transition">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <div class="p-6 lg:p-8 space-y-8 max-h-[80vh] overflow-y-auto custom-scrollbar">
                    <div>
                        <h3 class="text-[11px] font-bold text-gray-400 dark:text-white uppercase tracking-widest mb-4">Informasi Personal</h3>
                        <div class="space-y-4 border-t border-gray-50 dark:border-slate-800 pt-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-600 dark:text-white mb-2">Nama Lengkap</label>
                                <input type="text" name="nama" id="edit_nama" required class="w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg h-12 px-4 focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all text-sm dark:text-white">
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-gray-600 dark:text-white mb-2">Nomor Induk Karyawan (NIK)</label>
                                    <input type="text" name="nik" id="edit_nik" required class="w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg h-12 px-4 focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all text-sm dark:text-white">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-600 dark:text-white mb-2">JPL</label>
                                    <input type="text" name="total_jpl" id="edit_jpl" class="w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg h-12 px-4 focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all text-sm dark:text-white opacity-50 cursor-not-allowed" readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-[11px] font-bold text-gray-400 dark:text-white uppercase tracking-widest mb-4">Akses Sistem</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 border-t border-gray-50 dark:border-slate-800 pt-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-600 dark:text-white mb-2">Unit Kerja</label>
                                <select name="unit_kerja_id" id="edit_unit_kerja" required class="w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg h-12 px-4 text-sm dark:text-white outline-none">
                                    @foreach($unit_kerjas as $uk)
                                        <option value="{{ $uk->unit_kerja_id }}">{{ $uk->unit_kerja }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-600 dark:text-white mb-2">Jenis Tenaga</label>
                                <select name="jenis_tenaga_id" id="edit_jenis_tenaga" required class="w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg h-12 px-4 text-sm dark:text-white outline-none">
                                    @foreach($jenis_tenagas as $jt)
                                        <option value="{{ $jt->jenis_tenaga_id }}">{{ $jt->jenis_tenaga }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-600 dark:text-white mb-2">Role/Peran</label>
                                <select name="role_id" id="edit_role" required class="w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg h-12 px-4 text-sm dark:text-white outline-none">
                                    @foreach($roles as $role)
                                        <option value="{{ $role->role_id }}">{{ $role->role }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-600 dark:text-white mb-2">Password (Kosongkan jika tidak ingin mengubah)</label>
                            <input type="password" name="password" placeholder="********" class="w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg h-12 px-4 focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all text-sm dark:text-white">
                        </div>

                        <div class="flex items-center justify-between bg-gray-50 dark:bg-slate-800/50 p-4 rounded-xl border border-gray-100 dark:border-slate-700" x-data="{ status: 'Aktif' }" id="status_toggle_container">
                            <div class="flex flex-col">
                                <span class="text-xs font-bold text-gray-700 dark:text-white">Status Pengguna</span>
                                <span class="text-[10px] text-gray-500 dark:text-gray-400" id="status_text"></span>
                            </div>
                            
                            <input type="hidden" name="status" id="edit_status_val">
                            <button type="button" 
                                id="status_toggle_btn"
                                onclick="toggleStatus()"
                                class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none">
                                <span id="status_toggle_dot" class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                            </button>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4 border-t dark:border-slate-800">
                        <button type="button" @click="openEdit = false" class="w-full sm:w-auto px-8 py-2.5 border border-gray-200 dark:border-slate-700 text-gray-500 dark:text-white text-xs font-bold rounded-lg hover:bg-gray-50 transition">Batal</button>
                        <button type="submit" class="w-full sm:w-auto px-8 py-2.5 bg-blue-600 text-white text-xs font-bold rounded-lg hover:bg-blue-700 shadow-blue-100 transition active:scale-95">Simpan Perubahan</button>
                    </div>
                </div>
            </form>
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

<script>
    // Search Debounce
    let searchInput = document.getElementById('searchUser');
    let timeout = null;
    searchInput.addEventListener('input', function() {
        clearTimeout(timeout);
        timeout = setTimeout(function() {
            document.getElementById('filterForm').submit();
        }, 800);
    });

    // Populate Edit Modal
    function editUser(user) {
        const form = document.getElementById('editForm');
        form.action = `/manajemen-pengguna/${user.user_id}`;
        
        document.getElementById('edit_nama').value = user.nama;
        document.getElementById('edit_nik').value = user.nik;
        document.getElementById('edit_jpl').value = user.total_jpl;
        document.getElementById('edit_unit_kerja').value = user.unit_kerja_id;
        document.getElementById('edit_jenis_tenaga').value = user.jenis_tenaga_id;
        document.getElementById('edit_role').value = user.role_id;
        
        // Status Handling
        updateStatusUI(user.status);
    }

    function toggleStatus() {
        const currentStatus = document.getElementById('edit_status_val').value;
        const newStatus = (currentStatus === 'Aktif') ? 'Tidak Aktif' : 'Aktif';
        updateStatusUI(newStatus);
    }

    function updateStatusUI(status) {
        const valInput = document.getElementById('edit_status_val');
        const textSpan = document.getElementById('status_text');
        const btn = document.getElementById('status_toggle_btn');
        const dot = document.getElementById('status_toggle_dot');

        valInput.value = status;
        textSpan.innerText = (status === 'Aktif') ? 'Akun ini aktif' : 'Akun ini dinonaktifkan';
        
        if (status === 'Aktif') {
            btn.classList.add('bg-blue-600');
            btn.classList.remove('bg-gray-300', 'dark:bg-slate-600');
            dot.classList.add('translate-x-5');
            dot.classList.remove('translate-x-0');
        } else {
            btn.classList.remove('bg-blue-600');
            btn.classList.add('bg-gray-300', 'dark:bg-slate-600');
            dot.classList.remove('translate-x-5');
            dot.classList.add('translate-x-0');
        }
    }

    // Delete Confirmation
    function confirmDelete(button) {
        const form = button.closest('form');
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data pengguna yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-2xl',
                confirmButton: 'rounded-lg px-6 py-2.5 text-xs font-bold',
                cancelButton: 'rounded-lg px-6 py-2.5 text-xs font-bold'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }
</script>
@endsection