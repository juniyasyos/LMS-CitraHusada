@extends('components.layout')
@section('title', 'detail-materi')
@section('content')

<!-- HEADER DESKTOP ONLY -->
<div class="hidden lg:block">
    @include('components.header')
</div>

<div class="w-full min-h-screen px-4 sm:px-6 lg:px-8 py-6 sm:py-10 bg-slate-50 dark:bg-slate-950 transition-colors duration-300">

    <div class="max-w-6xl mx-auto">

        <!-- BACK BUTTON -->
        <a href="/pembelajaran"
        class="inline-flex items-center gap-2 text-gray-600 dark:text-gray-400 hover:text-red-500 mb-6 transition">
            <i class="fas fa-times text-lg"></i>
            <span>Kembali</span>
        </a>

        <!-- JUDUL -->
        <h1 id="judulMateri"
            class="text-xl sm:text-2xl lg:text-3xl font-bold mb-3 text-gray-900 dark:text-white">
        </h1>

        <!-- DESKRIPSI -->
        <p id="deskripsiMateri"
           class="text-gray-600 dark:text-gray-400 mb-5 leading-relaxed text-sm sm:text-base">
        </p>

        <!-- DURASI -->
        <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-8">
            <i class="fa-solid fa-clock"></i>
            <span id="jamPelajaran"></span>
        </div>

        <!-- LIST -->
        <div id="listMateri" class="space-y-4"></div>

    </div>

</div>

<!-- TOAST ALERT KUNCI -->
<div id="lockedAlert" class="fixed top-6 left-1/2 transform -translate-x-1/2 z-50 bg-white dark:bg-slate-900 border-l-4 border-red-500 text-gray-800 dark:text-gray-200 px-6 py-4 rounded-xl shadow-[0_8px_30px_rgb(0,0,0,0.12)] flex items-center gap-4 transition-all duration-300 opacity-0 -translate-y-10 pointer-events-none min-w-[320px]">
    <div class="bg-red-50 dark:bg-red-950/40 text-red-500 rounded-full w-10 h-10 flex items-center justify-center shrink-0">
        <i class="fa-solid fa-lock text-lg"></i>
    </div>
    <div>
        <h4 class="font-bold text-sm sm:text-base text-gray-900 dark:text-white">Akses Terkunci</h4>
        <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-0.5">Selesaikan materi sebelumnya terlebih dahulu!</p>
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
            materi.jam_pelajaran + " JPL";

        renderSteps(materi.steps, materi.urutan_selesai, materi.status);

    } catch (error) {
        console.error("Error load detail", error);
    }
}

function renderSteps(steps, urutanSelesai, materiStatus) {

    const container = document.getElementById("listMateri");
    container.innerHTML = `<h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Materi</h2>`;

    steps.forEach(step => {
        let statusText = "Terkunci";
        let warna = "bg-gray-400";
        let icon = "fa-lock text-gray-400";
        let isLocked = true;

        if (step.urutan <= urutanSelesai) {
            // Logika khusus untuk Kuis yang sudah dikerjakan tapi nilainya kecil
            if (step.type === 'post_test' && step.skor < 75) {
                statusText = `Skor: ${step.skor} (Remidi)`;
                warna = "bg-orange-500"; // Warna orange sebagai penanda perlu perbaikan
                icon = "fa-circle-exclamation text-orange-500";
            } else {
                statusText = step.type === 'post_test' ? `Skor: ${step.skor}` : "Selesai";
                warna = "bg-green-500";
                icon = "fa-circle-check text-green-500";
            }
            isLocked = false;
        } else if (step.urutan === urutanSelesai + 1) {
            statusText = "Buka";
            warna = "bg-blue-500";
            icon = "fa-play text-blue-500";
            isLocked = false;
        }

        if (materiStatus === "Sesi Berakhir") {
            statusText = "Ditutup";
            warna = "bg-red-500";
            icon = "fa-ban text-red-100";
            isLocked = true;
        }

        let onclickAction = isLocked ? `onclick="showLockedAlert()"` : `onclick="goToStep(${step.urutan})"`;
        let cursorStyle =
            isLocked && materiStatus === "Sesi Berakhir"
                ? "cursor-not-allowed opacity-50 bg-red-50 dark:bg-red-950/30"
                : (isLocked
                    ? "cursor-not-allowed opacity-75"
                    : "cursor-pointer hover:bg-gray-50 dark:hover:bg-slate-800");

        container.innerHTML += `
        <div ${onclickAction}
            class="flex items-center justify-between p-4 sm:p-5 border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 rounded-xl transition ${cursorStyle}"

            <!-- LEFT -->
            <div class="flex items-center gap-3 sm:gap-4">
                <i class="fa-solid ${icon} text-xl sm:text-2xl"></i>

                <div>
                    <p class="font-medium text-sm sm:text-base text-gray-900 dark:text-white">
                        ${step.judul}
                    </p>
                </div>
            </div>

            <!-- STATUS -->
            <span class="text-white ${warna} px-3 py-1.5 sm:px-4 sm:py-2 rounded-lg text-xs sm:text-sm shadow-sm">
                ${statusText}
            </span>

        </div>
        `;
    });
}

function goToStep(step){
    window.location.href = "/lanjutkan-materi/" + materiId + "?step=" + step;
}

let alertTimeout;
function showLockedAlert() {
    const alertBox = document.getElementById('lockedAlert');
    
    // Reset any existing timeout so it doesn't hide early if clicked multiple times
    if(alertTimeout) clearTimeout(alertTimeout);
    
    alertBox.classList.remove('opacity-0', '-translate-y-10', 'pointer-events-none');
    alertBox.classList.add('opacity-100', 'translate-y-0');

    alertTimeout = setTimeout(() => {
        alertBox.classList.remove('opacity-100', 'translate-y-0');
        alertBox.classList.add('opacity-0', '-translate-y-10', 'pointer-events-none');
    }, 3000);
}
</script>
@endsection