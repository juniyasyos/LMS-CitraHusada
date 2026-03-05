<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <title>LMS - @yield('title', 'word')</title>
    
    <script src="https://cdn.tailwindcss.com"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])



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

    <script src="//unpkg.com/alpinejs" defer></script>


</head>
<body>
    @yield('content')
</body>
</html>