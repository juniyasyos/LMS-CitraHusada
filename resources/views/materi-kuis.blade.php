@extends('components.layout')
@section('title', 'lanjutkan-materi')

@section('content')

<!-- HEADER DESKTOP -->
<div class="hidden lg:block">
    @include('components.header')
</div>

<div class="flex flex-col lg:flex-row min-h-screen">

    <!-- SIDEBAR -->
    <aside class="order-2 lg:order-1 w-full lg:w-72 bg-white border-t lg:border-t-0 lg:border-r p-4 lg:p-6">

        <h2 class="font-bold text-base lg:text-lg mb-4">Progress Belajar</h2>

        <div class="mb-6">
            <div class="flex justify-between text-sm mb-1">
                <span>Progress</span>
                <span id="progressText" class="font-medium text-blue-600">0%</span>
            </div>

            <div class="w-full bg-gray-200 rounded-full h-2">
                <div id="progressBar" class="bg-blue-600 h-2 rounded-full" style="width:0%"></div>
            </div>
        </div>

        <h2 class="font-semibold text-sm lg:text-md mb-3">Daftar Materi</h2>

        <div id="daftarMateri" class="space-y-2 text-sm"></div>

    </aside>

    <!-- MAIN -->
    <main class="order-1 lg:order-2 flex-1 p-4 sm:p-6 lg:p-8">

        <!-- MOBILE TITLE -->
        <div class="lg:hidden mb-4">
            <h1 class="text-xl font-bold text-gray-800">Kuis</h1>
        </div>

        <!-- BUTTON BACK -->
        <a href="/lanjutkan-materi/{{ $materiId }}"
        class="inline-flex items-center gap-2 text-gray-600 hover:text-red-500 mb-6 transition">
            <i class="fas fa-times text-lg"></i>
            <span>Kembali</span>
        </a>

        <!-- HEADER QUIZ -->
        <div id="quizHeader">

            <div class="flex justify-between items-center mb-3 text-xl text-black font-bold">

                <div>
                    Soal 
                    <span id="nomorSoal" class="text-gray-900 font-bold">0</span> 
                    dari 
                    <span id="totalSoal" class="text-gray-900 font-bold">0</span>
                </div>

                <div class="flex items-center gap-2 text-sm bg-gray-100 rounded-full px-2 py-1">
                    <i class="fa-solid fa-clock text-gray-500"></i>
                    <span id="timer">00:00</span>
                </div>

            </div>

            <!-- PROGRESS -->
            <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                <div id="progressSoal" class="bg-blue-500 h-2 rounded-full" style="width:0%"></div>
            </div>

            <div class="mb-8 text-right">
                <span id="poinSoal" class="text-sm text-blue-600 font-medium bg-blue-100 rounded-full px-2 py-1">
                    Poin : 0
                </span>
            </div>

        </div>

        <!-- CARD SOAL -->
        <div id="cardSoal" class="bg-white border rounded-xl p-4 sm:p-6"></div>

        <!-- HASIL -->
        <div id="hasilContainer" class="hidden text-center mt-10"></div>

    </main>

</div>

<!-- MODAL -->
<div id="modalKonfirmasi" class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50">
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

        document.getElementById("progressText").innerText = data.progress_percent + "%";
        document.getElementById("progressBar").style.width = data.progress_percent + "%";

        renderSidebar(data.steps, data.urutan_selesai);

    }catch(error){
        console.error(error);
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
            <a href="/lanjutkan-materi/${materiId}?step=${step.urutan}"
            class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-100 ${classItem}">
                <i class="fa-solid ${icon}"></i>
                <span>${step.judul}</span>
            </a>`;
        }else{
            container.innerHTML += `
            <div class="flex items-center gap-3 p-3 rounded-lg text-gray-500">
                <i class="fa-solid ${icon}"></i>
                <span>${step.judul}</span>
            </div>`;
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
    document.getElementById("poinSoal").innerText = "Poin : " + soal.poin;

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
        </div>`;
    }else{
        tombolHTML = `
        <div class="flex justify-center mt-8">
            <button onclick="bukaModal()"
            class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-2 rounded-lg">
                Kirim
            </button>
        </div>`;
    }

    pilihan.forEach((p, index) => {
        if(p){
            pilihanHTML += `
            <label class="flex items-start gap-3 p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                <input type="${soal.status_pilihan ? 'checkbox' : 'radio'}"
                name="soal"
                value="${index+1}"
                class="mt-1 w-4 h-4">
                <span>${p}</span>
            </label>`;
        }
    });

    document.getElementById("cardSoal").innerHTML = `
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-3 mb-6">
            <h2 class="text-lg sm:text-xl font-semibold leading-relaxed sm:max-w-xl">
                ${soal.soal}
            </h2>

            
        </div>

        <div class="space-y-3 sm:space-y-4">
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