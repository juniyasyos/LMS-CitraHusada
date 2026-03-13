@extends('components.layout')
@section('title', 'detail-materi')
@section('content')

<!-- HEADER DESKTOP ONLY -->
<div class="hidden lg:block">
    @include('components.header')
</div>

<div class="w-full min-h-screen px-4 sm:px-6 lg:px-8 py-6 sm:py-10">

    <div class="max-w-6xl mx-auto">

        <!-- BACK BUTTON -->
        <a href="/pembelajaran"
        class="inline-flex items-center gap-2 text-gray-600 hover:text-red-500 mb-6 transition">
            <i class="fas fa-times text-lg"></i>
            <span>Kembali</span>
        </a>

        <!-- JUDUL -->
        <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold mb-3">
            Keselamatan Pasien di Rumah Sakit
        </h1>

        <!-- DESKRIPSI -->
        <p class="text-gray-600 mb-5 leading-relaxed text-sm sm:text-base">
            Materi ini membahas mengenai standar keselamatan pasien yang harus diterapkan 
            di lingkungan rumah sakit untuk meningkatkan kualitas pelayanan kesehatan 
            dan meminimalkan risiko kesalahan medis.
        </p>

        <!-- DURASI -->
        <div class="flex items-center gap-2 text-sm text-gray-500 mb-8">
            <i class="fa-solid fa-clock"></i>
            <span>Durasi Pengerjaan: 3 JPL</span>
        </div>

        <!-- LIST MATERI -->
        <div class="space-y-4">

            <h2 class="text-lg sm:text-xl font-semibold mb-4">
                Materi
            </h2>

            <!-- ITEM -->
            <a href="/lanjutkan-materi" class="block">
                <div class="flex items-center justify-between p-4 sm:p-5 border rounded-xl hover:bg-gray-50 transition cursor-pointer">

                    <!-- LEFT -->
                    <div class="flex items-center gap-3 sm:gap-4">
                        <i class="fa-solid fa-file-lines text-blue-500 text-xl sm:text-2xl"></i>

                        <div>
                            <p class="font-medium text-sm sm:text-base">
                                Materi
                            </p>
                        </div>
                    </div>

                    <!-- STATUS -->
                    <span class="text-white bg-green-500 px-3 py-1.5 sm:px-4 sm:py-2 rounded-lg text-xs sm:text-sm">
                        Selesai
                    </span>

                </div>
            </a>

        </div>

    </div>

</div>

@endsection