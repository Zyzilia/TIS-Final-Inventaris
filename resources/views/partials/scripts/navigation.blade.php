let activeView = 'dashboard';

function switchView(viewName) {
    // Hide all views
    document.getElementById('view-dashboard').classList.add('hidden');
    document.getElementById('view-transactions').classList.add('hidden');
    document.getElementById('view-categories').classList.add('hidden');
    document.getElementById('view-finance').classList.add('hidden');
    document.getElementById('view-partners').classList.add('hidden');

    // Show active view
    document.getElementById(`view-${viewName}`).classList.remove('hidden');

    // Toggle sidebar button styles
    const navButtons = ['dashboard', 'transactions', 'categories', 'finance', 'partners'];
    navButtons.forEach(btnName => {
        const btn = document.getElementById(`btn-nav-${btnName}`);
        if (btnName === viewName) {
            btn.classList.add('bg-primary-800', 'text-white', 'shadow-sm', 'border', 'border-primary-700/50');
            btn.classList.remove('text-primary-300', 'hover:bg-primary-800/50', 'hover:text-white');
        } else {
            btn.classList.remove('bg-primary-800', 'text-white', 'shadow-sm', 'border', 'border-primary-700/50');
            btn.classList.add('text-primary-300', 'hover:bg-primary-800/50', 'hover:text-white');
        }
    });

    activeView = viewName;

    // Trigger corresponding view loader
    if (viewName === 'transactions') {
        renderTransactions();
    } else if (viewName === 'categories') {
        backToWarehouseGrid();
    } else if (viewName === 'finance') {
        fetchExchangeRate();
    } else if (viewName === 'partners') {
        loadPartners();
    }
}

function closeAllDropdowns() {
    document.querySelectorAll('.notifDropdown, .profileDropdown, .globalSearchResults').forEach(d => {
        d.classList.add('hidden');
    });
}

document.addEventListener('click', (e) => {
    if (!e.target.closest('.relative')) {
        closeAllDropdowns();
    }
});

function toggleProfileDropdown(avatar) {
    const container = avatar.closest('.relative');
    const dropdown = container.querySelector('.profileDropdown');
    const isOpen = !dropdown.classList.contains('hidden');
    
    closeAllDropdowns();
    
    if (!isOpen) {
        dropdown.classList.remove('hidden');
    }
}
