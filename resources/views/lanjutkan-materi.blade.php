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