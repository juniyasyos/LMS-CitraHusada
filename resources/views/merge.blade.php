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

################################################################################################################################################################################################################################################################################################################################################################################################################################################

################################################################################################################################################################################################################################################################################################################################################################################################################################################

################################################################################################################################################################################################################################################################################################################################################################################################################################################

################################################################################################################################################################################################################################################################################################################################################################################################################################################

//Detail-Materi
@extends('components.layout')
@section('title', 'detail-materi')
@section('content')

@include('components.header')

<div class="w-full min-h-screen px-8 py-10">
    
    <div class="max-w-6xl mx-auto">

        <!-- BACK BUTTON -->
        <a href="/pembelajaran"
        class="inline-flex items-center gap-2 text-gray-600 hover:text-red-500 mb-6 transition">
            <i class="fas fa-times text-lg"></i>
            <span>Kembali</span>
        </a>

        <!-- JUDUL -->
        <h1 id="judulMateri" class="text-3xl font-bold mb-3"></h1>

        <!-- DESKRIPSI -->
        <p id="deskripsiMateri" class="text-gray-600 mb-5 leading-relaxed"></p>

        <!-- DURASI -->
        <div class="flex items-center gap-2 text-sm text-gray-500 mb-10">
            <i class="fa-solid fa-clock"></i>
            <span id="jamPelajaran"></span>
        </div>

        <!-- LIST DOKUMEN -->
        <div id="listMateri" class="space-y-4"></div> </div>

    </div>

</div>

<script>
    const materiId = window.location.pathname.split('/').pop();

    document.addEventListener('DOMContentLoaded', function() {
        loadDetailMateri();
    });

    async function loadDetailMateri() {
        try {

            const response = await axios.get('/api/materi-user/' + materiId);
            const materi = response.data.data;

            document.getElementById("judulMateri").innerText = materi.judul;
            document.getElementById("deskripsiMateri").innerText = materi.deskripsi;
            document.getElementById("jamPelajaran").innerText =
                "Pengerjaan: " + materi.jam_pelajaran + " JPL";

            renderSteps(materi.steps, materi.urutan_selesai);

        } catch (error) {

            console.error("Error load detail", error);

        }
    }

    function renderSteps(steps, urutanSelesai) {

        const container = document.getElementById("listMateri");

        container.innerHTML = `
            <h2 class="text-xl font-semibold mb-4">Materi</h2>
        `;

        steps.forEach(step => {
            let status = "Belum";
            let warna = "bg-gray-400";

            if (step.urutan <= urutanSelesai) {
                status = "Selesai";
                warna = "bg-green-500";
            }

            container.innerHTML += `
            <div onclick="goToStep(${step.urutan})"
                class="cursor-pointer flex items-center justify-between p-5 border rounded-xl hover:bg-gray-50 transition">

                <div class="flex items-center gap-4">
                    <i class="fa-solid fa-file-lines text-blue-500 text-2xl"></i>
                    <div>
                        <p class="font-medium">${step.judul}</p>
                    </div>
                </div>

                <span class="text-white ${warna} px-4 py-2 rounded-lg">
                    ${status}
                </span>

            </div>
            `;
        });
    }

    function goToStep(step){
        window.location.href = "/lanjutkan-materi/" + materiId + "?step=" + step;
    }

    function goToMateri(){
        window.location.href = "/lanjutkan-materi/" + materiId;
    }
</script>
@endsection

#################################################################################################################################################################################################################################################################################################################################################################################################################################################

#################################################################################################################################################################################################################################################################################################################################################################################################################################################

##################################################################################################################################################################################################################################################################################################################################################################################################################################################

//Lanjutkan-Materi
@extends('components.layout')
@section('title', 'lanjutkan-materi')

@section('content')

@include('components.header')

<div class="flex min-h-screen">

    <!-- SIDEBAR MATERI -->
    <aside class="w-72 bg-white border-r p-6">
        
        <h2 class="font-bold text-lg mb-4">
            Progress Belajar
        </h2>

        <!-- PROGRESS BAR -->
        <div class="mb-6">
            <div class="flex justify-between text-sm mb-1">
                <span>Progress</span>
                <span id="progressText" class="font-medium text-blue-600">0%</span>
            </div>

            <div class="w-full bg-gray-200 rounded-full h-2">
                <div id="progressBar" class="bg-blue-600 h-2 rounded-full" style="width: 0%"></div>
            </div>
        </div>

        <h2 class="font-semibold text-md mb-3">
            Daftar Materi
        </h2>
        
        <div id="daftarMateri" class="space-y-2 text-sm"></div>
    </aside>


    <!-- KONTEN VIDEO -->
    <main class="flex-1 p-8">

        <!-- BUTTON KELUAR -->
        <a href="/detail-materi/{{ $materiId }}"
        class="inline-flex items-center gap-2 text-gray-600 hover:text-red-500 mb-6 transition">
            <i class="fas fa-times text-lg"></i>
            <span>Kembali</span>
        </a>


        <!-- JUDUL -->
        <h1 id="judulMateriAktif" class="text-2xl font-bold mb-6"></h1>

        <!-- KONTEN FILE -->
        <div id="materiViewer" class="mb-6"></div>

        <!-- PENJELASAN MATERI -->
        <div id="tentangMateriBox" class="bg-white border rounded-xl p-6">

            <h2 class="text-lg font-semibold mb-3">
                Tentang Materi
            </h2>
            
            <p id="deskripsiMateri" class="text-gray-600 leading-relaxed"></p>
        </div>
    </main>

</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
    const urlParams = new URLSearchParams(window.location.search);
    const stepDibuka = urlParams.get("step");

    const materiId = "{{ $materiId }}";
    let currentStep = null;
    document.addEventListener('DOMContentLoaded', function(){
        loadMateriLanjutkan();
    });

    let maxWatchedTime = 0;
    const video = document.getElementById("videoMateri");

    // update waktu maksimal yang pernah ditonton
    video.addEventListener("timeupdate", function () {
        if (video.currentTime > maxWatchedTime) {
            maxWatchedTime = video.currentTime;
        }
    });
    // cegah user lompat ke depan
    video.addEventListener("seeking", function () {
        if (video.currentTime > maxWatchedTime) {
            video.currentTime = maxWatchedTime;
        }
    });

    async function loadMateriLanjutkan(){

        try{
            const response = await axios.get('/api/materi-lanjutkan/' + materiId);
            const data = response.data.data;

            document.getElementById("progressText").innerText =
            data.progress_percent + "%";

            document.getElementById("progressBar").style.width =
            data.progress_percent + "%";

            renderSidebar(data.steps, data.urutan_selesai);
            let stepAktif;

            if(stepDibuka){
                stepAktif = data.steps.find(step =>
                    step.urutan === parseInt(stepDibuka)
                );
            }
            else{
                stepAktif = data.steps.find(step =>
                    step.urutan === data.urutan_selesai + 1
                );
            }

            if(stepAktif){
                loadMateri(stepAktif);
            }

        }catch(error){

            console.error("Error load materi lanjutkan", error);

        }

    }

    //daftar materi
    function renderSidebar(steps, urutanSelesai){

        const container = document.getElementById("daftarMateri");

        container.innerHTML = "";

        steps.forEach(step => {

            let status = "lock";
            let icon = "fa-lock text-gray-400";
            let classItem = "text-gray-500";

            if(step.urutan <= urutanSelesai){   
                status = "selesai";
                icon = "fa-circle-check text-green-500";
                classItem = "";
            }
            else if(step.urutan === urutanSelesai + 1){
                status = "aktif";
                icon = "fa-play text-blue-500";
                classItem = "bg-blue-100 text-blue-600 font-medium";
            }

            if(status !== "lock"){

            container.innerHTML += `
            <a href="#"
            onclick="loadMateri(${JSON.stringify(step).replace(/"/g, '&quot;')})"
            class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-100 ${classItem}">
                <i class="fa-solid ${icon}"></i>
                <span>${step.judul}</span>
            </a>
            `;

            }
            else{

            container.innerHTML += `
            <div class="flex items-center gap-3 p-3 rounded-lg text-gray-500">
                <i class="fa-solid ${icon}"></i>
                <span>${step.judul}</span>
            </div>
            `;

            }
        });
    }

    function loadMateri(step){
        currentStep = step;
        document.getElementById("judulMateriAktif").innerText = step.judul;

        const deskripsiContainer = document.getElementById("deskripsiMateri");
        const tentangMateriBox = document.getElementById("tentangMateriBox");

        if(step.type === "sub_materi"){
            tentangMateriBox.style.display = "block";
            deskripsiContainer.innerText = step.deskripsi ?? "";
        }
        else{
            tentangMateriBox.style.display = "none";
        }
        
        const viewer = document.getElementById("materiViewer");
        
        viewer.innerHTML = "";
        if(step.type === "post_test"){

            // ambil data post test dari step
            const sudahMengerjakan = step.sudah_mengerjakan;
            const skor = step.skor_tertinggi ?? 0;

            if(sudahMengerjakan){

                let tombolUlang = "";

                if(skor < 75){
                    tombolUlang = `
                        <button onclick="mulaiKuis()"
                        class="mt-5 bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-lg">
                            Mulai Ulang Test
                        </button>
                    `;
                }

                viewer.innerHTML = `
                <div class="bg-white border rounded-xl p-6 text-center">
                    <h2 class="text-3xl font-bold text-black">
                        ${skor} %
                    </h2>
                    <p class="text-gray-500 mb-3">
                        FINAL SCORE
                    </p>

                    ${
                        skor >= 75
                        ? `<p class="text-green-600 font-semibold">Lulus</p>`
                        : `<p class="text-red-500 font-semibold">Belum Lulus</p>`
                    }

                    <p class="text-gray-500 mt-3">
                        Kuis sudah pernah dikerjakan.
                    </p>

                    ${tombolUlang}
                </div>
                `;

            }else{

                // tampilan default (belum pernah kuis)
                viewer.innerHTML = `
                <div class="bg-white border rounded-xl p-6 text-center">
                    <h2 class="text-lg font-semibold mb-3">Kuis</h2>
                    <p class="text-gray-600 mb-4">
                        Anda akan memulai Kuis untuk materi ini.
                    </p>

                    <div class="flex justify-center gap-6 text-sm mb-6">
                        <span class="bg-blue-100 px-4 py-2 rounded-lg">
                            ${step.jumlah_soal} Soal
                        </span>

                        <span class="bg-blue-100 px-4 py-2 rounded-lg">
                            ${step.waktu_pengerjaan} Detik
                        </span>

                        <span class="bg-blue-100 px-4 py-2 rounded-lg">
                            ${step.max_attempt} Percobaan
                        </span>
                    </div>

                    <button onclick="mulaiKuis()"
                    class="bg-blue-600 text-white px-6 py-3 rounded-lg">
                        Mulai Test
                    </button>
                </div>
                `;
            }

            return;
        }

        const file = step.file ?? "";
        const url = `/storage/${file}`;
        const ext = file.split('.').pop().toLowerCase();
        
        if(!file){
            viewer.innerHTML = "File tidak tersedia";
            return;
        }

        if(ext === "mp4"){
        viewer.innerHTML = `
            <div class="bg-black rounded-xl overflow-hidden">
                <video id="videoMateri" controls class="w-full">
                    <source src="${url}" type="video/mp4">
                </video>
            </div>
        `;

        setTimeout(()=>{
            let videoCompleted = false;
            const video = document.getElementById("videoMateri");
            video.addEventListener("ended", function(){
                if(!videoCompleted){
                    videoCompleted = true;
                    updateProgress(currentStep.urutan);
                }
            });
        },200);
        }
        else if(ext === "pdf"){
            viewer.innerHTML = `
            <div class="bg-white border rounded-xl p-4">
                <canvas id="pdfCanvas"></canvas>

                <div class="flex justify-between mt-4">
                    <button id="prevPage" class="px-4 py-2 bg-gray-300 rounded">
                        Previous
                    </button>

                    <span id="pageInfo"></span>

                    <button id="nextPage" class="px-4 py-2 bg-blue-600 text-white rounded">
                        Next
                    </button>
                </div>
            </div>
            `;

            loadPDF(url);
        }
        //tombol tandai sudah dibaca
        // else if(ext === "pdf"){
        // viewer.innerHTML = `
        // <div class="bg-white border rounded-xl overflow-hidden">
        //     <iframe src="${url}" class="w-full h-[600px]"></iframe>

        //     <div class="p-4 text-right">
        //         <button onclick="updateProgress(${step.urutan})"
        //         class="bg-blue-600 text-white px-5 py-2 rounded-lg">
        //             Tandai Sudah Dibaca
        //         </button>
        //     </div>
        // </div>
        // `;
        // }
        else if(["jpg","jpeg","png","webp"].includes(ext)){
            viewer.innerHTML = `
            <div class="bg-white border rounded-xl p-4 text-center">
                <img src="${url}" class="mx-auto max-h-[600px]">
            </div>
            `;
        }
    }

    //update progress
    async function updateProgress(urutan){
        try{
            await axios.post('/api/progress/update',{
                materi_id: materiId,
                urutan: urutan
            });
            loadMateriLanjutkan();
        }catch(error){

        console.error(error);
        }
    }

    async function loadPDF(url){

        const pdf = await pdfjsLib.getDocument(url).promise;

        let pageNum = 1;
        const totalPages = pdf.numPages;

        const canvas = document.getElementById("pdfCanvas");
        const ctx = canvas.getContext("2d");

        async function renderPage(num){

            const page = await pdf.getPage(num);
            const viewport = page.getViewport({scale:1.5});

            canvas.height = viewport.height;
            canvas.width = viewport.width;

            await page.render({
                canvasContext: ctx,
                viewport: viewport
            }).promise;

            document.getElementById("pageInfo").innerText =
                `Halaman ${num} / ${totalPages}`;

            // jika halaman terakhir
            if(num === totalPages){
                updateProgress(currentStep.urutan);
            }
        }

        document.getElementById("prevPage").onclick = function(){
            if(pageNum <= 1) return;
            pageNum--;
            renderPage(pageNum);
        };

        document.getElementById("nextPage").onclick = function(){
            if(pageNum >= totalPages) return;
            pageNum++;
            renderPage(pageNum);
        };

        renderPage(pageNum);
    }

    async function mulaiKuis(){
        try{

            await axios.post('/api/post-test-start', {
                materi_id: materiId
            });

            // kalau sukses → redirect ke halaman kuis
            window.location.href = "/post-test/" + materiId;

        }catch(error){

            if(error.response && error.response.status === 403){
                alert("Percobaan sudah habis!");
            }else{
                console.error(error);
            }

        }
    }
</script>

@endsection


###################################################################################################################################################################################################################################################################################################################################################################################################################################################
###################################################################################################################################################################################################################################################################################################################################################################################################################################################
###################################################################################################################################################################################################################################################################################################################################################################################################################################################

//login page
@extends('components.layout')
@section('title', 'login')

@section('content')

<div class="min-h-screen flex items-center justify-center bg-gray-100">
    
    {{-- Card --}}
    <div class="bg-white w-full max-w-md p-10 rounded-2xl shadow-2xl">
        
        {{-- Logo + Title --}}
        <div class="flex items-center gap-1 mb-6">
            <img src="{{ asset('images/logo-lms.png') }}" alt="Logo" class="w-12 h-12">
            <div>
                <h1 class="text-red-600 font-bold text-lg">Citra Husada</h1>
                <p class="text-green-600 text-sm">Learning Management System</p>
            </div>
        </div>

        {{-- Welcome Text --}}
        <div class="text-center mb-8">
            <h2 class="text-xl font-semibold text-gray-800">Selamat Datang!</h2>
            <p id="dynamicText" class="text-gray-500 text-sm mt-1">
                Kata kata dapat berubah setiap di load
            </p>
        </div>

        {{-- error message --}}
        <div id="errorContainer" class="mb-4 text-red-600 hidden"></div>

        {{-- Form --}}
        <form id="loginForm" class="space-y-5" onsubmit="handleLogin(event)">
            @csrf

            {{-- Nomor Induk --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Nomor Induk Karyawan
                </label>

                <div class="relative">
                    {{-- Icon --}}
                    <i class="fa-solid fa-id-card absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    {{-- Input --}}
                    <input 
                        type="text"
                        id="nikInput"
                        name="nik"
                        placeholder="1234.12345"
                        class="w-full pl-10 pr-4 py-3 
                            rounded-lg border border-gray-300 
                            focus:outline-none 
                            focus:ring-2 focus:ring-blue-500 
                            focus:border-blue-500 
                            transition duration-200"
                        required
                    >
                </div>
            </div>

            {{-- Password --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Kata Sandi
                </label>
                <div class="relative">
                    {{-- Icon --}}
                    <i class="fa-solid fa-lock absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400"></i>
                    {{-- Input --}}
                    <input 
                        type="password"
                        id="passwordInput"
                        name="password"
                        placeholder="kata sandi"
                        class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required
                    >
                </div>
            </div>

            {{-- Remember me --}}
            <div class="flex items-center gap-2">
                <input type="checkbox" id="rememberInput" name="remember" class="w-4 h-4 text-blue-600 rounded">
                <label class="text-sm text-gray-600">Ingat saya</label>
            </div>

            {{-- Button submit --}}
            <button 
                type="submit"
                id="loginBtn"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-medium transition duration-300"
            >
                Masuk →
            </button>

        </form>

    </div>

</div>

<script>
async function handleLogin(event) {
    event.preventDefault();
    console.log('handleLogin called');

    const nik = document.getElementById('nikInput').value;
    const password = document.getElementById('passwordInput').value;
    const loginBtn = document.getElementById('loginBtn');
    const errorContainer = document.getElementById('errorContainer');

    console.log('Input values:', { nik, password });

    // Disable button dan show loading
    loginBtn.disabled = true;
    loginBtn.textContent = 'Memproses...';

    try {
        console.log('Step 1: Checking if axios is available...', typeof window.axios !== 'undefined');
        if (typeof window.axios === 'undefined') {
            throw new Error('Axios not available! Check if app.js is loaded.');
        }
        
        console.log('Step 2: Sending login request...');
        const response = await window.axios.post('/api/login', {
            nik: nik,
            password: password,
            remember: document.getElementById('rememberInput').checked
        });
        
        console.log('Step 3: Login response received:', response.data);

        if (response.data.success) {
            // Login berhasil, wait a moment untuk session di-set
            console.log('Step 4: Login successful, waiting 500ms...');
            await new Promise(resolve => setTimeout(resolve, 500));
            
            // Verify session dengan check-auth endpoint
            console.log('Step 5: Checking auth status...');
            const authCheck = await window.axios.get('/api/check-auth');
            console.log('Step 6: Auth check response:', authCheck.data);
            
            if (authCheck.data.success) {
                // Session verified, redirect ke dashboard
                console.log('Step 7: Auth verified, redirecting to:', response.data.data.redirect);
                errorContainer.classList.add('hidden');
                
                // Ensure redirect URL exists
                if (!response.data.data.redirect) {
                    throw new Error('No redirect URL in response!');
                }
                
                window.location.href = response.data.data.redirect;
            } else {
                // Session tidak ter-set, tampilkan error
                console.log('Step 6b: Auth check failed');
                errorContainer.textContent = 'Session tidak ter-set. Silakan coba lagi. [' + authCheck.data.message + ']';
                errorContainer.classList.remove('hidden');
            }
        } else {
            // Login gagal
            console.log('Step 3b: Login failed:', response.data.message);
            errorContainer.textContent = response.data.message;
            errorContainer.classList.remove('hidden');
        }
    } catch (error) {
        // Handle error
        console.error('Error during login:', error);
        console.error('Error details:', {
            message: error.message,
            response: error.response?.data,
            status: error.response?.status,
            config: error.config?.data
        });
        
        let errorMsg = 'Terjadi kesalahan saat login';
        
        if (error.response && error.response.data) {
            errorMsg = error.response.data.message;
            if (error.response.data.data) {
                // Handle validation errors
                const errors = error.response.data.data;
                for (const [field, messages] of Object.entries(errors)) {
                    errorMsg += '\n' + messages.join('\n');
                }
            }
        } else if (error.message) {
            errorMsg = error.message;
        }
        
        errorContainer.textContent = errorMsg;
        errorContainer.classList.remove('hidden');
        alert('🚨 ' + errorMsg); // Also show alert to make sure user sees the error
    } finally {
        // Re-enable button
        loginBtn.disabled = false;
        loginBtn.textContent = 'Masuk →';
    }
}

// Log when page loads to verify JS is working
console.log('Login page loaded, checking for required elements...');
console.log('Form element:', document.getElementById('loginForm'));
console.log('Button element:', document.getElementById('loginBtn'));
console.log('Error container:', document.getElementById('errorContainer'));
</script>

@endsection

####################################################################################################################################################################################################################################################################################################################################################################################################################################################
####################################################################################################################################################################################################################################################################################################################################################################################################################################################
//pembelajaran mobile interactive
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
        <div id="materiContainer" class="grid md:grid-cols-3 gap-6"></div></div>

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

######################################################################################################################################################################################################################################################################################################################################################################################################################################################

########################################################################################################################################################################################################################################################################################################################################################################################
//materi-kuis
@extends('components.layout')
@section('title', 'lanjutkan-materi')

@section('content')

@include('components.header')

<div class="flex min-h-screen">

    <!-- SIDEBAR MATERI -->
    <aside class="w-72 bg-white border-r p-6">
        <h2 class="font-bold text-lg mb-4">
            Progress Belajar
        </h2>

        <!-- PROGRESS BAR -->
        <div class="mb-6">
            <div class="flex justify-between text-sm mb-1">
                <span>Progress</span>
                <span id="progressText" class="font-medium text-blue-600">0%</span>
            </div>

            <div class="w-full bg-gray-200 rounded-full h-2">
                <div id="progressBar" class="bg-blue-600 h-2 rounded-full" style="width:0%"></div>
            </div>
        </div>

        <h2 class="font-semibold text-md mb-3">
            Daftar Materi
        </h2>

        <div id="daftarMateri" class="space-y-2 text-sm"></div>

    </aside>


    <!-- HALAMAN KUIS -->
    <main class="flex-1 p-8">

        <!-- BUTTON KEMBALI -->
        <a href="/lanjutkan-materi/{{ $materiId }}"
        class="inline-flex items-center gap-2 text-gray-600 hover:text-red-500 mb-6 transition">
            <i class="fas fa-times text-lg"></i>
            <span>Kembali</span>
        </a>

        <div id="quizHeader" class="text-center mt-10">
            <div class="flex justify-between items-center mb-3 text-sm text-gray-600 font-medium">
                <div>
                    Soal 
                    <span id="nomorSoal" class="text-gray-900 font-semibold">1</span> 
                    dari 
                    <span id="totalSoal" class="text-gray-900 font-semibold">0</span>
                </div>
                
                <div class="flex items-center gap-2 bg-gray-100 text-gray-800 px-4 py-1.5 rounded-full font-semibold border">
                    <i class="fa-solid fa-clock"></i>
                    <span id="timer">00:00</span>
                </div>
                
            </div>
            
            <!-- PROGRESS BAR -->
            <div class="w-full bg-gray-200 rounded-full h-2 mb-8">
                <div id="progressSoal" class="bg-blue-500 h-2 rounded-full" style="width:0%"></div>
            </div>
        </div>


        <!-- CARD SOAL -->
        <div id="cardSoal" class="bg-white border rounded-xl p-6"></div>

        <div id="hasilContainer" class="hidden text-center mt-10"></div>

        <div id="modalKonfirmasi" class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50">

            <!-- BOX POPUP -->
            <div id="modalBox" class="bg-white rounded-xl p-6 w-80 text-center border-2 border-blue-500">

                <h2 class="text-lg font-semibold mb-6">
                    Apakah anda sudah yakin ?
                </h2>

                <div class="flex justify-center gap-4">
                    
                    <button onclick="konfirmasiSubmit()"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                        Ya
                    </button>

                    <button onclick="tutupModal()"
                        class="bg-gray-300 hover:bg-gray-400 text-white px-6 py-2 rounded-lg">
                        Tidak
                    </button>

                </div>

            </div>

        </div>
    </main>

</div>


<script>
let materiId = "{{ $materiId }}";
let currentQuestion = 0;
let totalQuestion = 0;
let soalList = [];
let jawabanUser = {};
let waktuPengerjaan = 0;
let timerInterval;
let timeLeft = 0;

document.addEventListener("DOMContentLoaded", function(){
    const savedQuestion = localStorage.getItem("quiz_current_question");
    const savedJawaban = localStorage.getItem("quiz_jawaban");

    if(savedQuestion){
        currentQuestion = parseInt(savedQuestion);
    }

    if(savedJawaban){
        jawabanUser = JSON.parse(savedJawaban);
    }

    loadSidebarMateri();
    loadSoal();
});

async function loadSidebarMateri(){

    try{

        const response = await axios.get('/api/materi-lanjutkan/' + materiId);
        const data = response.data.data;

        // progress bar
        document.getElementById("progressText").innerText =
            data.progress_percent + "%";

        document.getElementById("progressBar").style.width =
            data.progress_percent + "%";

        renderSidebar(data.steps, data.urutan_selesai);

    }catch(error){

        console.error("Error load sidebar", error);

    }

}

function renderSidebar(steps, urutanSelesai){

    const container = document.getElementById("daftarMateri");

    container.innerHTML = "";

    steps.forEach(step => {

        let status = "lock";
        let icon = "fa-lock text-gray-400";
        let classItem = "text-gray-500";

        if(step.urutan <= urutanSelesai){   
            status = "selesai";
            icon = "fa-circle-check text-green-500";
            classItem = "";
        }
        else if(step.urutan === urutanSelesai + 1){
            status = "aktif";
            icon = "fa-play text-blue-500";
            classItem = "bg-blue-100 text-blue-600 font-medium";
        }

        if(status !== "lock"){

            container.innerHTML += `
            <a href="/lanjutkan-materi/${materiId}"
            class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-100 ${classItem}">
                <i class="fa-solid ${icon}"></i>
                <span>${step.judul}</span>
            </a>
            `;

        }else{

            container.innerHTML += `
            <div class="flex items-center gap-3 p-3 rounded-lg text-gray-500">
                <i class="fa-solid ${icon}"></i>
                <span>${step.judul}</span>
            </div>
            `;

        }

    });

}

async function loadSoal(){

    const response = await axios.get('/api/post-test-soal/' + materiId);

    soalList = response.data.data.soals;
    totalQuestion = response.data.data.total_soal;
    waktuPengerjaan = response.data.data.waktu_pengerjaan;

    startTimer();
    document.getElementById("totalSoal").innerText = totalQuestion;
    
    renderSoal();
}

function renderSoal(){
    let pilihanHTML = "";
    let tombolHTML = "";

    const soal = soalList[currentQuestion];

    if(soalList.length === 0){
        document.getElementById("cardSoal").innerHTML =
            "<p class='text-gray-500'>Soal tidak tersedia</p>";
        return;
    }

    document.getElementById("nomorSoal").innerText = currentQuestion + 1;
    
    let progress = ((currentQuestion + 1) / totalQuestion) * 100;
    document.getElementById("progressSoal").style.width = progress + "%";
    
    const pilihan = [
        soal.pilihan_1,
        soal.pilihan_2,
        soal.pilihan_3,
        soal.pilihan_4,
        soal.pilihan_5
    ];

    if(currentQuestion < totalQuestion - 1){
        tombolHTML = `
            <div class="flex justify-end mt-8">
                <button onclick="nextSoal()"
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                    Selanjutnya
                </button>
            </div>
        `;

    }else{

        tombolHTML = `
            <div class="flex justify-center mt-8">
                <button onclick="bukaModal()"
                class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-2 rounded-lg">
                    Kirim
                </button>
            </div>
        `;

    }

    pilihan.forEach((p, index) => {

        if(p){

            pilihanHTML += `
            <label class="flex items-center gap-3 p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                <input type="${soal.status_pilihan ? 'checkbox' : 'radio'}"
                name="soal"
                value="${index+1}"
                class="w-4 h-4">
                <span>${p}</span>
            </label>
            `;

        }

    });

    document.getElementById("cardSoal").innerHTML = `
        <div class="flex justify-between items-start mb-6">
            <h2 class="text-xl font-semibold leading-relaxed max-w-xl">
                ${soal.soal}
            </h2>

            <span class="flex item-center text-sm gap-3 bg-blue-100 text-blue-600 font-medium px-4 py-1.5 rounded-full">
                Poin: ${soal.poin}
            </span>
        </div>

        <div class="space-y-4">
            ${pilihanHTML}
        </div>

        ${tombolHTML}
    `;
}

function nextSoal(){

    simpanJawaban();

    currentQuestion++;
    localStorage.setItem("quiz_current_question", currentQuestion);

    renderSoal();
}

function simpanJawaban(){

    const soal = soalList[currentQuestion];

    const checked = document.querySelectorAll('input[name="soal"]:checked');

    let jawaban = [];

    checked.forEach(item => {
        jawaban.push(item.value);
    });

    jawabanUser[soal.soal_id] = jawaban;
    localStorage.setItem("quiz_jawaban", JSON.stringify(jawabanUser));
}

function pilihJawaban(jawaban){

    const soal = soalList[currentQuestion];

    // simpan jawaban user
    jawabanUser[soal.soal_id] = jawaban;

    // pindah ke soal berikutnya
    setTimeout(() => {

        currentQuestion++;

        if(currentQuestion < totalQuestion){

            renderSoal();

        }else{

            selesaiKuis();

        }

    }, 300); // delay kecil agar user melihat pilihannya
}

function selesaiKuis(){

    document.getElementById("cardSoal").innerHTML = `
        <div class="text-center py-10">
            <h2 class="text-2xl font-semibold mb-3">
                Kuis Selesai
            </h2>
            <p class="text-gray-500">
                Terima kasih sudah mengerjakan kuis.
            </p>
        </div>
    `;

    console.log("Jawaban user:", jawabanUser);
}

function startTimer(){
    const savedTime = localStorage.getItem("quiz_time_left");

    if(savedTime){
        timeLeft = parseInt(savedTime);
    }else{
        timeLeft = waktuPengerjaan;
    }

    updateTimerDisplay(timeLeft);

    timerInterval = setInterval(() => {

        timeLeft--;

        localStorage.setItem("quiz_time_left", timeLeft);

        updateTimerDisplay(timeLeft);

        if(timeLeft <= 0){

            clearInterval(timerInterval);

            localStorage.removeItem("quiz_time_left");

            alert("Waktu habis! Jawaban akan otomatis dikirim.");

            submitKuis();
        }
    }, 1000);
}

function updateTimerDisplay(seconds){
    if(seconds < 0){
        seconds = 0;
    }

    let minutes = Math.floor(seconds / 60);
    let secs = seconds % 60;

    minutes = minutes < 10 ? "0" + minutes : minutes;
    secs = secs < 10 ? "0" + secs : secs;

    document.getElementById("timer").innerText =
        minutes + ":" + secs;
}

function bukaModal(){
    const modal = document.getElementById("modalKonfirmasi");
    modal.classList.remove("hidden");
    modal.classList.add("flex");
}

function tutupModal(){
    const modal = document.getElementById("modalKonfirmasi");
    modal.classList.add("hidden");
    modal.classList.remove("flex");
}

function konfirmasiSubmit(){
    tutupModal();
    submitKuis();
}

document.getElementById("modalKonfirmasi").addEventListener("click", function(e){

    const modalBox = document.getElementById("modalBox");

    if(!modalBox.contains(e.target)){
        tutupModal();
    }

});

async function submitKuis(){
    localStorage.removeItem("quiz_time_left");
    localStorage.removeItem("quiz_current_question");
    localStorage.removeItem("quiz_jawaban");
    clearInterval(timerInterval);
    simpanJawaban();

    try{

        const response = await axios.post('/api/post-test-submit', {
            materi_id: materiId,
            jawaban: jawabanUser
        });

        const data = response.data;

        document.getElementById("quizHeader").style.display = "none";
        document.getElementById("cardSoal").style.display = "none";
        document.getElementById("hasilContainer").classList.remove("hidden");
        
        let tombolUlang = "";

        // jika nilai < 75 → tampilkan tombol ulang
        if(data.skor < 75){

            tombolUlang = `
                <button onclick="mulaiUlangKuis()"
                class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-lg">
                    Mulai Ulang Test
                </button>
            `;
        }

        document.getElementById("hasilContainer").innerHTML = `
            <div class="text-center py-10">
                <h2 class="text-3xl font-bold text-black">
                    ${data.skor} %
                </h2>
                <p class="text-gray-500 mb-3">
                    FINAL SCORE
                </p>

                ${
                    data.skor >= 75
                    ? `<p class="text-green-600 font-semibold">Lulus</p>`
                    : `<p class="text-red-500 font-semibold">Belum Lulus</p>`
                }

                <p class="text-gray-500 mt-3">
                    Kuis berhasil disubmit. Terima kasih sudah menyelesaikan materi ini.
                </p>
            </div>

            ${tombolUlang}

            <div class="mt-6">
                <a href="/detail-materi/${materiId}"
                class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition">
                    Kembali ke Materi
                </a>
            </div>
        `;

    }catch(error){

        console.error(error);

    }

}

async function mulaiUlangKuis(){

    try{

        await axios.post('/api/post-test-start', {
            materi_id: materiId
        });

        // reset state quiz
        localStorage.removeItem("quiz_time_left");
        localStorage.removeItem("quiz_current_question");
        localStorage.removeItem("quiz_jawaban");

        // reload halaman
        window.location.reload();

    }catch(error){

        if(error.response && error.response.status === 403){
            alert("Percobaan sudah habis!");
        }else{
            console.error(error);
        }

    }
}


</script>
@endsection

#########################################################################################################################################################################################################################################################################################################################################################################################

###########################################################################################################################################################################################################################################################################################################################################################################################