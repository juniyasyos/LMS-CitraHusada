<header class="bg-white dark:bg-slate-900 border-b dark:border-slate-800 h-16 flex items-center justify-between px-4 lg:px-8 shrink-0 z-10 transition-colors duration-300">
    <div class="flex items-center gap-4">
        <button @click="sidebarOpen = true"
            class="lg:hidden p-2 text-gray-500 dark:text-white hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition">
            <i class="fa-solid fa-bars text-lg"></i>
        </button>
        <h1 class="text-sm font-semibold text-gray-600 dark:text-white truncate uppercase tracking-wider">
            {{ $title }}
        </h1>
    </div>

    <div class="flex items-center gap-3 lg:gap-6">
        {{-- Search bar --}}
        {{-- <div class="relative w-48 lg:w-64 hidden md:block">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 dark:text-gray-300">
                <i class="fa-solid fa-magnifying-glass text-[10px]"></i>
            </span>
            <input type="text"
                class="block w-full pl-8 pr-3 py-1.5 border border-gray-200 dark:border-slate-700 rounded-lg bg-gray-50 dark:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-[11px] text-gray-700 dark:text-white transition-all"
                placeholder="Cari data...">
        </div> --}}

        <div class="flex items-center gap-2 lg:gap-4">
            <div>
                @include('components.notif-superadmin')
            </div>

            <div class="flex items-center gap-3 pl-2 lg:pl-4 border-l border-gray-100 dark:border-slate-800">
                <div class="text-right hidden sm:block">
                    <p class="text-xs font-bold text-gray-800 dark:text-white leading-tight">
                        {{ auth()->user()->name }}
                    </p>
                    <p class="text-[10px] text-gray-500 dark:text-gray-300 font-medium italic">
                        {{ auth()->user()->role->role ?? 'unknown' }}
                    </p>
                </div>
                <div class="w-8 h-8 bg-gray-200 dark:bg-slate-700 rounded-full overflow-hidden border border-gray-100 dark:border-slate-800 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-user text-gray-500 dark:text-white text-xs"></i>
                </div>
            </div>
        </div>
    </div>
</header>
