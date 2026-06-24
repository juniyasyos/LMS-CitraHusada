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
        
        {{-- Form --}}
        @if(config('iam.enabled'))
            <div class="text-center mt-6">
                <a href="{{ route('iam.sso.login') }}" class="w-full inline-block bg-red-600 hover:bg-red-700 text-white py-3 rounded-lg font-medium transition duration-300">
                    <i class="fa-solid fa-right-to-bracket mr-2"></i> Login via SSO (IAM)
                </a>
            </div>
        @else
        <form id="loginForm" class="space-y-5" onsubmit="handleLogin(event)">
            @csrf

            {{-- Nomor Induk --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Nomor Induk Karyawan
                </label>

                <div class="relative">
                    <span class="absolute left-3 inset-y-0 flex items-center text-gray-400 pointer-events-none">
                        <i class="fa-solid fa-id-card"></i>
                    </span>

                    <input 
                        type="text"
                        id="nipInput"
                        name="nip"
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
                    <span class="absolute left-3 inset-y-0 flex items-center text-gray-400 pointer-events-none">
                        <i class="fa-solid fa-lock"></i>
                    </span>

                    <input 
                        type="password"
                        id="passwordInput"
                        name="password"
                        placeholder="kata sandi"
                        class="w-full pl-10 pr-10 py-3 
                            rounded-lg border border-gray-300 
                            focus:outline-none 
                            focus:ring-2 focus:ring-blue-500"
                        required
                    >

                    <button 
                        type="button" 
                        id="togglePassword"
                        onclick="togglePasswordVisibility()"
                        class="absolute right-3 inset-y-0 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none transition duration-200"
                        tabindex="-1"
                    >
                        <i id="eyeIcon" class="fa-solid fa-eye"></i>
                    </button>
                </div>
            </div>

            {{-- Error Container --}}
            <div id="errorContainer" class="hidden text-red-600 text-xs font-semibold bg-red-50 border border-red-200 rounded-lg p-3 flex items-center gap-2">
                <i class="fa-solid fa-circle-exclamation text-sm shrink-0 text-red-500"></i>
                <span id="errorText"></span>
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
        @endif

    </div>

</div>

<script>
async function handleLogin(event) {
    event.preventDefault();
    console.log('handleLogin called');

    const nip = document.getElementById('nipInput').value;
    const password = document.getElementById('passwordInput').value;
    const loginBtn = document.getElementById('loginBtn');
    const errorContainer = document.getElementById('errorContainer');
    const errorText = document.getElementById('errorText');

    console.log('Input values:', { nip, password });

    // Reset error container
    errorContainer.classList.add('hidden');
    errorContainer.classList.remove('flex');

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
            nip: nip,
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
            if (errorText) errorText.textContent = response.data.message;
            errorContainer.classList.remove('hidden');
            errorContainer.classList.add('flex');
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
        
        if (errorText) errorText.textContent = errorMsg;
        errorContainer.classList.remove('hidden');
        errorContainer.classList.add('flex');
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

function togglePasswordVisibility() {
    const passwordInput = document.getElementById('passwordInput');
    const eyeIcon = document.getElementById('eyeIcon');

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeIcon.classList.remove('fa-eye');
        eyeIcon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        eyeIcon.classList.remove('fa-eye-slash');
        eyeIcon.classList.add('fa-eye');
    }
}
</script>

@endsection