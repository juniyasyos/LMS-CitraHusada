<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-logged-in" content="{{ Auth::check() ? 'true' : 'false' }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <title>LMS - @yield('title', 'word')</title>
    
    <!-- Tailwind tetap di head (ikut layout lama) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class', 
            theme: { extend: {} }
        }
    </script>
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Dynamic Text (versi lama dipertahankan) -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const texts = [
                "Belajar tidak mengenal usia",                
                "Belajar bisa dimulai kapan saja",
                "Ilmu selalu punya tempat",
                "Terus belajar, terus berkembang",
                "Langkah kecil untuk ilmu besar",
                "Belajar itu sederhana, asal konsisten",
                "Jangan lupa terus menambah ilmu",
                "Belajar bikin hidup lebih berkembang",
                "Pikiran terlatih, masa depan kuat",
                "Mulai hari ini, jadi lebih pintar",
                "Belajar pelan-pelan, yang penting jalan",
                "Ilmu bukan pilihan, tapi kebutuhan",
            ];

            const el = document.getElementById("dynamicText");
            if (el) {
                const randomText = texts[Math.floor(Math.random() * texts.length)];
                el.innerText = randomText;
            }
        });
    </script>

    <!-- Alpine (fix: hanya sekali) -->
    <script src="//unpkg.com/alpinejs" defer></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    @if(session()->has('impersonate_by'))
    <div class="fixed top-0 left-0 right-0 z-[9999] bg-amber-500 text-white px-4 py-2 flex justify-between items-center shadow-lg animate-bounce-subtle">
        <div class="flex items-center gap-3">
            <div class="bg-white/20 p-1.5 rounded-lg">
                <i class="fa-solid fa-user-secret text-sm"></i>
            </div>
            <p class="text-xs font-bold tracking-wide">
                MODE IMPERSONASI: Anda sedang masuk sebagai <span class="underline decoration-2 underline-offset-4">{{ Auth::user()->nama }}</span>
            </p>
        </div>
        <a href="{{ route('impersonate.stop') }}" 
           class="bg-white text-amber-600 px-4 py-1.5 rounded-full text-[10px] font-black uppercase hover:bg-amber-50 transition active:scale-95 shadow-sm">
            Kembali ke Admin
        </a>
    </div>
    <style>
        @keyframes bounce-subtle {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(3px); }
        }
        .animate-bounce-subtle { animation: bounce-subtle 3s infinite ease-in-out; }
        body { padding-top: 45px; } /* Space for the banner */
    </style>
    @endif

    <!-- Sidebar Toggle (SAFE VERSION) -->
    <script>
        document.addEventListener("DOMContentLoaded", function(){

            const toggle = document.getElementById("toggleSidebar");
            const sidebar = document.getElementById("sidebar");
            const overlay = document.getElementById("overlay");

            if(toggle && sidebar && overlay){
                toggle.addEventListener("click", function(){
                    sidebar.classList.toggle("-translate-x-full");
                    overlay.classList.toggle("hidden");
                });

                overlay.addEventListener("click", function(){
                    sidebar.classList.add("-translate-x-full");
                    overlay.classList.add("hidden");
                });
            }
        });
    </script>



    <!-- Global Notifications -->
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        window.showExportNotification = function() {
            Swal.fire({
                icon: 'info',
                title: 'Memproses...',
                text: 'Data sedang disiapkan. Unduhan akan dimulai otomatis.',
                timer: 4000,
                showConfirmButton: false,
                timerProgressBar: true,
                position: 'top-end',
                toast: true
            });
        };

        @if(session('success'))
            Toast.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: {!! json_encode(session('success')) !!}
            });
        @endif

        @if(session('error'))
            Toast.fire({
                icon: 'error',
                title: 'Gagal!',
                text: {!! json_encode(session('error')) !!}
            });
        @endif

        @if(session('warning'))
            Toast.fire({
                icon: 'warning',
                title: 'Peringatan!',
                text: {!! json_encode(session('warning')) !!}
            });
        @endif

    </script>

    <!-- Lottie -->
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>



    @yield('content')

</body>
</html>