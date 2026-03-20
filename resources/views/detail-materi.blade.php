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