<!-- Left Sidebar -->
<aside class="w-20 bg-darknav m-3 rounded-[1.5rem] flex flex-col items-center py-6 shadow-xl z-10 shrink-0 justify-between">
    <div class="flex flex-col items-center gap-8 w-full">
        <div class="text-white text-3xl mb-2"><i class="fa-solid fa-asterisk"></i></div>
        
        <nav class="flex flex-col gap-4 w-full px-3">
            <button onclick="switchView('dashboard')" id="btn-nav-dashboard" class="bg-white text-darknav w-full aspect-square rounded-xl shadow flex justify-center items-center relative transition-all duration-200">
                <i class="fa-solid fa-table-cells-large text-lg"></i>
            </button>
            <button onclick="switchView('transactions')" id="btn-nav-transactions" class="text-gray-400 hover:text-white w-full aspect-square rounded-xl flex justify-center items-center transition-colors relative transition-all duration-200">
                <i class="fa-solid fa-box text-lg"></i>
                <span class="absolute top-1 right-1 bg-accent text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center border-2 border-darknav">3</span>
            </button>
            <button onclick="switchView('categories')" id="btn-nav-categories" class="text-gray-400 hover:text-white w-full aspect-square flex justify-center items-center transition-colors rounded-xl transition-all duration-200"><i class="fa-solid fa-microchip text-lg"></i></button>
            <button onclick="switchView('finance')" id="btn-nav-finance" class="text-gray-400 hover:text-white w-full aspect-square flex justify-center items-center transition-colors rounded-xl transition-all duration-200"><i class="fa-solid fa-money-bill-wave text-lg"></i></button>
            <button onclick="openShippingModal()" class="text-gray-400 hover:text-white w-full aspect-square flex justify-center items-center transition-colors rounded-xl transition-all duration-200"><i class="fa-solid fa-truck-fast text-lg"></i></button>
            <button onclick="switchView('partners')" id="btn-nav-partners" class="text-gray-400 hover:text-white w-full aspect-square flex justify-center items-center transition-colors rounded-xl transition-all duration-200"><i class="fa-solid fa-users text-lg"></i></button>
        </nav>
    </div>

    <div class="flex flex-col gap-4 w-full px-3">
        <button class="bg-darkcard text-gray-400 hover:text-white w-full aspect-square rounded-xl flex justify-center items-center border border-gray-700 transition" onclick="logout()" title="Logout"><i class="fa-solid fa-arrow-right-from-bracket"></i></button>
    </div>
</aside>
