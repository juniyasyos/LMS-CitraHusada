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
        <h1 class="text-3xl font-bold mb-3">
            Keselamatan Pasien di Rumah Sakit
        </h1>

        <!-- DESKRIPSI -->
        <p class="text-gray-600 mb-5 leading-relaxed">
            Materi ini membahas mengenai standar keselamatan pasien yang harus diterapkan 
            di lingkungan rumah sakit untuk meningkatkan kualitas pelayanan kesehatan 
            dan meminimalkan risiko kesalahan medis.
        </p>

        <!-- DURASI -->
        <div class="flex items-center gap-2 text-sm text-gray-500 mb-10">
            <i class="fa-solid fa-clock"></i>
            <span>Durasi Pengerjaan: 3 JPL</span>
        </div>

        <!-- LIST DOKUMEN -->
        <div class="space-y-4">

            <h2 class="text-xl font-semibold mb-4">Materi</h2>

            <!-- ITEM -->
            <a href="/lanjutkan-materi" class="block">
            <div class="flex items-center justify-between p-5 border rounded-xl hover:bg-gray-50 transition cursor-pointer">
                
                <div class="flex items-center gap-4">
                    <i class="fa-solid fa-file-lines text-blue-500 text-2xl"></i>
                    <div>
                        <p class="font-medium">Materi</p>
                    </div>
                </div>

                <span class="text-white bg-green-500 px-4 py-2 rounded-lg">
                    Selesai
                </span>

            </div>
</a>

        </div>

    </div>

</div>

@endsection