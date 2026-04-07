<!-- NAV CONTAINER -->
<div class="w-full h-full flex flex-col bg-white shadow-md">
    {{-- Logo + Title --}}
    <div class="p-1 border-b border-gray-200 shrink-0">
        <div class="flex items-center gap-1 mb-6 mt-6">
        <img src="{{ asset('images/logo-lms.png') }}" alt="Logo" class="w-12 h-12">
            <div>
                <h1 class="text-red-600 font-bold text-lg">Citra Husada</h1>
                <p class="text-green-600 text-sm ">Learning Management System</p>
            </div>
        </div>
    </div>
    
    <nav class="p-4 space-y-2 flex-grow overflow-y-auto">
        <a href="pembelajaran" class="flex items-center gap-2 px-4 py-2 bg-blue-100 text-blue-600 rounded-lg">
            <i class="fa-solid fa-book"></i>
            Pembelajaran Saya
        </a>
        <a href="#" class="flex items-center gap-2 px-4 py-2 hover:bg-gray-100 rounded-lg">
            <i class="fa-solid fa-certificate"></i>
            Sertifikat
        </a>
        <!-- PROFILE DROPDOWN CARD -->
        <div x-data="{ open: false }" class="relative">

            <!-- Button Profil -->
            <button 
                @click="open = !open"
                :class="open ? 'bg-blue-100 text-blue-600' : 'hover:bg-gray-100'"
                class="w-full flex items-center gap-2 px-4 py-2 rounded-lg transition"
            >
                <i class="fa-solid fa-circle-user"></i>
                Profil
            </button>

            <!-- Profile Card -->
            <div 
                x-show="open"
                x-transition
                @click.away="open = false"
                class="ml-4 mt-3 bg-blue-50 border border-blue-100 shadow-md rounded-xl p-5 text-sm w-52"
            >

                <!-- Foto Profil -->
                <div class="flex flex-col mb-4">
                    <div class="w-16 h-16 rounded-full bg-gray-300 flex items-center justify-center self-center mb-4">
                        <i class="fa-solid fa-user text-gray-500 text-xl"></i>
                    </div>
                    <p class="text-left">Nama</p>
                    <p id="navProfileName" class="text-xs text-left font-semibold border-2 border-blue-300 p-2 rounded-lg"></p>
                    <p class="text-left">NIK</p>
                    <p id="navProfileNIK" class="text-xs text-left font-semibold border-2 border-blue-300 p-2 rounded-lg"></p>
                    <p class="text-left">Unit</p>
                    <p id="navProfileJabatan" class="text-xs text-left font-semibold border-2 border-blue-300 p-2 rounded-lg"></p>
                </div>

                <!-- Divider -->
                <div class="border-t border-blue-100 pt-3">

                    <!-- Pengaturan Mode -->
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-600">Mode Tampilan</span>

                        <button 
                            @click="document.documentElement.classList.toggle('dark')"
                            class="text-xs px-3 py-1 bg-white border rounded-md hover:bg-gray-100"
                        >
                            🌙 / ☀️
                        </button>
                    </div>

                </div>

            </div>

        </div>

        <div class="pt-2">
            <a href="#" onclick="handleLogout(event)"
            class="flex items-center gap-2 px-4 py-2 text-red-600 rounded-lg hover:bg-gray-100 transition duration-200">
                <i class="fa-solid fa-arrow-left"></i>
                Keluar
            </a>
        </div>
    </nav>
</div>
<script>
document.addEventListener("DOMContentLoaded", loadUserProfile);

async function loadUserProfile(){
    try{
        const response = await axios.get('/api/profile');

        if(response.data.success){
            const user = response.data.data;

            const nama = user.nama;
            const unitKerja = user.unit_kerja?.unit_kerja ?? '-';
            const jenisTenaga = user.jenis_tenaga?.jenis_tenaga ?? '-';
            const nik = user.nik ?? '-';

                document.getElementById("navProfileName").innerText = nama;
                document.getElementById("navProfileJabatan").innerText = "Unit " + unitKerja;
                document.getElementById("navProfileNIK").innerText = nik;
        }
    }catch(error){
        console.error("Gagal load profile:", error);
    }
}
</script>