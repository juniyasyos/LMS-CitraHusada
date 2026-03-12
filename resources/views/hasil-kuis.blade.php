@extends('components.layout')
@section('title', 'Hasil Kuis')

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
                <span class="font-medium text-blue-600">100%</span>
            </div>

            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-blue-600 h-2 rounded-full" style="width: 100%"></div>
            </div>
        </div>

        <h2 class="font-semibold text-md mb-3">
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

            <!-- FINAL KUIS -->
            <a href="/final-kuis"
            class="flex items-center gap-3 p-3 rounded-lg bg-blue-100 text-blue-600 font-medium">
                <i class="fa-solid fa-play text-blue-500"></i>
                <span>Final Kuis</span>
            </a>

        </div>

    </aside>


    <!-- HALAMAN HASIL KUIS -->
    <main class="flex-1 p-8">

        <!-- BUTTON KEMBALI -->
        <a href="/detail-materi"
        class="inline-flex items-center gap-2 text-gray-600 hover:text-red-500 mb-8 transition">
            <i class="fas fa-times text-lg"></i>
            <span>Kembali</span>
        </a>


        <!-- CARD HASIL KUIS -->
        <div class="bg-white border rounded-xl p-8 max-w-xl mx-auto text-center shadow-sm">

            <!-- ICON -->
            <div class="text-green-500 text-5xl mb-4">
                <i class="fa-solid fa-circle-check"></i>
            </div>

            <!-- JUDUL -->
            <h2 class="text-2xl font-bold mb-2">
                Selamat!
            </h2>

            <!-- DESKRIPSI -->
            <p class="text-gray-600 mb-4">
                Kamu telah menyelesaikan kuis dengan nilai
            </p>

            <!-- NILAI -->
            <div class="text-4xl font-bold text-blue-600 mb-6">
                90 / 100
            </div>

            <!-- PESAN -->
            <p class="text-gray-500 mb-8">
                Hasil yang sangat baik! Kamu sudah memahami materi keselamatan pasien
                dengan baik. Terus pertahankan semangat belajarmu.
            </p>

            <!-- BUTTON KEMBALI KE MATERI -->
            <a href="/detail-materi"
            class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition">
                Kembali ke Materi
            </a>

        </div>

    </main>

</div>

@endsection