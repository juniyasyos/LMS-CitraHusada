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
                <span class="font-medium text-blue-600">85%</span>
            </div>

            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-blue-600 h-2 rounded-full" style="width: 85%"></div>
            </div>
        </div>

        <h2 class="font-semibold text-md mb-3">
            Daftar Materi
        </h2>

        <div class="space-y-2 text-sm">
            <a href=""
            class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-100">
                <i class="fa-solid fa-circle-check text-green-500"></i>
                <span>Introduction</span>
            </a>

            <a href="/lanjutkan-materi"
            class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-100">
                <i class="fa-solid fa-circle-check text-green-500"></i>
                <span>Konsep Keselamatan Pasien</span>
            </a>
            
            <a href=""
            class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-100">
                <i class="fa-solid fa-circle-check text-green-500"></i>
                <span>Standar Pelayanan Rumah Sakit</span>
            </a>

            <a href="#"
            class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-100">
                <i class="fa-solid fa-circle-check text-green-500"></i>
                <span>Studi Kasus</span>
            </a>

            <a href="#"
            class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-100">
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


    <!-- HALAMAN KUIS -->
    <main class="flex-1 p-8">

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
                Soal <span class="text-gray-900 font-semibold">10</span> dari 
                <span class="text-gray-900 font-semibold">10</span>
            </div>

            <!-- DURASI -->
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-clock text-gray-500"></i>
                <span>01:00</span>
            </div>

        </div>

        <!-- PROGRESS BAR -->
        <div class="w-full bg-gray-200 rounded-full h-2 mb-8">
            <div class="bg-blue-500 h-2 rounded-full" style="width: 100%"></div>
        </div>


        <!-- CARD SOAL -->
        <div class="bg-white border rounded-xl p-6">

            <!-- SOAL + POIN -->
            <div class="flex justify-between items-start mb-6">
                
                <h2 class="text-xl font-semibold leading-relaxed max-w-xl">
                    Apa yang dimaksud dengan keselamatan pasien di rumah sakit?
                </h2>

                <span class="text-sm text-blue-600 font-medium">
                    Poin: 10
                </span>

            </div>


            <!-- PILIHAN JAWABAN -->
            <div class="space-y-4">

                <label class="flex items-center gap-3 p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                    <input type="radio" name="soal1" class="w-4 h-4">
                    <span>Upaya meningkatkan jumlah pasien yang dirawat</span>
                </label>

                <label class="flex items-center gap-3 p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                    <input type="radio" name="soal1" class="w-4 h-4">
                    <span>Sistem untuk membuat pelayanan pasien lebih aman</span>
                </label>

                <label class="flex items-center gap-3 p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                    <input type="radio" name="soal1" class="w-4 h-4">
                    <span>Proses administrasi rumah sakit</span>
                </label>

                <label class="flex items-center gap-3 p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                    <input type="radio" name="soal1" class="w-4 h-4">
                    <span>Kegiatan promosi kesehatan kepada masyarakat</span>
                </label>

            </div>

            
            <!-- button kirim dan popup -->
            <div class="flex justify-center mt-6">
                <button onclick="bukaPopup()" 
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition">
                    Kirim
                </button>
            </div>
            <div id="popupKirim" class="fixed inset-0 bg-black bg-opacity-40 hidden items-center justify-center">

                <div class="bg-white rounded-xl p-6 w-96 text-center shadow-lg">

                    <h2 class="text-lg font-semibold mb-2">
                        Apakah anda sudah yakin?
                    </h2>

                    <div class="flex justify-center gap-4">
                        
                        <!-- BATAL -->
                        <button onclick="tutupPopup()" 
                        class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">
                            TIDAK
                        </button>

                        <!-- KONFIRMASI -->
                        <button onclick="lanjutSoal()" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            YA
                        </button>

                    </div>

                </div>

            </div>

        </div>

    </main>

</div>

@endsection