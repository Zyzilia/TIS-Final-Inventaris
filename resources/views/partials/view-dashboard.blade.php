<!-- VIEW 1: Dashboard / Overview (Active by default) -->
<div id="view-dashboard" class="h-full flex flex-col xl:flex-row overflow-hidden">
    <!-- Center Column -->
    <div class="flex-1 flex flex-col p-8 overflow-y-auto min-w-0">
        
        <!-- Header -->
        <header class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">System Overview</h1>
                <p class="text-gray-500 font-medium mt-1">Monitor your inventory and business performance</p>
            </div>
            @include('partials.header-actions')
        </header>

        <!-- Stats Grid (Top row for quick metrics) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="card-standard p-6 flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-primary-50 text-primary-600 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-box-open"></i>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Total Items</p>
                    <h3 class="text-2xl font-bold text-gray-900" id="stat-total-items">0</h3>
                </div>
            </div>
            <div class="card-standard p-6 flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-green-50 text-green-600 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-truck-ramp-box"></i>
                </div>

                <!-- Last Activity -->
                <div class="bg-white p-6 rounded-[2rem] shadow-sm flex flex-col">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="font-semibold text-gray-800 text-lg">Last activity</h2>
                        <a href="#" class="text-gray-400 hover:text-gray-700"><i class="fa-solid fa-arrow-up-right-from-square"></i></a>
                    </div>
                    
                    <div class="flex flex-col gap-3 flex-1 overflow-y-auto max-h-[340px] pr-2 custom-scrollbar" id="activityList">
                        <!-- JS Populated -->
                    </div>
                </div>
            </div>
            <div class="card-standard p-6 flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-cart-flatbed"></i>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Stock Out</p>
                    <h3 class="text-2xl font-bold text-gray-900" id="stat-stock-out">0</h3>
                </div>
            </div>

        </div>
    </div>

    <!-- Right Sidebar / Statistics Column -->
    <div class="w-full xl:w-80 bg-bgmain p-6 xl:pl-0 flex flex-col gap-6 xl:overflow-y-auto shrink-0">
        
        <!-- Total Customers -->
        <div onclick="switchView('partners')" class="bg-darkcard text-white p-6 rounded-[2rem] shadow-lg relative overflow-hidden h-48 flex flex-col justify-between cursor-pointer hover:scale-[1.02] hover:-translate-y-1 transition-all duration-300 group">
            <div class="flex justify-between items-start">
                <h3 class="text-gray-400 font-medium">Total Customers</h3>
                <i class="fa-solid fa-grip-vertical text-gray-600 text-sm"></i>
            </div>
            <div>
                <div class="flex items-baseline gap-2">
                    <h2 id="stat-customers-total" class="text-4xl font-bold">1,226</h2>
                    <span id="stat-customers-growth" class="text-green-500 text-xs font-semibold flex items-center gap-1"><i class="fa-solid fa-arrow-trend-up"></i> 79%</span>
                </div>
                <p id="stat-customers-last" class="text-xs text-gray-500 mt-1">683 users last month</p>
            </div>
            <div class="flex gap-2 items-center mt-2">
                <div class="flex-1 h-8 rounded-lg overflow-hidden flex bg-white/10">
                    <div id="stat-customers-retail-bar" class="h-full bg-white/20" style="width: 40%; background-image: repeating-linear-gradient(45deg, transparent, transparent 5px, rgba(255,255,255,0.1) 5px, rgba(255,255,255,0.1) 10px);"></div>
                    <div id="stat-customers-wholesale-bar" class="h-full bg-white text-darkcard flex items-center justify-end px-3 text-xs font-bold" style="width: 60%;">60%</div>
                </div>
            </div>
        </div>

        <!-- Total Incoming Stock -->
        <div onclick="switchView('transactions')" class="p-6 rounded-[2rem] shadow-lg text-white flex flex-col justify-between h-56 relative overflow-hidden cursor-pointer hover:scale-[1.02] hover:-translate-y-1 transition-all duration-300 group" style="background: linear-gradient(135deg, #7C8EE2, #D4B9EE);">
            <div class="flex justify-between items-start relative z-10">
                <h3 class="text-white/80 font-medium">Barang Masuk</h3>
                <i class="fa-solid fa-arrow-right-to-bracket text-white/50 text-sm"></i>
            </div>
            <div class="relative z-10">
                <div class="flex items-baseline gap-2">
                    <h2 id="stat-incoming-total" class="text-4xl font-bold">0 Unit</h2>
                    <span id="stat-incoming-growth" class="text-white/80 text-xs font-semibold flex items-center gap-1"><i class="fa-solid fa-arrow-trend-up"></i> 0%</span>
                </div>
                <p id="stat-incoming-last" class="text-xs text-white/70 mt-1">0 units last month</p>
            </div>
            <div class="h-16 w-full relative z-10 mt-2 flex items-end justify-between px-1 gap-1 opacity-90">
                <div class="w-full bg-white rounded-t h-[40%]"></div>
                <div class="w-full bg-white rounded-t h-[60%]"></div>
                <div class="w-full bg-white rounded-t h-[30%] relative group">
                    <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-darkcard text-white text-[10px] px-2 py-1 rounded hidden group-hover:block whitespace-nowrap">$1,210.6</div>
                    <div class="w-full h-full bg-darknav rounded-t"></div>
                </div>
            </div>

        <!-- Total Outgoing Stock -->
        <div onclick="switchView('transactions')" class="bg-white p-6 rounded-[2rem] shadow-sm flex flex-col justify-between h-56 cursor-pointer hover:scale-[1.02] hover:-translate-y-1 transition-all duration-300 group" style="background: linear-gradient(180deg, #FFFFFF, #FFF5EC);">
            <div class="flex justify-between items-start">
                <h3 class="text-gray-500 font-medium">Barang Keluar</h3>
                <i class="fa-solid fa-arrow-right-from-bracket text-gray-300 text-sm"></i>
            </div>
            <div>
                <div class="flex items-baseline gap-2">
                    <h2 id="stat-outgoing-total" class="text-4xl font-bold text-gray-900">0 Unit</h2>
                    <span id="stat-outgoing-growth" class="text-green-500 text-xs font-semibold flex items-center gap-1"><i class="fa-solid fa-arrow-trend-up"></i> 0%</span>
                </div>
                <p id="stat-outgoing-last" class="text-xs text-gray-400 mt-1">0 units last month</p>
            </div>
            <div class="h-20 w-full relative mt-2">
                <canvas id="lineChart"></canvas>
            </div>
        </div>

        <button id="btn-export-stats" class="w-full bg-darknav text-white py-4 rounded-2xl font-medium shadow-lg hover:bg-gray-800 transition flex items-center justify-center gap-2 mt-auto">
            <i class="fa-solid fa-arrow-up-from-bracket rotate-180"></i> Export statistics
        </button>
    </div>

    <!-- Stats Panel (Optional/Secondary side) -->
    <!-- We've integrated most stats into the main flow, but we can keep a thin side panel for live ticker/conversions if needed -->
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 10px; }
</style>
