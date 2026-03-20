<div class="flex justify-between items-center p-4 border-b border-gray-200">

    <!-- LOGO -->
    <div class="flex items-center gap-2">
        <img src="{{ asset('images/logo-lms.png') }}" alt="Logo" class="w-12 h-12">

        <div>
            <h1 class="text-red-600 font-bold text-lg">Citra Husada</h1>
            <p class="text-green-600 text-sm">Learning Management System</p>
        </div>
    </div>

    <!-- NOTIF + USER -->
    <div class="flex items-center gap-4">

        @include('components.notif')

        <div class="text-right">
            <p id="headerNama" class="font-medium">-</p>
            <p id="headerUnit" class="text-sm text-gray-500">-</p>
        </div>
    </div>
</div>

<script>

document.addEventListener('DOMContentLoaded', function () {
    loadHeaderProfile();
});

async function loadHeaderProfile() {

    try {

        const response = await axios.get('/api/profile');

        const user = response.data.data;

        const nama = user.nama;
        const unit = user.unit_kerja?.unit_kerja ?? '-';

        // isi nama
        document.getElementById("headerNama").innerText = nama;

        // tambahkan kata "Unit"
        document.getElementById("headerUnit").innerText =
            "Unit " + unit;

    } catch (error) {

        console.error("Error load header profile:", error);

    }

}

</script>