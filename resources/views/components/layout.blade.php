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

    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>

    <script>
    function quizApp() {
        return {
            welcomeOpen: false,
            open: false,
            confirmOpen: false,

            soalKe: 1,
            totalSoal: 10,
            jawaban: null,

            currentMsg: 0,
            messages: [
                { title: "Santai aja, baca pelan-pelan.", desc: "Kamu pasti bisa jawab." },
                    { title: "Tidak perlu buru-buru.", desc: "Fokus satu soal dulu." },
                    { title: "Kamu sudah belajar,", desc: "sekarang tinggal tunjukin aja." },
                    { title: "Percaya sama jawabanmu.", desc: "Kamu lebih paham dari yang kamu kira" },
                    { title: "Kalau ragu, tarik napas dulu.", desc: "Lalu coba lagi pelan-pelan." },
                    { title: "Sedikit lagi selesai.", desc: "Tetap fokus ya!" },
                    { title: "Tidak harus sempurna,", desc: "yang penting kamu mencoba." },
                    { title: "Kerjakan dengan tenang.", desc: "Jawab yang kamu yakin dulu." },
                    { title: "Satu soal demi satu soal.", desc: "Kamu pasti sampai akhir." },
                    { title: "Kamu sudah sampai di sini,", desc: "itu artinya kamu siap." },
                    { title: "Tidak apa-apa kalau ragu,", desc: "yang penting tetap lanjut." },
                    { title: "Fokus,", desc: "kamu lagi on track." },
                    { title: "Jawab saja yang menurutmu paling benar.", desc: "Ikuti instingmu." },
                    { title: "Kamu tidak sendirian,", desc: "banyak yang juga berproses seperti kamu." },
                    { title: "Sedikit lagi, kamu hampir selesai.", desc: "Tetap semangat!" }
            ],

            init() {
                this.welcomeOpen = true
                setTimeout(() => this.welcomeOpen = false, 2000)

                this.$watch('soalKe', (v) => {
                    if (v % 3 === 0 && v !== this.totalSoal) {
                        this.currentMsg = Math.floor(Math.random() * this.messages.length)
                        this.showPopup()
                    }
                })
            },

            showPopup() {
                this.open = true
                setTimeout(() => this.open = false, 2000)
            },

            closePopup() {
                this.open = false
            },

            handleButton() {
                if (this.soalKe < this.totalSoal) {
                    this.soalKe++
                    this.jawaban = null
                } else {
                    this.confirmOpen = true
                }
            },

            submitQuiz() {
                this.confirmOpen = false
                setTimeout(() => {
                    window.location.href = "/hasil-kuis"
                }, 300)
            }
        }
    }
    </script>
    
    @yield('content')
</body>
</html>