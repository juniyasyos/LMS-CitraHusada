import './bootstrap';

function getToken() {
    let token = localStorage.getItem('token') || sessionStorage.getItem('token');
    if (!token) {
        const iamMeta = document.querySelector('meta[name="iam-access-token"]');
        if (iamMeta) {
            token = iamMeta.getAttribute('content');
            sessionStorage.setItem('token', token);
        }
    }
    return token;
}

function performWebLogout() {
    localStorage.removeItem('token');
    sessionStorage.removeItem('token');
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '/logout';

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (csrfToken) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = '_token';
        input.value = csrfToken;
        form.appendChild(input);
    }

    document.body.appendChild(form);
    form.submit();
}

// Global Frontend Route Protection
const token = getToken();
const isLoginPage = window.location.pathname === '/' || window.location.pathname === '/login';
const isServerLoggedIn = document.querySelector('meta[name="user-logged-in"]')?.getAttribute('content') === 'true';

if (isLoginPage && !isServerLoggedIn) {
    localStorage.removeItem('token');
    sessionStorage.removeItem('token');
} else if (!token && !isLoginPage) {
    if (isServerLoggedIn) {
        performWebLogout();
    } else {
        window.location.href = '/';
    }
} else if (token && isLoginPage && isServerLoggedIn) {
    window.location.href = '/pembelajaran';
}

// Inject token into all axios requests
window.axios.interceptors.request.use(function (config) {
    const currentToken = getToken();
    if (currentToken) {
        config.headers.Authorization = 'Bearer ' + currentToken;
    }
    return config;
});

// Axios response interceptor to handle 401 (Expired/Invalid Token)
window.axios.interceptors.response.use(
    function (response) {
        return response;
    },
    function (error) {
        if (error.response && error.response.status === 401 && !isLoginPage) {
            // Remove token and clear session on server side failure
            if (isServerLoggedIn) {
                performWebLogout();
            } else {
                localStorage.removeItem('token');
                sessionStorage.removeItem('token');
                window.location.href = '/';
            }
        }
        return Promise.reject(error);
    }
);

// Global Logout Function
window.handleLogout = async function (event) {
    if (event) event.preventDefault();

    const result = await Swal.fire({
        title: 'Sudah yakin keluar?',
        text: "Anda harus login kembali untuk mengakses fitur LMS.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Ya, Keluar!',
        cancelButtonText: 'Batal',
        reverseButtons: true,
        customClass: {
            popup: 'rounded-2xl dark:bg-slate-800 dark:text-white',
            confirmButton: 'rounded-lg px-6 py-2.5 text-xs font-bold',
            cancelButton: 'rounded-lg px-6 py-2.5 text-xs font-bold'
        }
    });

    if (result.isConfirmed) {
        try {
            // Coba logout via API jika ada token
            if (getToken()) {
                await window.axios.post('/api/logout');
            }
        } catch (error) {
            console.error('Logout error:', error);
        } finally {
            // Hapus token lokal
            localStorage.removeItem('token');
            sessionStorage.removeItem('token');

            // Jalankan form logout session (untuk Superadmin/Web)
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/logout';

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (csrfToken) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = '_token';
                input.value = csrfToken;
                form.appendChild(input);
            }

            document.body.appendChild(form);
            form.submit();
        }
    }
};