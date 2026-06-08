// Authentication Check
const token = localStorage.getItem('jwt_token');
const userStr = localStorage.getItem('user_data');
if (!token) {
    window.location.href = '/login';
} else {
    document.getElementById('authCheck').style.display = 'none';
    if(userStr) {
        const user = JSON.parse(userStr);
        document.querySelectorAll('.profileImg').forEach(img => {
            img.src = `https://ui-avatars.com/api/?name=${encodeURIComponent(user.name)}&background=7c3aed&color=fff`;
        });
        document.querySelectorAll('.dropdownUserName').forEach(el => {
            el.textContent = user.name;
        });
        const sidebarName = document.getElementById('user-display-name');
        if (sidebarName) sidebarName.textContent = user.name;
        document.querySelectorAll('.dropdownUserEmail').forEach(el => {
            el.textContent = user.email;
        });
        document.querySelectorAll('.dropdownUserRole').forEach(el => {
            el.textContent = user.role === 'admin' ? 'Administrator' : 'Staff Gudang';
        });

        // UI Restrictions based on Role
        if (user.role !== 'admin') {
            // Hide "Add New Item" buttons
            const shelfAddBtn = document.getElementById('addShelfItemBtn');
            if (shelfAddBtn) shelfAddBtn.style.display = 'none';

            // Hide "Add Transaction" button
            const txBtn = document.getElementById('btnAddTransaction');
            if (txBtn) txBtn.style.display = 'none';

            // Hide "Add Partner" button
            const partnerBtn = document.getElementById('addPartnerBtn');
            if (partnerBtn) partnerBtn.style.display = 'none';
        }
    }
}

// Axios Config
const BASE_URL = '/api';
axios.interceptors.request.use(config => {
    if (token) config.headers.Authorization = `Bearer ${token}`;
    return config;
});

function logout() {
    axios.post('/api/auth/logout').finally(() => {
        localStorage.removeItem('jwt_token');
        localStorage.removeItem('user_data');
        window.location.href = '/login';
    });
}
