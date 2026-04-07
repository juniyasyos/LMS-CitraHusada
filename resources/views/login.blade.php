@extends('components.layout')
@section('title', 'login')

@section('content')

<div class="min-h-screen flex items-center justify-center bg-gray-100 px-4">
    
    {{-- Card --}}
    <div class="bg-white w-full max-w-md p-6 sm:p-10 rounded-2xl shadow-2xl">
        
        {{-- Logo + Title --}}
        <div class="flex items-center gap-2 mb-6 justify-center sm:justify-start">
            <img src="{{ asset('images/logo-lms.png') }}" alt="Logo" class="w-10 h-10 sm:w-12 sm:h-12">
            <div>
                <h1 class="text-red-600 font-bold text-base sm:text-lg">Citra Husada</h1>
                <p class="text-green-600 text-xs sm:text-sm">Learning Management System</p>
            </div>
        </div>

        {{-- Welcome Text --}}
        <div class="text-center mb-8">
            <h2 class="text-lg sm:text-xl font-semibold text-gray-800">
                Selamat Datang!
            </h2>

            <p id="dynamicText" class="text-gray-500 text-xs sm:text-sm mt-1">
            </p>
        </div>
        {{-- Error Container --}}
        <div id="errorContainer" class="mb-4 text-red-600 hidden text-sm"></div>
        
        {{-- Form --}}
        <form id="loginForm" class="space-y-5" onsubmit="handleLogin(event)">
            @csrf

            {{-- Nomor Induk --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Nomor Induk Karyawan
                </label>

                <div class="relative">
                    <i class="fa-solid fa-id-card absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>

                    <input 
                        type="text"
                        id="nikInput"
                        name="nik"
                        placeholder="1234.12345"
                        class="w-full pl-10 pr-4 py-3 
                            rounded-lg border border-gray-300 
                            focus:outline-none 
                            focus:ring-2 focus:ring-blue-500 
                            focus:border-blue-500 
                            transition duration-200"
                        required
                    >
                </div>
            </div>

            {{-- Password --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Kata Sandi
                </label>

                <div class="relative">
                    <i class="fa-solid fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>

                    <input 
                        type="password"
                        id="passwordInput"
                        name="password"
                        placeholder="kata sandi"
                        class="w-full pl-10 pr-4 py-3 
                            rounded-lg border border-gray-300 
                            focus:outline-none 
                            focus:ring-2 focus:ring-blue-500"
                        required
                    >
                </div>
            </div>

            {{-- Remember me --}}
            <div class="flex items-center gap-2">
                <input type="checkbox" id="rememberInput" name="remember" class="w-4 h-4 text-blue-600 rounded">
                <label id="remember" for="rememberInput" class="text-sm text-gray-600">Ingat saya</label>
            </div>

            {{-- Button submit --}}
            <button 
                type="submit"
                id="loginBtn"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-medium transition duration-300"
            >
                Masuk →
            </button>

        </form>

    </div>

</div>

<script>
async function handleLogin(event) {
    event.preventDefault();
    console.log('handleLogin called');

    const nik = document.getElementById('nikInput').value;
    const password = document.getElementById('passwordInput').value;
    const loginBtn = document.getElementById('loginBtn');
    const errorContainer = document.getElementById('errorContainer');

    console.log('Input values:', { nik, password });

    // Disable button dan show loading
    loginBtn.disabled = true;
    loginBtn.textContent = 'Memproses...';

    try {
        console.log('Step 1: Checking if axios is available...', typeof window.axios !== 'undefined');
        if (typeof window.axios === 'undefined') {
            throw new Error('Axios not available! Check if app.js is loaded.');
        }
        
        console.log('Step 2: Sending login request...');
        const response = await window.axios.post('/api/login', {
            nik: nik,
            password: password,
            remember: document.getElementById('rememberInput').checked
        });
        
        console.log('Step 3: Login response received:', response.data);

        if (response.data.success) {
            console.log('Step 4: Login successful');

            const token = response.data.data.token;
            const remember = document.getElementById('rememberInput').checked;

            // ✅ SIMPAN TOKEN
            if (remember) {
                localStorage.setItem('token', token);
            } else {
                sessionStorage.setItem('token', token);
            }

            console.log('Token saved:', token);

            // ✅ LANGSUNG REDIRECT (tidak perlu check-auth)
            window.location.href = response.data.data.redirect;
        }
        else {
            // Login gagal
            console.log('Step 3b: Login failed:', response.data.message);
            errorContainer.textContent = response.data.message;
            errorContainer.classList.remove('hidden');
        }
    } catch (error) {
        // Handle error
        console.error('Error during login:', error);
        console.error('Error details:', {
            message: error.message,
            response: error.response?.data,
            status: error.response?.status,
            config: error.config?.data
        });
        
        let errorMsg = 'Terjadi kesalahan saat login';
        
        if (error.response && error.response.data) {
            errorMsg = error.response.data.message;
            if (error.response.data.data) {
                // Handle validation errors
                const errors = error.response.data.data;
                for (const [field, messages] of Object.entries(errors)) {
                    errorMsg += '\n' + messages.join('\n');
                }
            }
        } else if (error.message) {
            errorMsg = error.message;
        }
        
        errorContainer.textContent = errorMsg;
        errorContainer.classList.remove('hidden');
        alert('🚨 ' + errorMsg); // Also show alert to make sure user sees the error
    } finally {
        // Re-enable button
        loginBtn.disabled = false;
        loginBtn.textContent = 'Masuk →';
    }
}

// Log when page loads to verify JS is working
console.log('Login page loaded, checking for required elements...');
console.log('Form element:', document.getElementById('loginForm'));
console.log('Button element:', document.getElementById('loginBtn'));
console.log('Error container:', document.getElementById('errorContainer'));
</script>

@endsection