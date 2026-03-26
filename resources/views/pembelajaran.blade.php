@extends('components.layout')
@section('title', 'pembelajaran')
@section('content')

<div class="flex min-h-screen bg-gray-100">
    <!-- SIDEBAR -->
    <aside id="sidebar"
        class="fixed lg:static z-40 top-0 left-0 w-64 h-full bg-white border-r
        transform -translate-x-full lg:translate-x-0
        transition-transform duration-200">

        @include('components.nav')

    </aside>

    <!-- OVERLAY MOBILE -->
    <div id="overlay"
        class="fixed inset-0 bg-black/40 hidden z-30 lg:hidden">
    </div>

    <!-- MAIN CONTENT -->
    <main class="flex-1 p-4 lg:p-8">

        <!-- HEADER -->
        <div class="flex justify-between items-center mb-6">

            <!-- LEFT -->
            <div class="flex items-center gap-3">

                <!-- HAMBURGER -->
                <button id="toggleSidebar"
                    class="lg:hidden text-gray-600 text-xl">
                    <i class="fas fa-bars"></i>
                </button>

                <!-- DESKTOP TEXT -->
                <div class="hidden lg:block">
                    <h2 id="welcomeText" class="text-2xl font-semibold"></h2>
                    <p id="unitJenisText" class="text-sm text-gray-500"></p>
                </div>
                    
                <!-- MOBILE LOGO -->
                <div class="flex items-center gap-2 lg:hidden">
                    <img src="{{ asset('images/logo-lms.png') }}" alt="Logo" class="w-10 h-10">
                    <div class="leading-tight">
                        <p class="text-red-600 font-bold text-lg">
                            Citra Husada
                        </p>
                        <p class="text-green-600 text-sm font-semibold">
                            Learning Management System
                        </p>
                    </div>
                </div>
            </div>

            <!-- RIGHT -->
            <div class="flex items-center gap-4">

                @include('components.notif')

                <!-- USER INFO DESKTOP -->
                <div class="text-right hidden lg:block">
                    <p id="profileName" class="font-medium"></p>
                    <p id="profileUnit" class="text-sm text-gray-500"></p>
                </div>

            </div>

        </div>

        <!-- MOBILE WELCOME TEXT -->
        <div class="lg:hidden mb-6">
            <h2 id="welcomeTextMobile" class="text-lg font-semibold"></h2>
            <p id="unitJenisMobile" class="text-sm text-gray-500"></p>
        </div>

        <!-- SEARCH -->
        <div class="mb-6">
            <div class="relative w-full max-w-md">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input 
                    type="text"
                    placeholder="Cari modul..."
                    class="w-full pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                >
            </div>
        </div>

        <!-- FILTER STATUS -->
        <div class="grid grid-cols-3 gap-3 mb-8">

            <button onclick="filterMateri('belum')"
            class="flex-1 flex items-center justify-between px-6 py-3 bg-white border rounded-xl shadow-sm hover:bg-gray-50">
            <span>Belum Mulai</span>
            <i class="fa-solid fa-exclamation-circle text-gray-400"></i>
            </button>

            <button onclick="filterMateri('progres')"
            class="flex-1 flex items-center justify-between px-6 py-3 bg-white border rounded-xl shadow-sm hover:bg-gray-50">
            <span>Sedang Berjalan</span>
            <i class="fa-solid fa-clock text-gray-400"></i>
            </button>

            <button onclick="filterMateri('selesai')"
            class="flex-1 flex items-center justify-between px-6 py-3 bg-white border rounded-xl shadow-sm hover:bg-gray-50">
            <span>Selesai</span>
            <i class="fa-solid fa-check-circle text-gray-400"></i>
            </button>

        </div>

        <!-- CARD GRID -->
        <div id="materiContainer" class="grid md:grid-cols-3 gap-6"></div>

        <!-- LOAD MORE -->
        <div class="text-center mt-10">
            <button class="text-blue-600 hover:underline">
                Lihat Lebih Banyak →
            </button>
        </div>

    </main>

</div>

<script>
// Check authentication on page load
document.addEventListener('DOMContentLoaded', async function() {
    try {
        const response = await axios.get('/api/check-auth');
        if (!response.data.success) {
            // User belum login, redirect ke halaman login
            window.location.href = '/';
        }
        loadProfile();
        loadMateri();
    } catch (error) {
        // Error checking auth, redirect ke login
        window.location.href = '/';
    }
});

//logout
async function handleLogout(event) {
    event.preventDefault();

    if (!confirm('Apakah Anda yakin ingin keluar?')) {
        return;
    }

    try {
        const response = await axios.post('/api/logout');

        if (response.data.success) {
            window.location.href = '/';
        } else {
            alert(response.data.message);
        }
    } catch (error) {
        console.error('Logout error:', error);
        // Fallback ke redirect langsung jika ada error
        window.location.href = '/';
    }
}
//load data profile
async function loadProfile() {
    try {

        const response = await axios.get('/api/profile');

        const user = response.data.data;

        const nama = user.nama;
        const unitKerja = user.unit_kerja?.unit_kerja ?? '-';
        const jenisTenaga = user.jenis_tenaga?.jenis_tenaga ?? '-';

        // Header kiri
        document.getElementById('welcomeText').innerText =
            'Selamat Datang Kembali, ' + nama;

        document.getElementById('unitJenisText').innerText =
            'Unit ' + unitKerja + ' • ' + jenisTenaga;

        document.getElementById('welcomeTextMobile').innerText =
            'Selamat Datang Kembali, ' + nama;

        document.getElementById('unitJenisMobile').innerText =
            'Unit ' + unitKerja + ' • ' + jenisTenaga;

        // Header kanan
        document.getElementById('profileName').innerText = nama;

        document.getElementById('profileUnit').innerText = 'Unit ' + unitKerja;

    } catch (error) {

        console.error('Error load profile:', error);

    }
}

//load semua materi
async function loadMateri() {

    try {

        const response = await axios.get('/api/materi-user');

        const materis = response.data.data;

        renderMateri(materis);

    } catch (error) {

        console.error("Error load materi", error);

    }

}

//template card materi
const storageUrl = "{{ asset('storage') }}";

function renderMateri(materis){
    const container = document.getElementById("materiContainer");
    container.innerHTML = "";

    materis.forEach(materi => {

        const progressPercent = materi.progress_percent ?? 0;

        let statusColor = "bg-gray-800";
        if (materi.status === "Selesai") statusColor = "bg-green-500";
        if (materi.status === "Belum Dimulai") statusColor = "bg-red-500";

        container.innerHTML += `
        <div class="bg-white rounded-2xl shadow-md overflow-hidden">

            <div class="h-40 bg-gray-300 relative">
                <img src="${storageUrl}/${materi.image}" 
                     class="w-full h-full object-cover">

                <span class="absolute top-3 right-3 ${statusColor} text-white text-xs px-3 py-1 rounded-full">
                    ${materi.status}
                </span>
            </div>

            <div class="p-5">

                <h3 class="font-semibold text-lg">${materi.judul}</h3>
                <p class="text-sm text-gray-500 mb-4">${materi.subjudul}</p>

                <div class="flex justify-between items-center text-sm mb-2">
                    <div class="flex items-center gap-1">
                        <i class="fa-solid fa-clock text-gray-400"></i>
                        <p>${materi.jam_pelajaran} JPL</p>
                    </div>

                    <span class="text-red-500 text-xs">
                        Due: ${materi.tanggal_selesai}
                    </span>
                </div>

                <div class="w-full bg-gray-200 rounded-full h-2 mb-3">
                    <div class="bg-blue-600 h-2 rounded-full"
                         style="width:${progressPercent}%"></div>
                </div>

                <span class="text-xs bg-blue-100 text-blue-600 px-3 py-1 rounded-full">
                    ${progressPercent}%
                </span>

                <div class="flex gap-3 mt-5">

                    <a href="/lanjutkan-materi/${materi.materi_id}"
                        class="flex-1 bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 flex items-center justify-center gap-2">
                        <i class="fas fa-caret-right"></i>
                        Lanjutkan
                    </a>

                    <a href="/detail-materi/${materi.materi_id}"
                        class="flex-1 border py-2 rounded-lg hover:bg-gray-100 flex items-center justify-center gap-2">
                        <i class="fas fa-eye"></i>
                        Detail
                    </a>

                </div>

            </div>

        </div>
        `;
    });
}

//filter materi
async function filterMateri(status) {

    try {
        const response = await axios.get('/api/materi-user?status=' + status);
        console.log("FILTER RESULT:", response.data);
        const materis = response.data.data;
        renderMateri(materis);

    } catch (error) {
        console.error("Error filter materi", error);
    }
}

//search materi
async function searchMateri() {

    const keyword = document.getElementById("searchMateri").value;

    try {

        const response = await axios.get('/api/materi-user?search=' + keyword);

        const materis = response.data.data;

        renderMateri(materis);

    } catch (error) {

        console.error("Error search materi", error);

    }
}
</script>

@endsection