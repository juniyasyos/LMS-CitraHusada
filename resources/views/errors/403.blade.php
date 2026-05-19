<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Akses Ditolak</title>
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
</head>
<body class="bg-gray-50 dark:bg-slate-900 flex items-center justify-center h-screen px-4 transition-colors duration-300">

    <div class="max-w-lg w-full text-center bg-white dark:bg-slate-800 rounded-3xl shadow-xl p-10 border border-gray-100 dark:border-slate-700">
        
        <div class="mb-6 flex justify-center">
            <div class="w-24 h-24 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center text-red-500">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
        </div>

        <h1 class="text-4xl font-black text-gray-800 dark:text-white mb-2">403</h1>
        <h2 class="text-xl font-bold text-gray-700 dark:text-gray-200 mb-4">Akses Ditolak</h2>
        
        <p class="text-gray-500 dark:text-gray-400 text-sm mb-8 leading-relaxed">
            Maaf, Anda tidak memiliki izin untuk mengakses halaman ini atau mencoba mengakses fungsi administratif saat sedang dalam mode impersonasi.
        </p>

        <div class="flex flex-col gap-3">
            @if(session()->has('impersonate_by'))
                <a href="{{ route('impersonate.stop') }}" class="w-full bg-amber-500 hover:bg-amber-600 text-white font-bold py-3 px-6 rounded-xl transition-colors shadow-lg shadow-amber-200 dark:shadow-none flex items-center justify-center gap-2 active:scale-95">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                    Hentikan Impersonasi & Kembali ke Admin
                </a>
            @else
                <button onclick="window.history.back()" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-xl transition-colors shadow-lg shadow-blue-200 dark:shadow-none flex items-center justify-center gap-2 active:scale-95">
                    Kembali ke Halaman Sebelumnya
                </button>
            @endif

            <a href="{{ url('/') }}" class="w-full bg-gray-100 hover:bg-gray-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-gray-700 dark:text-white font-bold py-3 px-6 rounded-xl transition-colors flex items-center justify-center gap-2 active:scale-95">
                Ke Beranda
            </a>
        </div>

    </div>

</body>
</html>
