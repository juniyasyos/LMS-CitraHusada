@extends('components.layout')
@section('title', 'lanjutkan-materi')

@section('content')

<!-- HEADER (HANYA DESKTOP) -->
<div class="hidden lg:block">
    @include('components.header')
</div>

<div class="flex flex-col lg:flex-row min-h-screen">

    <!-- JUDUL MOBILE -->
    <div class="lg:hidden px-4 pt-6 pb-2">
        <h1 class="text-xl font-bold text-gray-800">
            Kuis
        </h1>
    </div>


    <!-- HALAMAN KUIS -->
    <main class="order-1 lg:order-2 flex-1 p-4 sm:p-6 lg:p-8">

        <!-- BUTTON KEMBALI -->
        <a href="/detail-materi"
        class="inline-flex items-center gap-2 text-gray-600 hover:text-red-500 mb-6 transition">
            <i class="fas fa-times text-lg"></i>
            <span>Kembali</span>
        </a>


        <!-- INFO SOAL -->
        <div class="flex justify-between items-center mb-3 text-sm text-gray-600 font-medium">
    
            <!-- NOMOR SOAL -->
            <div>
                Soal <span class="text-gray-900 font-semibold">9</span> dari 
                <span class="text-gray-900 font-semibold">10</span>
            </div>

            <!-- DURASI -->
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-clock text-gray-500"></i>
                <span>10:00</span>
            </div>

        </div>

        <!-- PROGRESS BAR -->
        <div class="w-full bg-gray-200 rounded-full h-2 mb-8">
            <div class="bg-blue-500 h-2 rounded-full" style="width: 90%"></div>
        </div>


        <!-- CARD SOAL -->
        <div class="bg-white border rounded-xl p-4 sm:p-6">

            <!-- SOAL + POIN -->
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-3 mb-6">
                
                <h2 class="text-lg sm:text-xl font-semibold leading-relaxed sm:max-w-xl">
                    Apa yang dimaksud dengan keselamatan pasien di rumah sakit?
                </h2>

                <span class="text-sm text-blue-600 font-medium">
                    Poin: 10
                </span>

            </div>


            <!-- PILIHAN JAWABAN -->
            <div class="space-y-3 sm:space-y-4">

                <label class="flex items-start gap-3 p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                    <input type="radio" name="soal1" class="mt-1 w-4 h-4 jawaban">
                    <span>Upaya meningkatkan jumlah pasien yang dirawat</span>
                </label>

                <label class="flex items-start gap-3 p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                    <input type="radio" name="soal1" class="mt-1 w-4 h-4 jawaban">
                    <span>Sistem untuk membuat pelayanan pasien lebih aman</span>
                </label>

                <label class="flex items-start gap-3 p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                    <input type="radio" name="soal1" class="mt-1 w-4 h-4 jawaban">
                    <span>Proses administrasi rumah sakit</span>
                </label>

                <label class="flex items-start gap-3 p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                    <input type="radio" name="soal1" class="mt-1 w-4 h-4 jawaban">
                    <span>Kegiatan promosi kesehatan kepada masyarakat</span>
                </label>

            </div>

        </div>

    </main>



    <!-- SIDEBAR MATERI -->
    <aside class="order-2 lg:order-1 w-full lg:w-72 bg-white border-t lg:border-t-0 lg:border-r p-4 lg:p-6">

        <h2 class="font-bold text-base lg:text-lg mb-4">
            Progress Belajar
        </h2>

        <!-- PROGRESS BAR -->
        <div class="mb-6">
            <div class="flex justify-between text-sm mb-1">
                <span>Progress</span>
                <span class="font-medium text-blue-600">85%</span>
            </div>

            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-blue-600 h-2 rounded-full" style="width: 85%"></div>
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

            <a href="/lanjutkan-materi" class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-100">
                <i class="fa-solid fa-circle-check text-green-500"></i>
                <span>Konsep Keselamatan Pasien</span>
            </a>
            
            <a href="" class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-100">
                <i class="fa-solid fa-circle-check text-green-500"></i>
                <span>Standar Pelayanan Rumah Sakit</span>
            </a>

            <a href="#" class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-100">
                <i class="fa-solid fa-circle-check text-green-500"></i>
                <span>Studi Kasus</span>
            </a>

            <a href="#" class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-100">
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