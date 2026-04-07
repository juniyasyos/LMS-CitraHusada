@extends('components.layout')
@section('title', 'lanjutkan-materi')

@section('content')

<div x-data="quizApp()" x-init="init()">

    <!-- ================= POPUP WELCOME ================= -->
    <div x-show="welcomeOpen" x-transition
        class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
        
        <div class="bg-white rounded-2xl shadow-lg w-[90%] max-w-md p-6 relative">
            <button @click="welcomeOpen=false" class="absolute top-3 right-3 text-gray-400">✕</button>

            <div class="flex justify-center mb-4">
                <lottie-player 
                    src="https://lottie.host/363a0f93-0231-4ecf-9d9c-77a30aa4cc4d/hY7zUek7Du.json"
                    background="transparent"
                    style="width:120px;height:120px;"
                    autoplay>
                </lottie-player>
            </div>

            <div class="text-center">
                <p class="font-semibold mb-2">Siap untuk kuis? 🚀</p>
                <p class="text-gray-500 text-sm">
                    Jawab dengan tenang ya 😉
                </p>
            </div>
        </div>
    </div>

    <!-- ================= POPUP MOTIVASI ================= -->
    <div x-show="open" x-transition
        class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
        
        <div class="bg-white rounded-2xl shadow-lg w-[90%] max-w-md p-6 relative">
            <button @click="closePopup()" class="absolute top-3 right-3 text-gray-400">✕</button>

            <div class="flex justify-center mb-4">
                <lottie-player 
                    src="https://lottie.host/7559d986-bb08-4257-afdc-7a9253af9a54/c2F2tTQjjS.json"
                    background="transparent"
                    style="width:120px;height:120px;"
                    loop autoplay>
                </lottie-player>
            </div>

            <div class="text-center">
                <p class="font-semibold mb-2" x-text="messages[currentMsg].title"></p>
                <p class="text-gray-500 text-sm" x-text="messages[currentMsg].desc"></p>
            </div>
        </div>
    </div>

    <!-- ================= POPUP KONFIRMASI ================= -->
    <div x-show="confirmOpen" x-transition
        class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
        
        <div class="bg-white rounded-2xl shadow-lg w-[90%] max-w-md p-6 text-center">

            <div class="flex justify-center mb-4">
                <lottie-player 
                    src="https://assets10.lottiefiles.com/packages/lf20_touohxv0.json"
                    background="transparent"
                    style="width:130px;height:130px;"
                    autoplay>
                </lottie-player>
            </div>

            <h2 class="font-semibold mb-2">
                Yakin kirim jawaban?
            </h2>

            <div class="flex justify-center gap-3 mt-4">
                <button @click="confirmOpen=false"
                    class="px-4 py-2 border rounded-lg">
                    Tidak
                </button>

                <button @click="submitQuiz()"
                    class="px-4 py-2 bg-blue-500 text-white rounded-lg">
                    Ya
                </button>
            </div>

        </div>
    </div>

    <!-- HEADER DESKTOP -->
    <div class="hidden lg:block">
        @include('components.header')
    </div>

    <!-- ================= LAYOUT ================= -->
    <div class="flex flex-col lg:flex-row min-h-screen">

        <!-- MAIN -->
        <main class="order-1 lg:order-2 flex-1 p-4 sm:p-6 lg:p-8">

            <!-- BACK MOBILE -->
            <div class="lg:hidden mb-4">
                <a href="/detail-materi"
                class="inline-flex items-center gap-2 text-gray-600">
                    ← Kembali
                </a>
            </div>

            <!-- INFO -->
            <div class="flex justify-between mb-3 text-sm">
                <div>Soal <span x-text="soalKe"></span> dari 10</div>
                <div>10:00</div>
            </div>

            <!-- PROGRESS -->
            <div class="bg-gray-200 h-2 rounded-full mb-6">
                <div class="bg-blue-500 h-2 rounded-full"
                    :style="'width:' + (soalKe * 10) + '%'"></div>
            </div>

            <!-- CARD -->
            <div class="bg-white border rounded-xl p-4 sm:p-6">

                <h2 class="font-semibold mb-6">
                    Apa yang dimaksud dengan keselamatan pasien?
                </h2>

                <div class="space-y-3 mb-6">

                    <label class="flex gap-3 p-3 border rounded-lg">
                        <input type="radio" value="A" x-model="jawaban">
                        <span>Upaya meningkatkan jumlah pasien</span>
                    </label>

                    <label class="flex gap-3 p-3 border rounded-lg">
                        <input type="radio" value="B" x-model="jawaban">
                        <span>Sistem pelayanan lebih aman</span>
                    </label>

                    <label class="flex gap-3 p-3 border rounded-lg">
                        <input type="radio" value="C" x-model="jawaban">
                        <span>Administrasi rumah sakit</span>
                    </label>

                    <label class="flex gap-3 p-3 border rounded-lg">
                        <input type="radio" value="D" x-model="jawaban">
                        <span>Promosi kesehatan</span>
                    </label>

                </div>

                <!-- BUTTON -->
                <div 
                    class="flex"
                    :class="soalKe === totalSoal ? 'justify-center' : 'justify-end'"
                >
                    <button 
                        @click="handleButton()"
                        :disabled="!jawaban"
                        class="text-white px-4 py-2 rounded-lg disabled:opacity-50"
                        :class="soalKe === totalSoal 
                            ? 'bg-blue-500 w-50 sm:w-auto' 
                            : 'bg-blue-500'"
                        x-text="soalKe === totalSoal ? 'Kirim' : 'Selanjutnya'"
                    ></button>
                </div>

            </div>

        </main>

        <!-- SIDEBAR -->
        <aside class="order-3 lg:order-1 w-full lg:w-72 bg-white border-t lg:border-t-0 lg:border-r p-4 lg:p-6">

            <h2 class="font-bold text-base lg:text-lg mb-4">
                Progress Belajar
            </h2>

            <!-- PROGRESS -->
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
                class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-100 text-gray-500 font-medium">
                    <i class="fa-solid fa-lock text-gray-400"></i>
                    <span>Final Kuis</span>
                </a>

            </div>

        </aside>


    </div>

</div>
@endsection