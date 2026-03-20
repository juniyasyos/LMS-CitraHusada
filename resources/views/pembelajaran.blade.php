@extends('components.layout')
@section('title', 'pembelajaran')
@section('content')

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
</script>

<div class="flex min-h-screen">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-white shadow-md">
        {{-- Logo + Title --}}
        <div class="p-1 border-b border-grey-200">
            <div class="flex items-center gap-1 mb-6 mt-6">
            <img src="{{ asset('images/logo-lms.png') }}" alt="Logo" class="w-12 h-12">
                <div>
                    <h1 class="text-red-600 font-bold text-lg">Citra Husada</h1>
                    <p class="text-green-600 text-sm ">Learning Management System</p>
                </div>
            </div>
        </div>
        
        <nav class="p-4 space-y-2">
            <a href="" class="flex items-center gap-2 px-4 py-2 bg-blue-100 text-blue-600 rounded-lg">
                <i class="fa-solid fa-book"></i>
                Pembelajaran Saya
            </a>
            <a href="#" class="flex items-center gap-2 px-4 py-2 hover:bg-gray-100 rounded-lg">
                <i class="fa-solid fa-certificate"></i>
                Sertifikat
            </a>
            <a href="#" class="flex items-center gap-2 px-4 py-2 hover:bg-gray-100 rounded-lg">
                <i class="fa-solid fa-circle-user"></i>
                Profil
            </a>
        </nav>

        <div class="p-4 border-t border-gray-200">
            <a href="#" 
            onclick="handleLogout(event)"
            class="flex items-center gap-2 text-red-600 
                    hover:text-red-800 transition duration-200">
                <i class="fa-solid fa-arrow-left"></i>
                Keluar
            </a>
        </div>

    </aside>

    <!-- MAIN CONTENT -->
    <main class="flex-1 p-8">

        <!-- HEADER -->
        <div class="flex justify-between items-start mb-8">

            <div>
                <h2 id="welcomeText" class="text-2xl font-semibold">
                    Selamat Datang Kembali
                </h2>

                <p id="unitJenisText" class="text-sm text-gray-500">
                    -
                </p>
            </div>

            <div class="flex items-center gap-4">
                <i class="fas fa-bell"></i>

                <div class="text-right">
                    <p id="profileName" class="font-medium">-</p>
                    <p id="profileUnit" class="text-sm text-gray-500">-</p>
                </div>
            </div>

        </div>

        <!-- SEARCH -->
        <div class="mb-6">
            <div class="flex items-center w-full max-w-md border rounded-lg px-3 py-2 focus-within:ring-2 focus-within:ring-blue-400">
                
                <i class="fas fa-search text-gray-400 mr-2"></i>

                <input 
                    type="text"
                    id="searchMateri"
                    placeholder="Cari modul..."
                    onkeyup="searchMateri()"
                    class="w-full outline-none"
                >

            </div>
        </div>

        <!-- FILTER STATUS -->
        <div class="flex w-full gap-4 mb-8">
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
        </div>

        <!-- LOAD MORE -->
        {{-- <div class="text-center mt-10">
            <button class="text-blue-600 hover:underline">
                Lihat Lebih Banyak →
            </button>
        </div> --}}

    </main>
</div>

<script>
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
        console.log(materi);
        const progressPercent = materi.progress_percent ?? 0;

        container.innerHTML += `
        <div class="bg-white rounded-2xl shadow-md overflow-hidden">

            <div class="h-40 bg-gray-300 relative">
                <img src="${storageUrl}/${materi.image}" 
                    class="w-full h-full object-cover">
                <span class="absolute top-3 right-3 bg-gray-800 text-white text-xs px-3 py-1 rounded-full">
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

                    <span class="text-red-500">
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
                        class="flex-1 bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 text-center">
                            Lanjutkan
                    </a>

                    <a href="/detail-materi/${materi.materi_id}"
                        class="flex-1 border py-2 rounded-lg hover:bg-gray-100 text-center">
                            Lihat Detail
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