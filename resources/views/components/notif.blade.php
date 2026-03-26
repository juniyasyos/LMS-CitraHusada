<div x-data="notifComponent()" class="relative">

    <!-- ICON BELL -->
    <button 
        @click="openNotif = !openNotif; if(openNotif) fetchNotifications()"
        class="relative text-gray-600 hover:text-blue-600 transition">

        <i class="fas fa-bell text-lg"></i>

        <!-- NOTIFICATION DOT -->
        <span 
            x-show="unreadCount > 0"
            x-text="unreadCount"
            class="absolute -top-2 -right-2 text-xs bg-red-500 text-white px-1.5 rounded-full">
        </span>

    </button>

    <!-- DROPDOWN NOTIFICATION -->
    <div 
        x-show="openNotif"
        @click.away="openNotif = false"
        x-transition
        class="absolute right-0 mt-3 w-72 bg-white rounded-xl shadow-lg border p-4 z-50">

        <!-- HEADER -->
        <p class="font-semibold mb-3 text-gray-700">
            Notifikasi
        </p>

        <!-- LIST NOTIFICATION -->
        <div class="space-y-3 text-sm max-h-60 overflow-y-auto">

            <template x-for="notif in notifications" :key="notif.id">
                <div 
                    @click="markAsRead(notif.id)"
                    class="p-3 rounded-lg cursor-pointer"
                    :class="notif.is_read ? 'bg-white' : 'bg-blue-50'"
                >
                    <p class="font-medium" x-text="notif.title"></p>
                    <p class="text-gray-500 text-xs" x-text="notif.message"></p>
                </div>
            </template>

            <!-- Kalau kosong -->
            <div x-show="notifications.length === 0" class="text-center text-gray-400 text-sm">
                Tidak ada notifikasi
            </div>

        </div>

        <!-- FOOTER -->
        <div class="mt-4 text-center">
            <button class="text-blue-600 text-sm hover:underline">
                Lihat semua notifikasi
            </button>
        </div>

    </div>
</div>

<script>
function notifComponent() {
    return {
        openNotif: false,
        notifications: [],
        unreadCount: 0,

        async fetchNotifications() {
            try {
                const res = await axios.get('/api/notifications');
                this.notifications = res.data.data;
            } catch (e) {
                console.error('Gagal ambil notif', e);
            }
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
            this.fetchNotifications();
            this.fetchUnreadCount();
        }
    }
}
</script>