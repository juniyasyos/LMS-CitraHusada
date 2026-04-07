@extends('components.layout')
@section('title', 'Hasil Kuis')

@section('content')

<!-- HEADER HANYA DESKTOP -->
<div class="hidden lg:block">
    @include('components.header')
</div>

<div class="flex flex-col lg:flex-row min-h-screen">

    <!-- HALAMAN HASIL KUIS -->
    <main class="order-1 lg:order-2 flex-1 p-4 sm:p-6 lg:p-8">

        <!-- JUDUL MOBILE -->
        <div class="lg:hidden mb-4">
            <h1 class="text-2xl font-bold text-gray-800">
                Hasil Kuis
            </h1>
        </div>

        <!-- BUTTON KEMBALI -->
        <a href="/detail-materi"
        class="inline-flex items-center gap-2 text-gray-600 hover:text-red-500 mb-6 transition">
            <i class="fas fa-times text-lg"></i>
            <span>Kembali</span>
        </a>


        <!-- CARD HASIL -->
        <div class="bg-white border rounded-xl p-6 sm:p-8 max-w-xl mx-auto text-center shadow-sm">

            <!-- ICON -->
            <div class="text-green-500 text-4xl sm:text-5xl mb-4">
                <i class="fa-solid fa-circle-check"></i>
            </div>

            <!-- JUDUL -->
            <h2 class="text-xl sm:text-2xl font-bold mb-2">
                Selamat!
            </h2>

            <!-- DESKRIPSI -->
            <p class="text-gray-600 mb-4 text-sm sm:text-base">
                Kamu telah menyelesaikan kuis dengan nilai
            </p>

            <!-- NILAI -->
            <div class="text-3xl sm:text-4xl font-bold text-blue-600 mb-6">
                ${data.skor_kuis_ini} / 100
            </div>

            <!-- PESAN -->
            <p class="text-gray-500 mb-8 text-sm sm:text-base">
                Hasil yang sangat baik! Kamu sudah memahami materi keselamatan pasien
                dengan baik. Terus pertahankan semangat belajarmu.
            </p>

            <!-- BUTTON -->
            <div class="mt-6 flex flex-wrap justify-center gap-3">
                ${tombolUlang}
                ${tombolLanjut}
            </div>

        </div>

    </main>



    <!-- SIDEBAR -->
    <aside class="order-2 lg:order-1 w-full lg:w-72 bg-white border-t lg:border-t-0 lg:border-r p-4 lg:p-6">
        
        <h2 class="font-bold text-base lg:text-lg mb-4">
            Progress Belajar
        </h2>

        <!-- PROGRESS -->
        <div class="mb-6">
            <div class="flex justify-between text-sm mb-1">
                <span>Progress</span>
                <span class="font-medium text-blue-600">100%</span>
            </div>

            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-blue-600 h-2 rounded-full" style="width: 100%"></div>
            </div>
        </div>

        <h2 class="font-semibold text-sm lg:text-md mb-3">
            Daftar Materi
        </h2>

        <div class="space-y-2 text-sm">

            <a href="" class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-100">
                <i class="fa-solid fa-circle-check text-green-500"></i>
                <span>Introduction</span>
            </a>

            <a href="" class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-100">
                <i class="fa-solid fa-circle-check text-green-500"></i>
                <span>Konsep Keselamatan Pasien</span>
            </a>
            
            <a href="" class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-100">
                <i class="fa-solid fa-circle-check text-green-500"></i>
                <span>Standar Pelayanan Rumah Sakit</span>
            </a>

            <a href="" class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-100">
                <i class="fa-solid fa-circle-check text-green-500"></i>
                <span>Studi Kasus</span>
            </a>

            <a href="" class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-100">
                <i class="fa-solid fa-circle-check text-green-500"></i>
                <span>Kesimpulan</span>
            </a>

            <a href="/final-kuis"
            class="flex items-center gap-3 p-3 rounded-lg bg-blue-100 text-blue-600 font-medium">
                <i class="fa-solid fa-play text-blue-500"></i>
                <span>Final Kuis</span>
            </a>

        </div>

    </aside>

</div>

@endsection