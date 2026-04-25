<div x-data="notifAdminComponent()" class="relative">
    <button 
        @click="openNotif = !openNotif; if(openNotif) fetchNotifications()"
        class="relative text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition">

        <i class="fas fa-bell text-lg"></i>

        <span x-show="unreadCount > 0" x-text="unreadCount" class="absolute -top-2 -right-2 text-[10px] bg-red-500 text-white rounded-full px-1.5 border-2 border-white dark:border-slate-900"></span>

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
            <span x-show="unreadCount > 0" class="bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-[10px] px-2 py-0.5 rounded-full font-bold" x-text="unreadCount + ' Baru'"></span>
        </div>

        <div class="space-y-1 max-h-80 overflow-y-auto custom-scrollbar">

            <template x-for="notif in notifications" :key="notif.id">
                <div @click="markAsRead(notif.id)" class="p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-800 cursor-pointer transition-colors group" :class="notif.is_read ? 'bg-white dark:bg-slate-900' : 'bg-blue-50 dark:bg-slate-800/80'">
                    <p class="font-bold text-gray-800 dark:text-white text-xs group-hover:text-blue-600 dark:group-hover:text-blue-400" x-text="notif.title"></p>
                    <p class="text-gray-500 dark:text-gray-300 text-[11px] mt-1 leading-relaxed" x-text="notif.message"></p>
                </div>
            </template>

            <div x-show="notifications.length === 0" class="text-center text-gray-400 text-sm py-4">
                Tidak ada notifikasi terbaru
            </div>

        </div>

        <div class="mt-4 pt-3 border-t dark:border-slate-800 text-center" x-show="totalAll > 0">
            <button @click="toggleShowAll()" class="text-blue-600 dark:text-blue-400 text-[11px] font-bold uppercase tracking-widest hover:underline" x-text="showAll ? 'Sembunyikan' : 'Lihat semua notifikasi'">
            </button>
        </div>

    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('notifAdminComponent', () => ({
        openNotif: false,
        notifications: [],
        unreadCount: 0,
        totalAll: 0,
        showAll: false,

        async fetchNotifications() {
            try {
                const endpoint = this.showAll ? '/api/notifications' : '/api/notifications/unread';
                const res = await axios.get(endpoint);
                this.notifications = res.data.data;
                this.totalAll = res.data.total_all;
            } catch (e) {
                console.error('Gagal ambil notif', e);
            }
        },

        toggleShowAll() {
            this.showAll = !this.showAll;
            this.fetchNotifications();
        },

        async fetchUnreadCount() {
            try {
                const res = await axios.get('/api/notifications/count');
                this.unreadCount = res.data.total_unread;
            } catch (e) {
                console.error('Gagal ambil count', e);
            }
        },

        async markAsRead(id) {
            try {
                await axios.post(`/api/notifications/${id}/read`);
                this.fetchNotifications();
                this.fetchUnreadCount();
            } catch (e) {
                console.error('Gagal read notif', e);
            }
        },

        init() {
            this.unreadCount = 0;
            this.fetchUnreadCount();
        }
    }));
});
</script>