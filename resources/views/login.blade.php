@extends('components.layout')
@section('title', 'login')

@section('content')

<div class="min-h-screen flex items-center justify-center bg-gray-100 px-4">
    
    {{-- Card --}}
    <div class="bg-white w-full max-w-md p-6 sm:p-10 rounded-2xl shadow-2xl">
        
        {{-- Logo + Title --}}
        <div class="flex items-center gap-2 mb-6 justify-center sm:justify-start">
            <img src="{{ asset('images/logo-lms.png') }}" alt="Logo" class="w-10 h-10 sm:w-12 sm:h-12">
            <div>
                <h1 class="text-red-600 font-bold text-base sm:text-lg">Citra Husada</h1>
                <p class="text-green-600 text-xs sm:text-sm">Learning Management System</p>
            </div>
        </div>

        {{-- Welcome Text --}}
        <div class="text-center mb-8">
            <h2 class="text-lg sm:text-xl font-semibold text-gray-800">
                Selamat Datang!
            </h2>

            <p id="dynamicText" class="text-gray-500 text-xs sm:text-sm mt-1">
            </p>
        </div>

        {{-- Form --}}
        <form action="{{ route('login') }}" method="POST" class="space-y-5">
            @csrf

            {{-- Nomor Induk --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Nomor Induk Karyawan
                </label>

                <div class="relative">
                    <i class="fa-solid fa-id-card absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>

                    <input 
                        type="text"
                        name="nik"
                        placeholder="1234.12345"
                        class="w-full pl-10 pr-4 py-3 
                            rounded-lg border border-gray-300 
                            focus:outline-none 
                            focus:ring-2 focus:ring-blue-500 
                            focus:border-blue-500 
                            transition duration-200"
                        required
                    >
                </div>
            </div>

            {{-- Password --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Kata Sandi
                </label>

                <div class="relative">
                    <i class="fa-solid fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>

                    <input 
                        type="password"
                        name="password"
                        placeholder="kata sandi"
                        class="w-full pl-10 pr-4 py-3 
                            rounded-lg border border-gray-300 
                            focus:outline-none 
                            focus:ring-2 focus:ring-blue-500"
                        required
                    >
                </div>
            </div>

            {{-- Remember me --}}
            <div class="flex items-center gap-2">
                <input type="checkbox" name="remember" class="w-4 h-4 text-blue-600 rounded">
                <label class="text-sm text-gray-600">Ingat saya</label>
            </div>

            {{-- Button submit --}}
            <button 
                type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-medium transition duration-300"
            >
                Masuk →
            </button>

        </form>

    </div>

</div>

@endsection