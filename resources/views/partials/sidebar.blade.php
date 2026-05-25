<!-- Sidebar -->
<aside class="w-64 bg-primary-900 flex flex-col h-screen transition-all duration-300 ease-in-out shrink-0">
    <!-- Brand/Logo -->
    <div class="h-20 flex items-center px-6 gap-3">
        <div class="w-10 h-10 bg-primary-600 rounded-xl flex items-center justify-center text-white shadow-lg">
            <i class="fa-solid fa-microchip text-xl"></i>
        </div>
        <div class="flex flex-col">
            <span class="text-white font-bold text-lg leading-tight">Inventaris</span>
            <span class="text-primary-400 text-xs font-medium uppercase tracking-wider">Management System</span>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
        <div class="text-primary-400 text-[11px] font-bold uppercase tracking-widest px-3 mb-2 opacity-50">Main Menu</div>
        
        <button onclick="switchView('dashboard')" id="btn-nav-dashboard" 
            class="nav-link w-full flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-200 group bg-primary-800 text-white shadow-sm border border-primary-700/50">
            <i class="fa-solid fa-chart-line w-5 text-center group-hover:scale-110 transition-transform"></i>
            <span class="font-medium">Overview</span>
        </button>

        <button onclick="switchView('transactions')" id="btn-nav-transactions" 
            class="nav-link w-full flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-200 group text-primary-300 hover:bg-primary-800/50 hover:text-white">
            <i class="fa-solid fa-arrow-right-arrow-left w-5 text-center group-hover:scale-110 transition-transform"></i>
            <span class="font-medium">Transactions</span>
        </button>

        <button onclick="switchView('categories')" id="btn-nav-categories" 
            class="nav-link w-full flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-200 group text-primary-300 hover:bg-primary-800/50 hover:text-white">
            <i class="fa-solid fa-boxes-stacked w-5 text-center group-hover:scale-110 transition-transform"></i>
            <span class="font-medium">Warehouse</span>
        </button>

        <div class="pt-6">
            <div class="text-primary-400 text-[11px] font-bold uppercase tracking-widest px-3 mb-2 opacity-50">Support</div>
            
            <button onclick="switchView('finance')" id="btn-nav-finance" 
                class="nav-link w-full flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-200 group text-primary-300 hover:bg-primary-800/50 hover:text-white">
                <i class="fa-solid fa-file-invoice-dollar w-5 text-center group-hover:scale-110 transition-transform"></i>
                <span class="font-medium">Finance & Taxes</span>
            </button>

            <button onclick="switchView('partners')" id="btn-nav-partners" 
                class="nav-link w-full flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-200 group text-primary-300 hover:bg-primary-800/50 hover:text-white">
                <i class="fa-solid fa-handshake w-5 text-center group-hover:scale-110 transition-transform"></i>
                <span class="font-medium">Partners</span>
            </button>
        </div>
    </nav>

    <!-- User Profile & Logout -->
    <div class="p-4 border-t border-primary-800">
        <div class="bg-primary-800/50 p-3 rounded-2xl flex items-center gap-3 mb-3">
            <div class="w-10 h-10 rounded-full bg-primary-600 flex items-center justify-center text-white font-bold border-2 border-primary-700">
                <i class="fa-solid fa-user text-sm"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-white text-sm font-bold truncate" id="user-display-name">Admin User</p>
                <p class="text-primary-400 text-xs truncate">Administrator</p>
            </div>
        </div>
        <button onclick="logout()" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-red-400 hover:bg-red-500/10 hover:text-red-300 transition-colors font-medium text-sm">
            <i class="fa-solid fa-right-from-bracket"></i>
            <span>Sign Out</span>
        </button>
    </div>
</aside>

<style>
    .nav-link.active {
        background-color: #1e1b4b; /* primary-900/80 */
        color: white;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
</style>
