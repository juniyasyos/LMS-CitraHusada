<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <title>LMS - @yield('title', 'word')</title>
    
    <!-- Tailwind tetap di head (ikut layout lama) -->
    <script src="https://cdn.tailwindcss.com"></script>

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

</head>

<body>

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



    <!-- Lottie -->
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>



    @yield('content')

</body>
</html>