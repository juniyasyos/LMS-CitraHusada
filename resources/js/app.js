import './bootstrap';

function getToken() {
    return localStorage.getItem('token') || sessionStorage.getItem('token');
}

// Global Frontend Route Protection
const token = getToken();
const isLoginPage = window.location.pathname === '/' || window.location.pathname === '/login';

if (!token && !isLoginPage) {
    window.location.href = '/';
} else if (token && isLoginPage) {
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
    function(response) {
        return response;
    },
    function(error) {
        if (error.response && error.response.status === 401 && !isLoginPage) {
            // Remove token and clear session on server side failure
            localStorage.removeItem('token');
            sessionStorage.removeItem('token');
            window.location.href = '/';
        }
        return Promise.reject(error);
    }
);

// Global Logout Function
window.handleLogout = async function(event) {
    if (event) event.preventDefault();

    if (!confirm('Apakah Anda yakin ingin keluar?')) {
        return;
    }

    try {
        await window.axios.post('/api/logout');
    } catch (error) {
        console.error('Logout error:', error);
    } finally {
        localStorage.removeItem('token');
        sessionStorage.removeItem('token');
        window.location.href = '/';
    }
};