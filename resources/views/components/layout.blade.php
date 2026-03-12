<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <title>LMS - @yield('title', 'word')</title>
    
</head>
<body>
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const texts = [
                "Belajar tidak mengenal usia",
                "Belajar bisa dimulai kapan saja",
                "Ilmu selalu punya tempat",
                "Terus belajar, terus berkembang",
                "Langkah kecil untuk ilmu besar",
                "Sehat itu sederhana, asal konsisten",
                "Jangan lupa jaga kesehatan",
                "Sehat bikin hidup lebih nyaman",
                "Tubuh sehat, pikiran kuat",
                "Mulai hari ini, hidup lebih sehat",
                "Belajar pelan pelan, yang penting jalan",
                "Kesehatan bukan pilihan, tapi kebutuhan"
                ];

            const randomText = texts[Math.floor(Math.random() * texts.length)];
                document.getElementById("dynamicText").innerText = randomText;
            });
    </script>

    <script src="//unpkg.com/alpinejs" defer></script>

    <script>
    document.addEventListener("DOMContentLoaded", function(){

        const toggle = document.getElementById("toggleSidebar");
        const sidebar = document.getElementById("sidebar");
        const overlay = document.getElementById("overlay");

        if(toggle){
            toggle.addEventListener("click", function(){
                sidebar.classList.toggle("-translate-x-full");
                overlay.classList.toggle("hidden");
            });
        }

        if(overlay){
            overlay.addEventListener("click", function(){
                sidebar.classList.add("-translate-x-full");
                overlay.classList.add("hidden");
            });
        }

    });
    </script>
    
    <script>
    document.querySelectorAll('.jawaban').forEach(function(item) {
        item.addEventListener('click', function() {

            setTimeout(function() {
                window.location.href = "/soal-10"; 
            }, 300);

        });
    });
    </script>

    <script>

    function bukaPopup(){
        document.getElementById("popupKirim").classList.remove("hidden");
        document.getElementById("popupKirim").classList.add("flex");
    }

    function tutupPopup(){
        document.getElementById("popupKirim").classList.add("hidden");
    }

    function lanjutSoal(){
        window.location.href = "/hasil-kuis"; // halaman setelah soal terakhir
    }

    </script>
    
    @yield('content')
</body>
</html>