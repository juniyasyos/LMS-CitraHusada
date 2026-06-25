@extends('components.layout')
@section('title', 'Beranda Dashboard')

@section('content')

    {{-- PENAMBAHAN: Inisialisasi Alpine.js untuk fitur Dark Mode dan Sidebar Mobile --}}
    <div class="flex h-screen overflow-hidden bg-slate-50 dark:bg-slate-950 transition-colors duration-300" x-data="{ 
            darkMode: localStorage.getItem('theme') === 'dark',
            sidebarOpen: false 
        }">

        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-slate-900 border-r dark:border-slate-800 transition-transform duration-300 lg:translate-x-0 lg:static lg:inset-0 shrink-0 overflow-y-auto">
            @include('components.nav-superadmin')
        </aside>

        {{-- PENAMBAHAN: Overlay hitam transparan saat sidebar mobile terbuka --}}
        <div x-show="sidebarOpen" @click="sidebarOpen = false"
            x-transition:enter="transition opacity-100 ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:leave="transition opacity-100 ease-in duration-200" x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-40 bg-black/50 backdrop-blur-sm lg:hidden" x-cloak>
        </div>

        <div class="flex-1 flex flex-col min-w-0 transition-colors duration-300">

            @include('components.header-superadmin', ['title' => 'Dashboard'])

            <main class="flex-1 overflow-y-auto p-4 lg:p-8 custom-scrollbar">

            <div class="mb-8">
                <h2 class="text-lg lg:text-xl font-bold text-gray-800 dark:text-white transition-colors">
                    Selamat Datang, {{ auth()->user()->name }}
                </h2>
                <p class="text-xs lg:text-sm text-gray-500 dark:text-gray-200 mt-1 italic">
                    Pantau statistik pelatihan sistem <span class="font-semibold text-gray-700 dark:text-white">Hospital LMS</span> hari ini.
                </p>
            </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 lg:gap-4 mb-8">
                    <!-- Total Pengguna -->
                    <div class="bg-white dark:bg-slate-900 p-4 lg:p-5 rounded-xl border border-gray-100 dark:border-slate-800 shadow-sm transition-all hover:shadow-md group">
                        <div class="flex justify-between items-start mb-3">
                            <p class="text-[10px] lg:text-[10px] font-bold text-gray-400 dark:text-white uppercase tracking-wider">Total Pengguna</p>
                            <i class="fa-solid fa-users text-blue-600 opacity-20 dark:opacity-60 text-xs lg:text-sm group-hover:opacity-100 transition-opacity"></i>
                        </div>
                        <h3 id="valTotalPengguna" class="text-lg lg:text-2xl font-bold text-gray-800 dark:text-white"><i class="fa-solid fa-spinner fa-spin text-sm"></i></h3>
                        <p class="text-[9px] lg:text-[10px] text-gray-400 dark:text-gray-200 mt-1 font-medium leading-tight truncate">Karyawan terdaftar aktif</p>
                    </div>

                    <!-- Total Unit Kerja -->
                    <div class="bg-white dark:bg-slate-900 p-4 lg:p-5 rounded-xl border border-gray-100 dark:border-slate-800 shadow-sm transition-all hover:shadow-md group">
                        <div class="flex justify-between items-start mb-3">
                            <p class="text-[10px] lg:text-[10px] font-bold text-gray-400 dark:text-white uppercase tracking-wider">Total Unit Kerja</p>
                            <i class="fa-solid fa-building text-emerald-600 opacity-20 dark:opacity-60 text-xs lg:text-sm group-hover:opacity-100 transition-opacity"></i>
                        </div>
                        <h3 id="valTotalUnitKerja" class="text-lg lg:text-2xl font-bold text-gray-800 dark:text-white"><i class="fa-solid fa-spinner fa-spin text-sm"></i></h3>
                        <p class="text-[9px] lg:text-[10px] text-gray-400 dark:text-gray-200 mt-1 font-medium leading-tight truncate">Departemen terintegrasi</p>
                    </div>

                    <!-- Total Jenis Tenaga -->
                    <div class="bg-white dark:bg-slate-900 p-4 lg:p-5 rounded-xl border border-gray-100 dark:border-slate-800 shadow-sm transition-all hover:shadow-md group">
                        <div class="flex justify-between items-start mb-3">
                            <p class="text-[10px] lg:text-[10px] font-bold text-gray-400 dark:text-white uppercase tracking-wider">Total Jenis Tenaga</p>
                            <i class="fa-solid fa-id-card-clip text-indigo-600 opacity-20 dark:opacity-60 text-xs lg:text-sm group-hover:opacity-100 transition-opacity"></i>
                        </div>
                        <h3 id="valTotalJenisTenaga" class="text-lg lg:text-2xl font-bold text-gray-800 dark:text-white"><i class="fa-solid fa-spinner fa-spin text-sm"></i></h3>
                        <p class="text-[9px] lg:text-[10px] text-gray-400 dark:text-gray-200 mt-1 font-medium leading-tight truncate">Kategori profesi</p>
                    </div>

                    <!-- Total Pelatihan -->
                    <div class="bg-white dark:bg-slate-900 p-4 lg:p-5 rounded-xl border border-gray-100 dark:border-slate-800 shadow-sm transition-all hover:shadow-md group">
                        <div class="flex justify-between items-start mb-3">
                            <p class="text-[10px] lg:text-[10px] font-bold text-gray-400 dark:text-white uppercase tracking-wider">Total Pelatihan</p>
                            <i class="fa-solid fa-book-open text-purple-600 opacity-20 dark:opacity-60 text-xs lg:text-sm group-hover:opacity-100 transition-opacity"></i>
                        </div>
                        <h3 id="valTotalPelatihan" class="text-lg lg:text-2xl font-bold text-gray-800 dark:text-white"><i class="fa-solid fa-spinner fa-spin text-sm"></i></h3>
                        <p class="text-[9px] lg:text-[10px] text-gray-400 dark:text-gray-200 mt-1 font-medium leading-tight truncate">Modul pelatihan tersedia</p>
                    </div>

                    <!-- Pelatihan Aktif -->
                    <div class="bg-white dark:bg-slate-900 p-4 lg:p-5 rounded-xl border border-gray-100 dark:border-slate-800 shadow-sm transition-all hover:shadow-md group">
                        <div class="flex justify-between items-start mb-3">
                            <p class="text-[10px] lg:text-[10px] font-bold text-gray-400 dark:text-white uppercase tracking-wider">Pelatihan Aktif</p>
                            <i class="fa-solid fa-clock text-orange-600 opacity-20 dark:opacity-60 text-xs lg:text-sm group-hover:opacity-100 transition-opacity"></i>
                        </div>
                        <h3 id="valPelatihanAktif" class="text-lg lg:text-2xl font-bold text-gray-800 dark:text-white"><i class="fa-solid fa-spinner fa-spin text-sm"></i></h3>
                        <p class="text-[9px] lg:text-[10px] text-gray-400 dark:text-gray-200 mt-1 font-medium leading-tight truncate">Sedang berjalan saat ini</p>
                    </div>

                    <!-- Pelatihan Selesai -->
                    <div class="bg-white dark:bg-slate-900 p-4 lg:p-5 rounded-xl border border-gray-100 dark:border-slate-800 shadow-sm transition-all hover:shadow-md group">
                        <div class="flex justify-between items-start mb-3">
                            <p class="text-[10px] lg:text-[10px] font-bold text-gray-400 dark:text-white uppercase tracking-wider">Pelatihan Selesai</p>
                            <i class="fa-solid fa-certificate text-pink-600 opacity-20 dark:opacity-60 text-xs lg:text-sm group-hover:opacity-100 transition-opacity"></i>
                        </div>
                        <h3 id="valPelatihanSelesai" class="text-lg lg:text-2xl font-bold text-gray-800 dark:text-white"><i class="fa-solid fa-spinner fa-spin text-sm"></i></h3>
                        <p class="text-[9px] lg:text-[10px] text-gray-400 dark:text-gray-200 mt-1 font-medium leading-tight truncate">Melewati batas waktu</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                    {{-- Grafik Keaktifan --}}
                    <div class="lg:col-span-2 bg-white dark:bg-slate-900 p-4 lg:p-6 rounded-xl border border-gray-100 dark:border-slate-800 shadow-sm transition-colors overflow-hidden">
                        <div class="mb-6 flex justify-between items-center">
                            <h3 class="font-bold text-sm lg:text-base text-gray-800 dark:text-white transition-colors">Grafik Keaktifan</h3>
                            <span class="text-[10px] font-bold text-blue-600 dark:text-blue-400 uppercase bg-blue-50 dark:bg-blue-900/30 px-2 py-1 rounded">Real-time</span>
                        </div>

                        <div class="relative h-[22rem] w-full mt-4">
                            <canvas id="keaktifanChart"></canvas>
                        </div>
                    </div>

                    {{-- Leaderboard --}}
                    <div class="bg-white dark:bg-slate-900 p-4 lg:p-6 rounded-xl border border-gray-100 dark:border-slate-800 shadow-sm flex flex-col transition-colors">
                        <div class="mb-6 relative">
                            <h3 class="font-bold text-sm lg:text-base text-gray-800 dark:text-white transition-colors">Leaderboard</h3>
                            <p class="text-[11px] text-gray-400 dark:text-gray-200 italic">Partisipasi Unit Kerja</p>
                            <a href="{{ route('detail-leaderboard') }}" class="absolute top-0 right-0 text-[10px] text-blue-600 dark:text-blue-400 font-bold underline hover:text-blue-800 transition-colors">Detail</a>
                        </div>

                        <div class="flex justify-center mb-6 relative h-48 lg:h-56 w-full">
                            <canvas id="leaderboardChart"></canvas>
                            <div class="absolute inset-0 flex items-center justify-center pointer-events-none pb-2">
                                <div class="bg-white dark:bg-slate-900 w-16 h-16 rounded-full flex flex-col items-center justify-center shadow-inner transition-colors">
                                    <span class="text-[10px] text-gray-400 dark:text-gray-300 uppercase tracking-wider font-bold mb-0.5">Total</span>
                                    <span class="text-xs font-bold text-gray-800 dark:text-white leading-none" id="leaderboardTotal">0</span>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-2.5 flex-1 overflow-y-auto pr-1 custom-scrollbar" id="leaderboardLegend">
                            <!-- Populated via Javascript API -->
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-900 p-4 lg:p-6 rounded-xl border border-gray-100 dark:border-slate-800 shadow-sm transition-colors">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="font-bold text-sm lg:text-base text-gray-800 dark:text-white transition-colors">Aktivitas Terkini</h3>
                        <a href="{{ route('log-aktivitas') }}" class="text-[9px] lg:text-[10px] font-bold text-blue-600 dark:text-blue-400 uppercase tracking-widest hover:underline transition">Semua</a>
                    </div>

                    <div class="space-y-5" id="logAktivitasContainer">
                        <div class="text-center text-gray-500 dark:text-gray-400 text-xs py-4 flex flex-col items-center justify-center gap-2">
                            <i class="fa-solid fa-spinner fa-spin text-lg"></i>
                            Memuat aktivitas...
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 10px; }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; }
        .custom-scrollbar-h::-webkit-scrollbar { height: 4px; }
        .custom-scrollbar-h::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 10px; }
        [x-cloak] { display: none !important; }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Dark mode awareness for charts
            const isDark = document.documentElement.classList.contains('dark');
            const textColor = isDark ? '#f8fafc' : '#1f2937';
            const gridColor = isDark ? '#334155' : '#f3f4f6';

            // Fetch Main Dashboard Data
            fetch("/api/admin/superadmin/dashboard", {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const stats = data.data.statistik_utama;
                    const status = data.data.status_pelatihan;
                    
                    document.getElementById('valTotalPengguna').innerText = new Intl.NumberFormat('id-ID').format(stats.total_pengguna);
                    document.getElementById('valTotalUnitKerja').innerText = new Intl.NumberFormat('id-ID').format(stats.total_unit_kerja);
                    document.getElementById('valTotalJenisTenaga').innerText = new Intl.NumberFormat('id-ID').format(stats.total_jenis_tenaga);
                    document.getElementById('valTotalPelatihan').innerText = new Intl.NumberFormat('id-ID').format(stats.total_pelatihan);
                    document.getElementById('valPelatihanAktif').innerText = new Intl.NumberFormat('id-ID').format(status.aktif);
                    document.getElementById('valPelatihanSelesai').innerText = new Intl.NumberFormat('id-ID').format(status.selesai);
                    
                    const logs = data.data.log_aktivitas;
                    const container = document.getElementById('logAktivitasContainer');
                    container.innerHTML = '';
                    
                    if (logs.length === 0) {
                        container.innerHTML = '<div class="text-center text-gray-500 dark:text-gray-400 text-xs py-4">Belum ada aktivitas tercatat.</div>';
                    } else {
                        logs.forEach((log, index) => {
                            let bgColor = 'bg-gray-50 dark:bg-slate-800';
                            let iconColor = 'text-gray-500 dark:text-gray-400';
                            let icon = 'fa-circle-info';
                            let badgeColor = 'bg-gray-50 text-gray-500 dark:bg-slate-800 dark:text-gray-400';
                            
                            if (log.tipe === 'Create') {
                                bgColor = 'bg-blue-50 dark:bg-blue-900/30';
                                iconColor = 'text-blue-500 dark:text-blue-400';
                                icon = 'fa-plus';
                                badgeColor = 'bg-blue-50 text-blue-500 dark:bg-blue-900/30 dark:text-blue-400';
                            } else if (log.tipe === 'Update') {
                                bgColor = 'bg-emerald-50 dark:bg-emerald-900/30';
                                iconColor = 'text-emerald-500 dark:text-emerald-400';
                                icon = 'fa-rotate';
                                badgeColor = 'bg-emerald-50 text-emerald-500 dark:bg-emerald-900/30 dark:text-emerald-400';
                            } else if (log.tipe === 'Delete') {
                                bgColor = 'bg-rose-50 dark:bg-rose-900/30';
                                iconColor = 'text-rose-500 dark:text-rose-400';
                                icon = 'fa-trash';
                                badgeColor = 'bg-rose-50 text-rose-500 dark:bg-rose-900/30 dark:text-rose-400';
                            }
                            
                            let userNama = log.user ? log.user.name : 'Sistem';
                            let isFirst = index === 0;
                            
                            // Simple relative time format or just date
                            let dateObj = new Date(log.created_at);
                            let dateStr = dateObj.toLocaleString('id-ID', {day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit'});
                            
                            let perubahanHtml = log.perubahan;
                            if (log.tipe === 'Update' && log.perubahan.includes(',')) {
                                let parts = log.perubahan.split(',');
                                let dataLama = parts[0].trim();
                                let dataBaru = parts.slice(1).join(',').trim();
                                perubahanHtml = `<span class="line-through text-gray-400 dark:text-gray-500">${dataLama}</span>
                                                 <span class="font-bold text-amber-500 mx-1">menjadi</span>
                                                 <span class="font-semibold text-gray-800 dark:text-gray-200">${dataBaru}</span>`;
                            }
                            
                            container.innerHTML += `
                                <div class="flex gap-4 ${isFirst ? '' : 'border-t border-gray-50 dark:border-slate-800 pt-4'}">
                                    <div class="w-8 h-8 rounded-lg ${bgColor} flex items-center justify-center shrink-0 transition-colors">
                                        <i class="fa-solid ${icon} ${iconColor} text-xs"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-xs text-gray-700 dark:text-white leading-snug">
                                            <span class="font-bold">${userNama}</span>
                                            melakukan <span class="font-semibold px-1.5 py-0.5 rounded-md text-[10px] ${badgeColor}">${log.tipe}</span>
                                            pada tabel <span class="font-semibold text-gray-800 dark:text-gray-300">${log.tabel}</span>
                                        </p>
                                        <div class="text-[11px] text-gray-600 dark:text-gray-400 mt-1.5 p-2 bg-gray-50 dark:bg-slate-800/50 rounded border border-gray-100 dark:border-slate-700/50 transition-colors">
                                            ${perubahanHtml}
                                        </div>
                                        <p class="text-[9px] lg:text-[10px] text-gray-400 dark:text-gray-500 mt-1 font-medium italic uppercase tracking-wider">
                                            ${dateStr}
                                        </p>
                                    </div>
                                </div>
                            `;
                        });
                    }
                }
            })
            .catch(error => console.error("Error loading dashboard data:", error));

            // Fetch Chart Data
            fetch("/api/admin/superadmin/dashboard/charts", {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => response.json())
                .then(data => {
                    const ctxKeaktifan = document.getElementById('keaktifanChart').getContext('2d');
                    new Chart(ctxKeaktifan, {
                        type: 'bar',
                        data: {
                            labels: data.grafik_keaktifan.map(item => item.month),
                            datasets: [
                                {
                                    label: 'Telah Selesai',
                                    data: data.grafik_keaktifan.map(item => item.done),
                                    backgroundColor: '#10b981', // emerald-500
                                    borderRadius: 4,
                                    barPercentage: 0.6
                                },
                                {
                                    label: 'Belum Selesai',
                                    data: data.grafik_keaktifan.map(item => item.belum_selesai),
                                    backgroundColor: '#DC2626', // red-500
                                    borderRadius: 4,
                                    barPercentage: 0.6
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            interaction: { mode: 'index', intersect: false },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: { color: gridColor, borderDash: [4, 4] },
                                    border: { display: false },
                                    ticks: { color: textColor }
                                },
                                x: {
                                    grid: { display: false },
                                    border: { display: false },
                                    ticks: { color: textColor }
                                }
                            },
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: { color: textColor, usePointStyle: true, boxWidth: 6, font: { family: 'ui-sans-serif, system-ui', size: 11 } }
                                }
                            }
                        }
                    });

                    const ctxLeaderboard = document.getElementById('leaderboardChart').getContext('2d');
                    const leaderboardTotal = document.getElementById('leaderboardTotal');
                    leaderboardTotal.innerText = data.total_leaderboard;

                    new Chart(ctxLeaderboard, {
                        type: 'doughnut',
                        data: {
                            labels: data.leaderboard.map(item => item.label),
                            datasets: [{
                                data: data.leaderboard.map(item => item.val),
                                backgroundColor: data.leaderboard.map(item => item.color),
                                borderWidth: 0,
                                cutout: '75%',
                                borderRadius: (ctx) => { return ctx.dataIndex === 0 ? 0 : 2; }
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    callbacks: {
                                        label: function (context) {
                                            return ' ' + context.label + ': ' + context.raw + ' User (>=20 JP)';
                                        }
                                    }
                                }
                            }
                        }
                    });

                    const legendContainer = document.getElementById('leaderboardLegend');
                    data.leaderboard.forEach(item => {
                        const legendItem = document.createElement('div');
                        legendItem.className = 'flex justify-between items-center group cursor-default p-2 hover:bg-gray-50 dark:hover:bg-slate-800 rounded-lg transition-colors';
                        legendItem.innerHTML = `
                            <div class="flex items-center gap-3">
                                <div class="w-2.5 h-2.5 rounded-full" style="background-color: ${item.color.replace('0.8', '1')}"></div>
                                <span class="text-[11px] font-semibold text-gray-600 dark:text-gray-300 group-hover:text-gray-900 dark:group-hover:text-white transition">${item.label}</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span class="text-xs font-bold text-gray-800 dark:text-white">${item.val}</span>
                                <span class="text-[9px] font-medium text-gray-400 dark:text-gray-500">Users</span>
                            </div>
                        `;
                        legendContainer.appendChild(legendItem);
                    });
                })
                .catch(error => console.error("Error loading charts:", error));
        });
    </script>
@endsection