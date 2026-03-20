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
                <span class="font-medium text-blue-600">35%</span>
            </div>

            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-blue-600 h-2 rounded-full" style="width: 35%"></div>
            </div>
        </div>

        <h2 class="font-semibold text-md mb-3">
            Daftar Materi
        </h2>

        <div class="space-y-2 text-sm">

            <!-- SUB MATERI SELESAI -->
            <a href=""
            class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-100">
                <i class="fa-solid fa-circle-check text-green-500"></i>
                <span>Introduction</span>
            </a>

            <!-- SUB MATERI SELESAI -->
            <a href="/lanjutkan-materi"
            class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-100">
                <i class="fa-solid fa-circle-check text-green-500"></i>
                <span>Konsep Keselamatan Pasien</span>
            </a>

            <!-- SUB MATERI AKTIF-->
            <a href="/dummy-lanjutkan-materi-ver-ppt"
            class="flex items-center gap-3 p-3 rounded-lg bg-blue-100 text-blue-600 font-medium">
                <i class="fa-solid fa-play text-blue-500"></i>
                <span>Standar Pelayanan Rumah Sakit</span>
            </a>

            <a href="#"
            class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-100 text-gray-500">
                <i class="fa-solid fa-lock text-gray-400"></i>
                <span>Studi Kasus</span>
            </a>

            <a href="#"
            class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-100 text-gray-500">
                <i class="fa-solid fa-lock text-gray-400"></i>
                <span>Kesimpulan</span>
            </a>

            <!-- FINAL KUIS -->
            <a href="#"
            class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-100 text-gray-500 font-medium">
                <i class="fa-solid fa-lock text-gray-400"></i>
                <span>Final Kuis</span>
            </a>

        </div>

    </aside>


    <!-- KONTEN VIDEO -->
    <main class="flex-1 p-8">

        <!-- BUTTON KELUAR -->
        <a href="/detail-materi"
        class="inline-flex items-center gap-2 text-gray-600 hover:text-red-500 mb-6 transition">
            <i class="fas fa-times text-lg"></i>
            <span>Kembali</span>
        </a>


        <!-- JUDUL VIDEO -->
        <h1 class="text-2xl font-bold mb-6">
            Judul
        </h1>


        <!-- PPT -->
        <img src="{{ asset('images/dummy-ppt.jpeg') }}" alt="Contoh PPT" class="w-30 h-30">


        <!-- PENJELASAN MATERI -->
        <div class="bg-white border rounded-xl p-6">

            <h2 class="text-lg font-semibold mb-3">
                Tentang Materi
            </h2>

            <p class="text-gray-600 leading-relaxed">
                Materi ini menjelaskan dasar-dasar keselamatan pasien di lingkungan rumah sakit. 
                Keselamatan pasien merupakan sistem yang bertujuan untuk membuat asuhan pasien 
                lebih aman dengan mencegah terjadinya cedera akibat kesalahan tindakan medis. 
                Dalam materi ini akan dibahas mengenai konsep keselamatan pasien, standar 
                penerapan di rumah sakit, serta contoh kasus yang sering terjadi dalam 
                pelayanan kesehatan.
            </p>

        </div>

    </main>

</div>

@endsection