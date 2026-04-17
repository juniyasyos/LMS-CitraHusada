<div x-data="{ openNotif: false }" class="relative">
    <button 
        @click="openNotif = !openNotif"
        class="relative text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition">

        <i class="fas fa-bell text-lg"></i>

        <span class="absolute -top-1 -right-1 w-2 h-2 bg-red-500 rounded-full border-2 border-white dark:border-slate-900"></span>

    </button>

    <div 
        x-show="openNotif"
        @click.away="openNotif = false"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-cloak
        class="absolute right-0 mt-3 w-80 bg-white dark:bg-slate-900 rounded-xl shadow-xl border border-gray-100 dark:border-slate-800 p-4 z-50 transition-colors duration-300">

        <div class="flex items-center justify-between mb-4 border-b dark:border-slate-800 pb-2">
            <p class="font-bold text-gray-700 dark:text-white text-sm uppercase tracking-wider">
                Notifikasi
            </p>
            <span class="bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-[10px] px-2 py-0.5 rounded-full font-bold">3 Baru</span>
        </div>

        <div class="space-y-1 max-h-80 overflow-y-auto custom-scrollbar">

            <div class="p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-800 cursor-pointer transition-colors group">
                <p class="font-bold text-gray-800 dark:text-white text-xs group-hover:text-blue-600 dark:group-hover:text-blue-400">
                    Modul Baru Tersedia
                </p>
                <p class="text-gray-500 dark:text-gray-300 text-[11px] mt-1 leading-relaxed">
                    Modul "Keselamatan Pasien" telah ditambahkan ke dashboard Anda.
                </p>
            </div>

            <div class="p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-800 cursor-pointer transition-colors group">
                <p class="font-bold text-gray-800 dark:text-white text-xs group-hover:text-blue-600 dark:group-hover:text-blue-400">
                    Deadline Mendekat
                </p>
                <p class="text-gray-500 dark:text-gray-300 text-[11px] mt-1 leading-relaxed">
                    Masa berlaku modul "Manajemen RS" akan segera berakhir.
                </p>
            </div>

            <div class="p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-800 cursor-pointer transition-colors group">
                <p class="font-bold text-gray-800 dark:text-white text-xs group-hover:text-blue-600 dark:group-hover:text-blue-400">
                    Sertifikat Tersedia
                </p>
                <p class="text-gray-500 dark:text-gray-300 text-[11px] mt-1 leading-relaxed">
                    Sertifikat pelatihan periode April sudah bisa diunduh sekarang.
                </p>
            </div>

        </div>

        <div class="mt-4 pt-3 border-t dark:border-slate-800 text-center">
            <button class="text-blue-600 dark:text-blue-400 text-[11px] font-bold uppercase tracking-widest hover:underline">
                Lihat semua notifikasi
            </button>
        </div>

    </div>
</div>