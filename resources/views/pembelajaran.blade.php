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
        <div class="relative w-full max-w-md flex items-center mb-6">
            <i class="fas fa-search absolute left-3 text-gray-400"></i>
            <input 
                id="searchMateri"
                type="text"
                placeholder="Cari modul..."
                class="w-full pl-10 pr-4 h-10 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                oninput="debounceSearch()"
            >
        </div>

        <!-- FILTER STATUS -->
        <div class="grid grid-cols-3 gap-3 mb-8"> 

            <button onclick="setActive(this); filterMateri('belum')"
            class="filter-btn flex-1 flex items-center justify-between px-6 py-3 bg-white border rounded-xl shadow-sm hover:bg-gray-50">
            <span>Belum Mulai</span>
            <i class="fa-solid fa-exclamation-circle text-gray-400"></i>
            </button>

            <button onclick="setActive(this); filterMateri('progres')"
            class="filter-btn flex-1 flex items-center justify-between px-6 py-3 bg-white border rounded-xl shadow-sm hover:bg-gray-50">
            <span>Sedang Berjalan</span>
            <i class="fa-solid fa-clock text-gray-400"></i>
            </button>

            <button onclick="setActive(this); filterMateri('selesai')"
            class="filter-btn flex-1 flex items-center justify-between px-6 py-3 bg-white border rounded-xl shadow-sm hover:bg-gray-50">
            <span>Selesai</span>
            <i class="fa-solid fa-check-circle text-gray-400"></i>
            </button>

        </div>

        <!-- CARD GRID -->
        <div id="materiContainer" class="grid md:grid-cols-3 gap-6"></div>

        <!-- LOAD MORE -->
        <div id="loadMoreContainer" class="text-center mt-10 hidden">
            <button onclick="loadMoreMateri()" class="text-blue-600 hover:underline bg-white px-6 py-2 rounded-full border shadow-sm transition">
                Lihat Lebih Banyak →
            </button>
        </div>

    </main>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    loadProfile();
    loadMateri();
});
//warna filter
function setActive(clickedButton) {
    const buttons = document.querySelectorAll('.filter-btn');

    buttons.forEach(btn => {
        // reset ke default
        btn.classList.remove('bg-blue-100', 'text-black');
        btn.classList.add('bg-white');
    });

    // set yang diklik jadi aktif
    clickedButton.classList.remove('bg-white');
    clickedButton.classList.add('bg-blue-100', 'text-black');
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

// GLOBAL STATE
let currentLimit = 6;
let currentStatusFilter = '';
let currentSearchQuery = '';

async function fetchMateri() {
    try {
        let url = '/api/materi-user?limit=' + currentLimit;
        if (currentStatusFilter) url += '&status=' + currentStatusFilter;
        if (currentSearchQuery) url += '&search=' + currentSearchQuery;

        const response = await axios.get(url);
        const materis = response.data.data;
        const total = response.data.total;

        renderMateri(materis);

        const loadMoreContainer = document.getElementById("loadMoreContainer");
        if (total > currentLimit) {
            loadMoreContainer.classList.remove("hidden");
        } else {
            loadMoreContainer.classList.add("hidden");
        }

    } catch (error) {
        console.error("Error load materi", error);
    }
}

// load default materi awal
function loadMateri() {
    currentLimit = 6;
    currentStatusFilter = '';
    currentSearchQuery = '';
    fetchMateri();
}

// event: klik load more
function loadMoreMateri() {
    currentLimit += 6;
    fetchMateri();
}

//template card materi
const storageUrl = "{{ asset('storage') }}";

function renderMateri(materis){
    const container = document.getElementById("materiContainer");
    container.innerHTML = "";

    materis.forEach(materi => {

        const progressPercent = materi.progress_percent ?? 0;

        let statusColor = "bg-gray-800";
        let btnText = "Lanjutkan";
        let btnClass = "bg-blue-600 hover:bg-blue-700";

        if (materi.status === "Selesai") statusColor = "bg-green-500";
        if (materi.status === "Belum Dimulai") statusColor = "bg-red-500";
        if (materi.status === "Belum Dimulai") {
            btnText = "Mulai";
            btnClass = "bg-green-600 hover:bg-green-700";
        }
        if (materi.status === "Selesai") {
            btnText = "Review";
            btnClass = "bg-gray-500 hover:bg-gray-600";
        }
        if (materi.status === "Sesi Berakhir") {
            statusColor = "bg-red-700";
            btnText = "Sesi Berakhir";
            btnClass = "bg-red-300 cursor-not-allowed pointer-events-none";
        }
        
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
                        class="flex-1 ${btnClass} text-white py-2 rounded-lg flex items-center justify-center gap-2">
                        <i class="fas fa-caret-right"></i>
                        ${btnText}
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
function filterMateri(status) {
    currentLimit = 6;
    currentStatusFilter = status;
    currentSearchQuery = '';
    document.getElementById("searchMateri").value = ''; // Reset input text view
    fetchMateri();
}

//search materi
let debounceTimer;
function debounceSearch() {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => {
        searchMateri();
    }, 400);
}

function searchMateri() {
    currentLimit = 6;
    currentSearchQuery = document.getElementById("searchMateri").value;
    fetchMateri();
}
</script>

@endsection