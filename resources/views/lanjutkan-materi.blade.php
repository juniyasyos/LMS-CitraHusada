@extends('components.layout')
@section('title', 'lanjutkan-materi')

@section('content')

    <div class="hidden lg:block">
        @include('components.header')
    </div>

    <div class="flex flex-col lg:flex-row min-h-screen bg-slate-50 dark:bg-slate-950 transition-colors duration-300">

        <!-- SIDEBAR MATERI -->
        <aside
            class="order-3 lg:order-1 w-full lg:w-72 bg-white dark:bg-slate-900 border-t dark:border-slate-800 lg:border-t-0 lg:border-r lg:dark:border-slate-800 p-4 lg:p-6 max-h-[300px] overflow-y-auto lg:max-h-none transition-colors duration-300">

            <h2 class="font-bold text-base lg:text-lg mb-4 text-gray-900 dark:text-white">
                Progress Belajar
            </h2>

            <!-- PROGRESS BAR -->
            <div class="mb-6">
                <div class="flex justify-between text-sm mb-1 text-gray-900 dark:text-white">
                    <span>Progress</span>
                    <span id="progressText" class="font-medium text-blue-600 dark:text-blue-400">0%</span>
                </div>

                <div class="w-full bg-gray-200 dark:bg-slate-700 rounded-full h-2">
                    <div id="progressBar" class="bg-blue-600 h-2 rounded-full" style="width: 0%"></div>
                </div>
            </div>

            <h2 class="font-semibold text-sm lg:text-md mb-3 text-gray-900 dark:text-white">
                Daftar Materi
            </h2>

            <div id="daftarMateri" class="space-y-2 text-sm"></div>

        </aside>


        <!-- KONTEN VIDEO -->
        <main class="order-1 lg:order-2 flex-1 p-4 sm:p-6 lg:p-8">

            <!-- BUTTON KELUAR -->
            <a href="/detail-materi/{{ $materiId }}"
                class="inline-flex items-center gap-2 text-gray-600 dark:text-gray-400 hover:text-red-500 dark:hover:text-red-400 mb-6 transition">
                <i class="fas fa-times text-lg"></i>
                <span>Kembali</span>
            </a>

            <!-- JUDUL VIDEO -->
            <h1 id="judulMateriAktif" class="text-lg sm:text-xl lg:text-2xl font-bold mb-6 text-gray-900 dark:text-white"></h1>
            {{-- <h1 class="text-lg sm:text-xl lg:text-2xl font-bold mb-6">
                Introduction Keselamatan Pasien
            </h1> --}}

            <!-- VIDEO PLAYER -->
            <div class="bg-black rounded-xl overflow-hidden mb-6">
                <div id="materiViewer" {{--class="mb-6" --}}></div>
            </div>

            <!-- PENJELASAN MATERI -->
            <div id="tentangMateriBox" class="bg-white dark:bg-slate-900 border dark:border-slate-800 rounded-xl p-4 sm:p-6 transition-colors duration-300">
                <h2 class="text-base lg:text-lg font-semibold mb-3 text-gray-900 dark:text-white">
                    Tentang Materi
                </h2>

                <p id="deskripsiMateri" class="text-gray-600 dark:text-gray-300 leading-relaxed text-sm sm:text-base"></p>
            </div>
        </main>
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>

    <script>
        pdfjsLib.GlobalWorkerOptions.workerSrc =
            "https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js";

        const urlParams = new URLSearchParams(window.location.search);
        let stepDibuka = urlParams.get("step");

        const materiId = "{{ $materiId }}";
        let currentStep = null;
        let quizAlertTimeout;
        let allSteps = [];
        let globalUrutanSelesai = 0;

        document.addEventListener('DOMContentLoaded', function () {
            loadMateriLanjutkan();
        });

        async function loadMateriLanjutkan() {
            try {
                const response = await axios.get('/api/materi-lanjutkan/' + materiId);
                const data = response.data.data;

                allSteps = data.steps;
                globalUrutanSelesai = data.urutan_selesai;

                document.getElementById("progressText").innerText =
                    data.progress_percent + "%";

                document.getElementById("progressBar").style.width =
                    data.progress_percent + "%";

                let stepAktif;

                if (stepDibuka) {
                    let stepInt = parseInt(stepDibuka);
                    if (stepInt > data.urutan_selesai + 1) {
                        alert("Anda tidak dapat membuka materi ini sebelum menyelesaikan materi sebelumnya.");
                        stepAktif = data.steps.find(step => step.urutan === data.urutan_selesai + 1);
                    } else {
                        stepAktif = data.steps.find(step => step.urutan === stepInt);
                    }
                } else {
                    stepAktif = data.steps.find(step =>
                        step.urutan === data.urutan_selesai + 1
                    );
                }

                if (!stepAktif && data.steps.length > 0) {
                    // Jika sudah selesai semua, buka step terakhir
                    stepAktif = data.steps[data.steps.length - 1];
                }

                if (stepAktif) {
                    loadMateri(stepAktif);
                } else {
                    // Render default
                    renderSidebar(allSteps, globalUrutanSelesai, null);
                }

            } catch (error) {
                console.error(error);
            }
        }

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

        function renderSidebar(steps, urutanSelesai, activeUrutan) {

            const container = document.getElementById("daftarMateri");

            container.innerHTML = "";

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
                <a href="#"
                onclick='loadMateri(${JSON.stringify(step).replace(/'/g, "\\'")})'
                class="flex items-center gap-3 p-3 rounded-lg transition-colors ${classItem}">
                    <i class="fa-solid ${icon}"></i>
                    <span>${step.judul}</span>
                </a>
                `;

                }
                else {

                    container.innerHTML += `
                <div class="flex items-center gap-3 p-3 rounded-lg ${classItem}">
                    <i class="fa-solid ${icon}"></i>
                    <span>${step.judul}</span>
                </div>
                `;

                }
            });
        }

        function loadMateri(step) {
            currentStep = step;

            renderSidebar(allSteps, globalUrutanSelesai, step.urutan);

            document.getElementById("judulMateriAktif").innerText = step.judul;

            const deskripsiContainer = document.getElementById("deskripsiMateri");
            const tentangMateriBox = document.getElementById("tentangMateriBox");

            if (step.type === "sub_materi") {
                tentangMateriBox.style.display = "block";
                deskripsiContainer.innerText = step.deskripsi ?? "";
            }
            else {
                tentangMateriBox.style.display = "none";
            }

            const viewer = document.getElementById("materiViewer");

            viewer.innerHTML = "";
            if (step.type === "post_test") {

                // ambil data post test dari step
                const sudahMengerjakan = step.sudah_mengerjakan;
                const skor = step.skor_tertinggi ?? 0;

                if (sudahMengerjakan) {

                    let tombolUlang = "";

                    if (skor < 75) {
                        tombolUlang = `
                            <button onclick="mulaiKuis()"
                            class="mt-5 bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-lg">
                                Mulai Ulang Test
                            </button>
                        `;
                    }

                    viewer.innerHTML = `
                    <div class="bg-white dark:bg-slate-900 border dark:border-slate-800 rounded-xl p-6 text-center">
                        <h2 class="text-3xl font-bold text-black dark:text-white">
                            ${skor} %
                        </h2>
                        <p class="text-gray-500 dark:text-gray-400 mb-3">
                            FINAL SCORE
                        </p>

                        ${skor >= 75
                            ? `<p class="text-green-600 dark:text-green-400 font-semibold">Memenuhi KKM</p>`
                            : `<p class="text-orange-500 dark:text-orange-400 font-semibold">Di Bawah KKM (Boleh lanjut / Remidi)</p>`
                        }

                        <p class="text-gray-500 dark:text-gray-400 mt-3">
                            Kuis sudah pernah dikerjakan.
                        </p>

                        ${tombolUlang}
                    </div>
                    `;

                } else {

                    // tampilan default (belum pernah kuis)
                    viewer.innerHTML = `
                    <div class="bg-white dark:bg-slate-900 border dark:border-slate-800 rounded-xl p-6 text-center">
                        <h2 class="text-lg font-semibold mb-3 text-gray-900 dark:text-white">Kuis</h2>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">
                            Anda akan memulai Kuis untuk materi ini.
                        </p>

                        <div class="flex justify-center gap-6 text-sm mb-6 text-gray-900 dark:text-gray-200">
                            <span class="bg-blue-100 dark:bg-blue-900/30 px-4 py-2 rounded-lg">
                                ${step.jumlah_soal} Soal
                            </span>

                            <span class="bg-blue-100 dark:bg-blue-900/30 px-4 py-2 rounded-lg">
                                ${step.waktu_pengerjaan} Detik
                            </span>

                            <span class="bg-blue-100 dark:bg-blue-900/30 px-4 py-2 rounded-lg">
                                ${step.max_attempt} Percobaan
                            </span>
                        </div>

                        <button onclick="mulaiKuis()"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition">
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

            if (!file) {
                viewer.innerHTML = "File tidak tersedia";
                return;
            }

            if (ext === "mp4") {
                viewer.innerHTML = `
                    <div class="bg-black rounded-xl mb-6">
                        <video id="videoMateri" controls class="w-full">
                            <source src="${url}" type="video/mp4">
                        </video>
                    </div>
                `;

                setTimeout(() => {
                    let videoCompleted = false;
                    const video = document.getElementById("videoMateri");
                    video.addEventListener("ended", function () {
                        if (!videoCompleted) {
                            videoCompleted = true;
                            updateProgress(currentStep.urutan);
                        }
                    });
                }, 200);
            }
            // else if(ext === "pdf"){
            //     viewer.innerHTML = `
            //     <div class="bg-white border rounded-xl p-4">
            //         <canvas id="pdfCanvas" class="w-full"></canvas>

            //         <div class="flex justify-between mt-4">
            //             <button id="prevPage" class="px-4 py-2 bg-gray-300 rounded">
            //                 Previous
            //             </button>

            //             <span id="pageInfo"></span>

            //             <button id="nextPage" class="px-4 py-2 bg-blue-600 text-white rounded">
            //                 Next
            //             </button>
            //         </div>
            //     </div>
            //     `;

            //     loadPDF(url);
            // }

            else if (["pdf", "ppt", "pptx", "doc", "docx"].includes(ext)) {
                const fullUrl = window.location.origin + url;
                let iframeSrc = url;

                if (ext !== 'pdf') {
                    iframeSrc = "https://view.officeapps.live.com/op/embed.aspx?src=" + encodeURIComponent(fullUrl);
                }

                viewer.innerHTML = `
                <div class="bg-white dark:bg-slate-900 border dark:border-slate-800 rounded-xl overflow-hidden flex flex-col">
                    ${ext !== 'pdf' ? `
                    <div class="p-3 bg-yellow-50 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-200 text-sm border-b dark:border-yellow-700/50">
                        <i class="fa-solid fa-circle-info mr-2"></i>
                        Jika dokumen tidak tampil (karena web diakses dari localhost), silakan 
                        <a href="${url}" target="_blank" download class="font-bold underline text-blue-600 dark:text-blue-400">Download/Buka File</a>.
                    </div>
                    ` : ''}
                    <iframe 
                        src="${iframeSrc}" 
                        class="w-full h-[600px] bg-gray-50 dark:bg-slate-800">
                    </iframe>

                    <div class="p-4 text-right bg-white dark:bg-slate-900 border-t dark:border-slate-800 mt-auto">
                        <button onclick="updateProgress(${step.urutan})"
                        class="bg-blue-600 hover:bg-blue-700 transition text-white px-5 py-2 rounded-lg">
                            Tandai Sudah Dibaca
                        </button>
                    </div>
                </div>
                `;
            }
            else {
                 viewer.innerHTML = `<div class="bg-white dark:bg-slate-900 border dark:border-slate-800 rounded-xl p-6 text-center text-gray-500 dark:text-gray-400">Format file tidak didukung.</div>`;
            }
        }

        //update progress
        async function updateProgress(urutan) {
            try {
                await axios.post('/api/progress/update', {
                    materi_id: materiId,
                    urutan: urutan
                });

                // Hapus parameter ?step dari URL dan variabel agar auto-next berjalan mulus
                stepDibuka = null;
                window.history.replaceState(null, null, window.location.pathname);

                loadMateriLanjutkan();
            } catch (error) {

                console.error(error);
            }
        }

        async function loadPDF(url) {
            const pdf = await pdfjsLib.getDocument(url).promise;

            let pageNum = 1;
            const totalPages = pdf.numPages;

            const canvas = document.getElementById("pdfCanvas");
            const ctx = canvas.getContext("2d");

            async function renderPage(num) {

                const page = await pdf.getPage(num);

                // ambil lebar container
                const containerWidth = canvas.parentElement.clientWidth;

                const viewport = page.getViewport({ scale: 1 });

                // hitung scale agar fit ke layar
                const scale = containerWidth / viewport.width;

                const scaledViewport = page.getViewport({ scale: scale });

                canvas.width = scaledViewport.width;
                canvas.height = scaledViewport.height;

                await page.render({
                    canvasContext: ctx,
                    viewport: scaledViewport
                }).promise;

                document.getElementById("pageInfo").innerText =
                    `Halaman ${num} / ${totalPages}`;

                if (num === totalPages) {
                    updateProgress(currentStep.urutan);
                }
            }

            document.getElementById("prevPage").onclick = function () {
                if (pageNum <= 1) return;
                pageNum--;
                renderPage(pageNum);
            };

            document.getElementById("nextPage").onclick = function () {
                if (pageNum >= totalPages) return;
                pageNum++;
                renderPage(pageNum);
            };

            renderPage(pageNum);
        }

        async function mulaiKuis() {
            try {

                await axios.post('/api/post-test-start', {
                    materi_id: materiId,
                    post_test_id: currentStep.id
                });

                // kalau sukses → redirect ke halaman kuis
                window.location.href = "/post-test/" + materiId + "?post_test_id=" + currentStep.id;

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