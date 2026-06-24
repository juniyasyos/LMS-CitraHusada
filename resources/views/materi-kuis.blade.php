@extends('components.layout')
@section('title', 'lanjutkan-materi')

@section('content')

    @php
        $quotes = \App\Models\MotivationQuote::all();
        $quotesAwal = $quotes->where('kondisi', 'awal kuis')->values();
        $quotesTengah = $quotes->where('kondisi', 'kelipatan 3')->values();
        $quotesAkhir = $quotes->where('kondisi', 'akhir kuis')->values();
    @endphp

    <!-- POPUP Awal Kuis -->
    <div id="popupWelcome" class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50">
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-lg w-[90%] max-w-md p-6 relative text-center border dark:border-slate-800">
            <div class="flex justify-center mb-4">
                <lottie-player src="https://lottie.host/363a0f93-0231-4ecf-9d9c-77a30aa4cc4d/hY7zUek7Du.json"
                    background="transparent" style="width:120px;height:120px;" autoplay></lottie-player>
            </div>
            <div>
                <h3 id="welcomeTitle" class="font-semibold text-lg mb-2 text-gray-900 dark:text-white"></h3>
                <p id="welcomeDesc" class="text-gray-500 dark:text-gray-400 text-sm mb-6"></p>
                <button onclick="mulaiDariPopup()"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg w-full transition-colors">Mulai Kuis</button>
            </div>
        </div>
    </div>

    <!-- POPUP Motivasi (Tengah) -->
    <div id="popupMotivasi" class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50">
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-lg w-[90%] max-w-md p-6 relative text-center border dark:border-slate-800">
            <div class="flex justify-center mb-4">
                <lottie-player src="https://lottie.host/7559d986-bb08-4257-afdc-7a9253af9a54/c2F2tTQjjS.json"
                    background="transparent" style="width:120px;height:120px;" loop autoplay></lottie-player>
            </div>
            <div>
                <h3 id="motivasiTitle" class="font-semibold text-lg mb-2 text-gray-900 dark:text-white"></h3>
                <p id="motivasiDesc" class="text-gray-500 dark:text-gray-400 text-sm"></p>
            </div>
        </div>
    </div>

    <!-- HEADER DESKTOP -->
    <div class="hidden lg:block">
        @include('components.header')
    </div>

    <div class="flex flex-col lg:flex-row min-h-screen bg-slate-50 dark:bg-slate-950 transition-colors duration-300">

        <!-- SIDEBAR -->
        <aside class="order-2 lg:order-1 w-full lg:w-72 bg-white dark:bg-slate-900 border-t dark:border-slate-800 lg:border-t-0 lg:border-r lg:dark:border-slate-800 p-4 lg:p-6 transition-colors duration-300">

            <h2 class="font-bold text-base lg:text-lg mb-4 text-gray-900 dark:text-white">Progress Belajar</h2>

            <div class="mb-6">
                <div class="flex justify-between text-sm mb-1 text-gray-900 dark:text-white">
                    <span>Progress</span>
                    <span id="progressText" class="font-medium text-blue-600 dark:text-blue-400">0%</span>
                </div>

                <div class="w-full bg-gray-200 dark:bg-slate-700 rounded-full h-2">
                    <div id="progressBar" class="bg-blue-600 h-2 rounded-full" style="width:0%"></div>
                </div>
            </div>

            <h2 class="font-semibold text-sm lg:text-md mb-3 text-gray-900 dark:text-white">Daftar Materi</h2>

            <div id="daftarMateri" class="space-y-2 text-sm"></div>

        </aside>

        <!-- MAIN -->
        <main class="order-1 lg:order-2 flex-1 p-4 sm:p-6 lg:p-8">

            <!-- MOBILE TITLE -->
            <div class="lg:hidden mb-4">
                <h1 class="text-xl font-bold text-gray-800 dark:text-white">Kuis</h1>
            </div>

            <!-- BUTTON BACK -->
            <a href="/lanjutkan-materi/{{ $materiId }}"
                class="inline-flex items-center gap-2 text-gray-600 dark:text-gray-400 hover:text-red-500 dark:hover:text-red-400 mb-6 transition">
                <i class="fas fa-times text-lg"></i>
                <span>Kembali</span>
            </a>

            <!-- HEADER QUIZ -->
            <div id="quizHeader">

                <div class="flex justify-between items-center mb-3 text-xl text-black dark:text-white font-bold">

                    <div>
                        Soal
                        <span id="nomorSoal" class="text-gray-900 dark:text-gray-100 font-bold">0</span>
                        dari
                        <span id="totalSoal" class="text-gray-900 dark:text-gray-100 font-bold">0</span>
                    </div>

                    <div class="flex items-center gap-2 text-sm bg-gray-100 dark:bg-slate-800 rounded-full px-2 py-1">
                        <i class="fa-solid fa-clock text-gray-500 dark:text-gray-400"></i>
                        <span id="timer" class="dark:text-gray-200">00:00</span>
                    </div>

                </div>

                <!-- PROGRESS -->
                <div class="w-full bg-gray-200 dark:bg-slate-700 rounded-full h-2 mb-2">
                    <div id="progressSoal" class="bg-blue-500 h-2 rounded-full" style="width:0%"></div>
                </div>

                <div class="mb-8 text-right">
                    <span id="poinSoal" class="text-sm text-blue-600 dark:text-blue-400 font-medium bg-blue-100 dark:bg-blue-900/30 rounded-full px-2 py-1">
                        Poin : 0
                    </span>
                </div>

            </div>

            <!-- CARD SOAL -->
            <div id="cardSoal" class="bg-white dark:bg-slate-900 border dark:border-slate-800 rounded-xl p-4 sm:p-6 transition-colors duration-300"></div>

            <!-- HASIL -->
            <div id="hasilContainer" class="hidden text-center mt-10"></div>

        </main>

        <!-- RIGHT SIDEBAR (Navigasi Soal) -->
        <aside class="order-3 lg:order-3 w-full lg:w-72 bg-white dark:bg-slate-900 border-t lg:border-t-0 lg:border-l dark:border-slate-800 p-4 lg:p-6 transition-colors duration-300">
            <h2 class="font-bold text-base lg:text-lg mb-4 text-gray-900 dark:text-white">Navigasi Soal</h2>
            <div id="navigasiSoal" class="grid grid-cols-5 gap-2"></div>
        </aside>

    </div>

    <!-- MODAL -->
    <div id="modalKonfirmasi" class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center z-50">
        <div id="modalBox" class="bg-white dark:bg-slate-900 rounded-xl p-6 w-[90%] max-w-md text-center border-2 border-blue-500">
            <div class="flex justify-center mb-4">
                <lottie-player src="https://assets10.lottiefiles.com/packages/lf20_touohxv0.json" background="transparent"
                    style="width:130px;height:130px;" autoplay></lottie-player>
            </div>
            <h2 class="text-lg font-semibold mb-2 text-gray-900 dark:text-white">
                Apakah anda sudah yakin ?
            </h2>
            <div class="mb-6">
                <p id="konfirmasiTitle" class="font-semibold text-gray-700 dark:text-gray-300 text-sm"></p>
                <p id="konfirmasiDesc" class="text-sm text-gray-500 dark:text-gray-400"></p>
            </div>

            <div class="flex justify-center gap-4">
                <button onclick="konfirmasiSubmit()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                    Ya
                </button>

                <button onclick="tutupModal()" class="bg-gray-300 hover:bg-gray-400 text-white px-6 py-2 rounded-lg">
                    Tidak
                </button>
            </div>
        </div>
    </div>

    <!-- TOAST PERCOBAAN HABIS -->
    <div id="quizLimitAlert"
        class="fixed top-6 left-1/2 transform -translate-x-1/2 z-50 bg-white dark:bg-slate-900 border-l-4 border-red-500 text-gray-800 dark:text-white px-6 py-4 rounded-xl shadow-[0_8px_30px_rgb(0,0,0,0.12)] flex items-center gap-4 transition-all duration-300 opacity-0 -translate-y-10 pointer-events-none min-w-[320px]">

        <!-- ICON -->
        <div class="bg-red-50 dark:bg-red-500/20 text-red-500 rounded-full w-10 h-10 flex items-center justify-center shrink-0">
            <i class="fa-solid fa-circle-xmark text-lg"></i>
        </div>

        <!-- TEXT -->
        <div>
            <h4 class="font-bold text-sm sm:text-base text-gray-900 dark:text-white">
                Percobaan Habis
            </h4>
            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                Kamu sudah mencapai batas maksimal percobaan kuis.
            </p>
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
        let quizAlertTimeout;

        const quotesAwal = @json($quotesAwal);
        const quotesTengah = @json($quotesTengah);
        const quotesAkhir = @json($quotesAkhir);

        const urlParamsKuis = new URLSearchParams(window.location.search);
        const postTestId = urlParamsKuis.get("post_test_id");

        document.addEventListener("DOMContentLoaded", function () {
            const savedQuestion = localStorage.getItem("quiz_current_question");
            const savedJawaban = localStorage.getItem("quiz_jawaban");

            if (savedQuestion) {
                currentQuestion = parseInt(savedQuestion);
            }

            if (savedJawaban) {
                jawabanUser = JSON.parse(savedJawaban);
            }

            loadSidebarMateri();
            loadSoal();
        });

        function showQuizLimitAlert() {
            const alertBox = document.getElementById('quizLimitAlert');

            if (quizAlertTimeout) clearTimeout(quizAlertTimeout);

            alertBox.classList.remove('opacity-0', '-translate-y-10', 'pointer-events-none');
            alertBox.classList.add('opacity-100', 'translate-y-0');

            quizAlertTimeout = setTimeout(() => {
                alertBox.classList.remove('opacity-100', 'translate-y-0');
                alertBox.classList.add('opacity-0', '-translate-y-10', 'pointer-events-none');
            }, 3000);
        }

        async function loadSidebarMateri() {
            try {
                const response = await axios.get('/api/materi-lanjutkan/' + materiId);
                const data = response.data.data;

                document.getElementById("progressText").innerText = data.progress_percent + "%";
                document.getElementById("progressBar").style.width = data.progress_percent + "%";

                renderSidebar(data.steps, data.urutan_selesai);

            } catch (error) {
                console.error(error);
            }
        }

        function renderSidebar(steps, urutanSelesai) {
            const container = document.getElementById("daftarMateri");
            container.innerHTML = "";

            const activeStep = steps.find(s => s.type === 'post_test' && s.id == postTestId) || steps.find(s => s.type === 'post_test');
            const activeUrutan = activeStep ? activeStep.urutan : null;

            steps.forEach(step => {
                let isOpened = step.urutan === activeUrutan;
                let isUnlocked = step.urutan <= urutanSelesai + 1;

                let icon = "fa-lock text-gray-400 dark:text-gray-500";
                let classItem = "text-gray-400 dark:text-gray-500 cursor-not-allowed";
                let status = "lock";

                if (isOpened) {
                    icon = "fa-play text-blue-500 dark:text-blue-400";
                    classItem = "bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 font-medium";
                    status = "aktif";
                } else if (isUnlocked) {
                    if (step.urutan <= urutanSelesai) {
                        icon = "fa-circle-check text-green-500 dark:text-green-400";
                        classItem = "text-gray-800 dark:text-gray-200 font-medium hover:bg-gray-100 dark:hover:bg-slate-800";
                        status = "selesai";
                    } else {
                        icon = "fa-unlock text-gray-600 dark:text-gray-400";
                        classItem = "text-gray-800 dark:text-gray-200 font-medium hover:bg-gray-100 dark:hover:bg-slate-800";
                        status = "unlocked";
                    }
                }

                if (status !== "lock") {
                    container.innerHTML += `
                    <a href="/lanjutkan-materi/${materiId}?step=${step.urutan}"
                    class="flex items-center gap-3 p-3 rounded-lg transition-colors ${classItem}">
                        <i class="fa-solid ${icon}"></i>
                        <span>${step.judul}</span>
                    </a>`;
                } else {
                    container.innerHTML += `
                    <div class="flex items-center gap-3 p-3 rounded-lg ${classItem}">
                        <i class="fa-solid ${icon}"></i>
                        <span>${step.judul}</span>
                    </div>`;
                }
            });
        }

        async function loadSoal() {
            try {
                const url = postTestId ? '/api/post-test-soal/' + materiId + '?post_test_id=' + postTestId : '/api/post-test-soal/' + materiId;
                const response = await axios.get(url);

                soalList = response.data.data.soals;
                totalQuestion = response.data.data.total_soal;
                waktuPengerjaan = response.data.data.waktu_pengerjaan;

                document.getElementById("totalSoal").innerText = totalQuestion;

                const savedTime = localStorage.getItem("quiz_time_left");
                if (savedTime) {
                    startTimer();
                    renderSoal();
                } else {
                    showWelcomePopup();
                }
                
                renderNavigasiSoal();

            } catch (error) {
                if (error.response && error.response.status === 403) {
                    // alert('Akses kuis tidak valid atau sesi berakhir. Coba mulai ulang kuis resmi dari tombol.');
                    window.location.href = '/lanjutkan-materi/' + materiId;
                } else {
                    console.error("Gagal memuat soal", error);
                }
            }
        }

        function renderSoal() {
            let pilihanHTML = "";
            let tombolHTML = "";

            const soal = soalList[currentQuestion];

            if (soalList.length === 0) {
                document.getElementById("cardSoal").innerHTML =
                    "<p class='text-gray-500 dark:text-gray-400'>Soal tidak tersedia</p>";
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

            // Cek jika jawaban sudah tersimpan sebelumnya
            let savedJawabanSoal = jawabanUser[soal.soal_id] || [];

            let prevButton = currentQuestion > 0 ? `
                <button onclick="prevSoal()" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition-colors duration-300">
                    Sebelumnya
                </button>
            ` : `<button disabled class="bg-gray-300 text-white px-6 py-2 rounded-lg cursor-not-allowed">Sebelumnya</button>`;

            if (currentQuestion < totalQuestion - 1) {
                tombolHTML = `
                <div class="flex justify-between mt-8">
                    ${prevButton}
                    <button id="btnLanjut" onclick="nextSoal()" disabled
                    class="bg-blue-300 pointer-events-none text-white px-6 py-2 rounded-lg transition-colors duration-300">
                        Selanjutnya
                    </button>
                </div>`;
            } else {
                tombolHTML = `
                <div class="flex justify-between mt-8">
                    ${prevButton}
                    <button id="btnKirim" onclick="bukaModal()" disabled
                    class="bg-blue-300 pointer-events-none text-white px-8 py-2 rounded-lg transition-colors duration-300">
                        Kirim
                    </button>
                </div>`;
            }

            pilihan.forEach((p, index) => {
                if (p) {
                    let isChecked = savedJawabanSoal.includes((index + 1).toString()) ? 'checked' : '';
                    pilihanHTML += `
                    <label class="flex items-start gap-3 p-3 border dark:border-slate-700 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-slate-800 text-gray-800 dark:text-gray-200">
                        <input type="${soal.status_pilihan ? 'checkbox' : 'radio'}"
                        name="soal"
                        value="${index + 1}"
                        ${isChecked}
                        onchange="cekPilihan()"
                        class="mt-1 w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 dark:bg-slate-700 dark:border-slate-600">
                        <span>${p}</span>
                    </label>`;
                }
            });

            document.getElementById("cardSoal").innerHTML = `
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-3 mb-6">
                    <h2 class="text-lg sm:text-xl font-semibold leading-relaxed sm:max-w-xl text-gray-900 dark:text-white">
                        ${soal.soal}
                    </h2>


                </div>

                <div class="space-y-3 sm:space-y-4">
                    ${pilihanHTML}
                </div>

                ${tombolHTML}
            `;
            
            cekPilihan();
            renderNavigasiSoal();
        }

        function showWelcomePopup() {
            const popup = document.getElementById('popupWelcome');
            if (quotesAwal.length > 0) {
                const rand = Math.floor(Math.random() * quotesAwal.length);
                const quote = quotesAwal[rand];
                document.getElementById('welcomeTitle').innerText = quote.judul;
                document.getElementById('welcomeDesc').innerText = quote.deskripsi;
            }
            popup.classList.remove('hidden');
            popup.classList.add('flex');
        }

        function mulaiDariPopup() {
            const popup = document.getElementById('popupWelcome');
            popup.classList.add('hidden');
            popup.classList.remove('flex');
            startTimer();
            renderSoal();
            renderNavigasiSoal();
        }

        function renderNavigasiSoal() {
            let navHTML = '';
            for (let i = 0; i < totalQuestion; i++) {
                let soalId = soalList[i].soal_id;
                let isAnswered = jawabanUser[soalId] && jawabanUser[soalId].length > 0;
                let isCurrent = i === currentQuestion;
                
                let bgClass = "bg-gray-200 dark:bg-slate-700 text-gray-700 dark:text-gray-300"; // Belum dijawab (abu-abu)
                
                if (isCurrent) {
                    bgClass = "bg-blue-500 text-white border-2 border-blue-700 dark:border-blue-400 shadow-md"; // Sedang dibuka (biru)
                } else if (isAnswered) {
                    bgClass = "bg-green-500 text-white shadow-md"; // Sudah dijawab (hijau)
                }

                navHTML += `
                <button onclick="goToSoal(${i})" class="${bgClass} font-semibold py-2 rounded-lg text-sm transition-colors duration-200">
                    ${i + 1}
                </button>
                `;
            }
            document.getElementById("navigasiSoal").innerHTML = navHTML;
        }

        function goToSoal(index) {
            simpanJawaban();
            currentQuestion = index;
            localStorage.setItem("quiz_current_question", currentQuestion);
            renderSoal();
        }

        function prevSoal() {
            if (currentQuestion > 0) {
                simpanJawaban();
                currentQuestion--;
                localStorage.setItem("quiz_current_question", currentQuestion);
                renderSoal();
            }
        }

        function cekPilihan() {
            simpanJawaban();
            renderNavigasiSoal();

            const checked = document.querySelectorAll('input[name="soal"]:checked');
            const btn = document.getElementById("btnLanjut");
            const btnKirim = document.getElementById("btnKirim");
            if (checked.length > 0) {
                if (btn) {
                    btn.disabled = false;
                    btn.className = "bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors duration-300";
                }
                if (btnKirim) {
                    btnKirim.disabled = false;
                    btnKirim.className = "bg-blue-600 hover:bg-blue-700 text-white px-8 py-2 rounded-lg transition-colors duration-300";
                }
            } else {
                if (btn) {
                    btn.disabled = true;
                    btn.className = "bg-blue-300 pointer-events-none text-white px-6 py-2 rounded-lg transition-colors duration-300";
                }
                if (btnKirim) {
                    btnKirim.disabled = true;
                    btnKirim.className = "bg-blue-300 pointer-events-none text-white px-8 py-2 rounded-lg transition-colors duration-300";
                }
            }
        }

        function nextSoal() {

            simpanJawaban();

            currentQuestion++;
            localStorage.setItem("quiz_current_question", currentQuestion);

            if (currentQuestion % 3 === 0 && currentQuestion !== totalQuestion) {
                const popup = document.getElementById('popupMotivasi');
                if (quotesTengah.length > 0) {
                    const rand = Math.floor(Math.random() * quotesTengah.length);
                    const quote = quotesTengah[rand];
                    document.getElementById('motivasiTitle').innerText = quote.judul;
                    document.getElementById('motivasiDesc').innerText = quote.deskripsi;
                }
                popup.classList.remove('hidden');
                popup.classList.add('flex');
                setTimeout(() => {
                    popup.classList.add('hidden');
                    popup.classList.remove('flex');
                }, 2000);
            }

            renderSoal();
        }

        function simpanJawaban() {

            const soal = soalList[currentQuestion];

            const checked = document.querySelectorAll('input[name="soal"]:checked');

            let jawaban = [];

            checked.forEach(item => {
                jawaban.push(item.value);
            });

            jawabanUser[soal.soal_id] = jawaban;
            localStorage.setItem("quiz_jawaban", JSON.stringify(jawabanUser));
        }

        function pilihJawaban(jawaban) {

            const soal = soalList[currentQuestion];

            // simpan jawaban user
            jawabanUser[soal.soal_id] = jawaban;

            // pindah ke soal berikutnya
            setTimeout(() => {

                currentQuestion++;

                if (currentQuestion < totalQuestion) {

                    renderSoal();

                } else {

                    selesaiKuis();

                }

            }, 300); // delay kecil agar user melihat pilihannya
        }

        function selesaiKuis() {

            document.getElementById("cardSoal").innerHTML = `
                <div class="text-center py-10">
                    <h2 class="text-2xl font-semibold mb-3 text-gray-900 dark:text-white">
                        Kuis Selesai
                    </h2>
                    <p class="text-gray-500 dark:text-gray-400">
                        Terima kasih sudah mengerjakan kuis.
                    </p>
                </div>
            `;

            console.log("Jawaban user:", jawabanUser);
        }

        function startTimer() {
            const savedTime = localStorage.getItem("quiz_time_left");

            if (savedTime) {
                timeLeft = parseInt(savedTime);
            } else {
                timeLeft = waktuPengerjaan;
            }

            updateTimerDisplay(timeLeft);

            timerInterval = setInterval(() => {

                timeLeft--;

                localStorage.setItem("quiz_time_left", timeLeft);

                updateTimerDisplay(timeLeft);

                if (timeLeft <= 0) {

                    clearInterval(timerInterval);

                    localStorage.removeItem("quiz_time_left");

                    alert("Waktu habis! Jawaban akan otomatis dikirim.");

                    submitKuis();
                }
            }, 1000);
        }

        function updateTimerDisplay(seconds) {
            if (seconds < 0) {
                seconds = 0;
            }

            let minutes = Math.floor(seconds / 60);
            let secs = seconds % 60;

            minutes = minutes < 10 ? "0" + minutes : minutes;
            secs = secs < 10 ? "0" + secs : secs;

            document.getElementById("timer").innerText =
                minutes + ":" + secs;
        }

        function bukaModal() {
            const modal = document.getElementById("modalKonfirmasi");
            if (quotesAkhir.length > 0) {
                const rand = Math.floor(Math.random() * quotesAkhir.length);
                const quote = quotesAkhir[rand];
                document.getElementById('konfirmasiTitle').innerText = quote.judul;
                document.getElementById('konfirmasiDesc').innerText = quote.deskripsi;
            }
            modal.classList.remove("hidden");
            modal.classList.add("flex");
        }

        function tutupModal() {
            const modal = document.getElementById("modalKonfirmasi");
            modal.classList.add("hidden");
            modal.classList.remove("flex");
        }

        function konfirmasiSubmit() {
            tutupModal();
            submitKuis();
        }

        function formatDurasi(detik) {
            let menit = Math.floor(detik / 60);
            let sisaDetik = detik % 60;
            if (menit > 0) return `${menit} Menit ${sisaDetik} Detik`;
            return `${sisaDetik} Detik`;
        }

        document.getElementById("modalKonfirmasi").addEventListener("click", function (e) {

            const modalBox = document.getElementById("modalBox");

            if (!modalBox.contains(e.target)) {
                tutupModal();
            }

        });

        async function submitKuis() {
            const durasiPengerjaan = waktuPengerjaan - (timeLeft > 0 ? timeLeft : 0);
            
            localStorage.removeItem("quiz_time_left");
            localStorage.removeItem("quiz_current_question");
            localStorage.removeItem("quiz_jawaban");
            clearInterval(timerInterval);
            simpanJawaban();

            try {

                const response = await axios.post('/api/post-test-submit', {
                    materi_id: materiId,
                    post_test_id: postTestId,
                    jawaban: jawabanUser,
                    waktu_pengerjaan: durasiPengerjaan
                });

                const data = response.data;

                document.getElementById("quizHeader").style.display = "none";
                document.getElementById("cardSoal").style.display = "none";
                document.getElementById("hasilContainer").classList.remove("hidden");

                if (data.is_locked_back) {
                    document.getElementById("hasilContainer").innerHTML = `
                        <div class="bg-white dark:bg-slate-900 border dark:border-slate-800 rounded-xl p-6 sm:p-8 max-w-xl mx-auto text-center shadow-sm border-red-200 dark:border-red-900/50">
                            <div class="text-4xl sm:text-5xl mb-4 text-red-500 dark:text-red-400">
                                <i class="fa-solid fa-rotate-left"></i>
                            </div>
                            <h2 class="text-xl sm:text-2xl font-bold mb-2 text-red-600 dark:text-red-500">
                                Kesempatan Habis!
                            </h2>
                            <p class="text-gray-600 dark:text-gray-400 mb-6 text-sm sm:text-base">
                                ${data.lockback_message}
                            </p>
                            <div class="mt-6 flex justify-center">
                                <a href="/lanjutkan-materi/${materiId}?step=1"
                                class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition">
                                    Mulai Dari Awal
                                </a>
                            </div>
                        </div>
                    `;
                    return;
                }

                let tombolUlang = "";

                // jika nilai < 75 → tampilkan tombol ulang
                if (data.skor_kuis_ini < 75) {

                    tombolUlang = `
                        <button onclick="mulaiUlangKuis()"
                        class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-lg">
                            Mulai Ulang Test
                        </button>
                    `;
                }

                let tombolLanjut = "";

                if (!data.is_last_step) {
                    tombolLanjut = `
                        <a href="/lanjutkan-materi/${materiId}?step=${data.next_step}"
                        class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition">
                            Materi Selanjutnya
                        </a>
                    `;
                } else if (data.is_last_step && data.skor_kuis_ini >= 75) {
                    tombolLanjut = `
                        <a href="/detail-materi/${materiId}"
                        class="inline-block bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition">
                            Kembali ke Materi
                        </a>
                    `;
                }

                document.getElementById("hasilContainer").innerHTML = `
                    ${data.skor_kuis_ini >= 75
                        ? `<div class="bg-white dark:bg-slate-900 border dark:border-slate-800 rounded-xl p-6 sm:p-8 max-w-xl mx-auto text-center shadow-sm">
                            <!-- ICON -->
                            <div class="text-green-500 dark:text-green-400 text-4xl sm:text-5xl mb-4">
                                <i class="fa-solid fa-circle-check"></i>
                            </div>

                            <!-- JUDUL -->
                            <h2 class="text-xl sm:text-2xl font-bold mb-2 text-gray-900 dark:text-white">
                                Selamat!
                            </h2>

                            <!-- DESKRIPSI -->
                            <p class="text-gray-600 dark:text-gray-400 mb-4 text-sm sm:text-base">
                                Kamu telah menyelesaikan kuis dengan nilai
                            </p>

                            <!-- NILAI -->
                            <div class="text-3xl sm:text-4xl font-bold text-blue-600 dark:text-blue-400 mb-2">
                                ${data.skor_kuis_ini} / 100
                            </div>

                            <!-- DURASI -->
                            <p class="text-[11px] text-gray-400 dark:text-gray-500 font-medium mb-6">
                                <i class="fa-regular fa-clock mr-1"></i> Waktu pengerjaan: ${formatDurasi(data.waktu_pengerjaan)}
                            </p>

                            <!-- PESAN -->
                            <p class="text-gray-500 dark:text-gray-400 mb-8 text-sm sm:text-base">
                                Hasil yang sangat baik! Kamu sudah memahami materi keselamatan pasien
                                dengan baik. Terus pertahankan semangat belajarmu.
                            </p>

                            <!-- BUTTON -->
                            <div class="mt-6 flex flex-wrap justify-center gap-3">
                                ${tombolUlang}
                                ${tombolLanjut}
                            </div>

                        </div>`
                        : `<div class="bg-white dark:bg-slate-900 border dark:border-slate-800 rounded-xl p-6 sm:p-8 max-w-xl mx-auto text-center shadow-sm">

                            <!-- ICON -->
                            <div class="text-4xl sm:text-5xl mb-4 text-red-500 dark:text-red-400">
                                <i class="fa-solid fa-circle-xmark"></i>
                            </div>

                            <!-- JUDUL -->
                            <h2 class="text-xl sm:text-2xl font-bold mb-2 text-gray-900 dark:text-white">
                                Tetap Semangat!
                            </h2>

                            <!-- DESKRIPSI -->
                            <p class="text-gray-600 dark:text-gray-400 mb-4 text-sm sm:text-base">
                                Nilai kamu masih di bawah KKM. Jangan menyerah, coba lagi ya!
                            </p>

                            <!-- NILAI -->
                            <div class="text-3xl sm:text-4xl font-bold mb-2 text-red-600 dark:text-red-500">
                                ${data.skor_kuis_ini} / 100
                            </div>

                            <!-- DURASI -->
                            <p class="text-[11px] text-gray-400 dark:text-gray-500 font-medium mb-6">
                                <i class="fa-regular fa-clock mr-1"></i> Waktu pengerjaan: ${formatDurasi(data.waktu_pengerjaan)}
                            </p>

                            <!-- PESAN MOTIVASI -->
                            <p class="text-gray-500 dark:text-gray-400 mb-6 text-sm sm:text-base">
                                Kamu bisa lebih baik lagi! Perbanyak latihan dan jangan takut mencoba.
                            </p>

                            <!-- BUTTONS -->
                            <div class="mt-6 flex flex-wrap justify-center gap-3">
                                ${tombolUlang}


                                ${tombolLanjut}
                            </div>

                        </div>`
                    }
                        `;
                // <a href="/detail-materi/${materiId}"
                // class="inline-block bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition">
                //     Kembali ke Menu
                // </a>

            } catch (error) {

                console.error(error);

            }

        }

        async function mulaiUlangKuis() {

            try {

                await axios.post('/api/post-test-start', {
                    materi_id: materiId,
                    post_test_id: postTestId
                });

                // reset state quiz
                localStorage.removeItem("quiz_time_left");
                localStorage.removeItem("quiz_current_question");
                localStorage.removeItem("quiz_jawaban");

                // reload halaman
                window.location.reload();

            } catch (error) {

                if (error.response && error.response.status === 403) {
                    showQuizLimitAlert();
                } else {
                    console.error(error);
                }

            }
        }
    </script>

@endsection