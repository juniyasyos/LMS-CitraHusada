<!-- SIDEBAR -->
<aside class="w-64 bg-white shadow-md">
    {{-- Logo + Title --}}
    <div class="p-1 border-b border-grey-200">
        <div class="flex items-center gap-1 mb-6 mt-6">
        <img src="{{ asset('images/logo-lms.png') }}" alt="Logo" class="w-12 h-12">
            <div>
                <h1 class="text-red-600 font-bold text-lg">Citra Husada</h1>
                <p class="text-green-600 text-sm ">Learning Management System</p>
            </div>
        </div>
    </div>
    

    <nav class="p-4 space-y-2">
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
                <div class="flex flex-col items-center text-center mb-4">
                    <div class="w-16 h-16 rounded-full bg-gray-300 flex items-center justify-center">
                        <i class="fa-solid fa-user text-gray-500 text-xl"></i>
                    </div>

                    <p id="navProfileName" class="font-semibold mt-3"></p>
                    <p id="navProfileJabatan" class="text-xs text-gray-500"></p>
                    <p id="navProfileNIK" class="text-xs text-gray-400"></p>
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
    </nav>

    <div class="p-4 border-t border-gray-200">
        <a href="/" 
        class="flex items-center gap-2 text-red-600 
                hover:text-red-800 transition duration-200">
            <i class="fa-solid fa-arrow-left"></i>
            Keluar
        </a>
    </div>

</aside>
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