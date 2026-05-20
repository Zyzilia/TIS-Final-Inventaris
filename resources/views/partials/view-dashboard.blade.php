<!-- VIEW 1: Dashboard / Overview (Active by default) -->
<div id="view-dashboard" class="flex-1 flex flex-col xl:flex-row overflow-y-auto xl:overflow-hidden">
    <!-- Center Column -->
    <div class="flex-1 flex flex-col p-8 xl:overflow-y-auto min-w-0">
        
        <!-- Header -->
        <header class="flex justify-between items-end mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Overview</h1>
                <p class="text-gray-500 text-sm mt-1">Detailed information about your store</p>
            </div>
            @include('partials.header-actions')
        </header>

        <div class="flex flex-col gap-6">
            
            <!-- Sales Analytics Bar Chart -->
            <div class="bg-white p-6 rounded-[2rem] shadow-sm relative">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h2 class="font-semibold text-gray-800 text-lg">Sales Analytics</h2>
                    </div>
                </div>
                <div class="h-64 w-full relative">
                    <canvas id="barChart"></canvas>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Top Sales by Country -->
                <div class="bg-white p-6 rounded-[2rem] shadow-sm flex flex-col">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="font-semibold text-gray-800 text-lg">Top sales by region</h2>
                        <i class="fa-solid fa-grip-vertical text-gray-300"></i>
                    </div>
                    <div class="flex-1 flex flex-col justify-center items-center py-4">
                        <div class="flex items-center justify-center h-48 relative">
                            <div class="w-48 h-48">
                                <canvas id="doughnutChart"></canvas>
                            </div>
                        </div>
                        <div class="flex justify-center gap-8 text-xs text-gray-500 mt-6 font-semibold">
                            <span class="flex flex-col items-center gap-1">
                                <span class="flex items-center gap-1.5">
                                    <span class="w-2.5 h-2.5 rounded-full bg-[#9A82EA]"></span>
                                    <span class="text-gray-700">Jakarta</span>
                                </span>
                                <span id="doughnut-pct-jkt" class="text-accent text-sm font-bold">45%</span>
                            </span>
                            <span class="flex flex-col items-center gap-1">
                                <span class="flex items-center gap-1.5">
                                    <span class="w-2.5 h-2.5 rounded-full bg-[#D3C6F9]"></span>
                                    <span class="text-gray-700">Surabaya</span>
                                </span>
                                <span id="doughnut-pct-sby" class="text-gray-500 text-sm font-bold">20%</span>
                            </span>
                            <span class="flex flex-col items-center gap-1">
                                <span class="flex items-center gap-1.5">
                                    <span class="w-2.5 h-2.5 rounded-full bg-[#1B1C22]"></span>
                                    <span class="text-gray-700">Bandung</span>
                                </span>
                                <span id="doughnut-pct-bdg" class="text-gray-900 text-sm font-bold">18%</span>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Last Activity -->
                <div class="bg-white p-6 rounded-[2rem] shadow-sm flex flex-col">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="font-semibold text-gray-800 text-lg">Last activity</h2>
                        <a href="#" class="text-gray-400 hover:text-gray-700"><i class="fa-solid fa-arrow-up-right-from-square"></i></a>
                    </div>
                    
                    <div class="flex flex-col gap-3 flex-1 overflow-y-auto" id="activityList">
                        <!-- JS Populated -->
                    </div>
                </div>
            </div>

            <!-- Product Sales Table (Rebranded to Category Sales Summary) -->
            <div class="bg-white p-6 rounded-[2rem] shadow-sm">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="font-semibold text-gray-800 text-lg">Ringkasan Penjualan Kategori</h2>
                    <a href="#" class="text-gray-400 hover:text-gray-700 flex items-center"><i class="fa-solid fa-arrow-up-right-from-square"></i></a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="text-gray-400 text-xs border-b border-gray-100">
                                <th class="pb-4 font-medium px-2">Kategori Barang</th>
                                <th class="pb-4 font-medium">Model Aktif</th>
                                <th class="pb-4 font-medium">Total Stok Gudang</th>
                                <th class="pb-4 font-medium text-right">Estimasi Terjual</th>
                                <th class="pb-4 font-medium text-center px-6">Kontribusi Penjualan</th>
                                <th class="pb-4 font-medium text-center px-2">Detail</th>
                            </tr>
                        </thead>
                        <tbody id="categorySalesTableBody" class="text-sm">
                            <tr>
                                <td colspan="6" class="py-8 text-center text-gray-500">
                                    <i class="fa-solid fa-circle-notch fa-spin"></i> Memuat data kategori...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <!-- Right Sidebar / Statistics Column -->
    <div class="w-full xl:w-80 bg-bgmain p-6 xl:pl-0 flex flex-col gap-6 xl:overflow-y-auto shrink-0">
        
        <!-- Total Customers -->
        <div class="bg-darkcard text-white p-6 rounded-[2rem] shadow-lg relative overflow-hidden h-48 flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <h3 class="text-gray-400 font-medium">Total Customers</h3>
                <i class="fa-solid fa-grip-vertical text-gray-600 text-sm"></i>
            </div>
            <div>
                <div class="flex items-baseline gap-2">
                    <h2 class="text-4xl font-bold">1,226</h2>
                    <span class="text-green-500 text-xs font-semibold flex items-center gap-1"><i class="fa-solid fa-arrow-trend-up"></i> 79%</span>
                </div>
                <p class="text-xs text-gray-500 mt-1">683 users last month</p>
            </div>
            <div class="flex gap-2 items-center mt-2">
                <div class="flex-1 h-8 rounded-lg overflow-hidden flex bg-white/10">
                    <div class="h-full bg-white/20" style="width: 40%; background-image: repeating-linear-gradient(45deg, transparent, transparent 5px, rgba(255,255,255,0.1) 5px, rgba(255,255,255,0.1) 10px);"></div>
                    <div class="h-full bg-white text-darkcard flex items-center justify-end px-3 text-xs font-bold" style="width: 60%;">60%</div>
                </div>
            </div>
            <div class="flex justify-between text-[10px] text-gray-400 uppercase font-semibold tracking-wider">
                <span><span class="inline-block w-1.5 h-1.5 border border-gray-400 rounded-full mr-1"></span>Retail</span>
                <span><span class="inline-block w-1.5 h-1.5 bg-white rounded-full mr-1"></span>Wholesale</span>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="p-6 rounded-[2rem] shadow-lg text-white flex flex-col justify-between h-56 relative overflow-hidden" style="background: linear-gradient(135deg, #7C8EE2, #D4B9EE);">
            <div class="flex justify-between items-start relative z-10">
                <h3 class="text-white/80 font-medium">Total Revenue</h3>
                <i class="fa-solid fa-grip-vertical text-white/50 text-sm"></i>
            </div>
            <div class="relative z-10">
                <div class="flex items-baseline gap-2">
                    <h2 class="text-4xl font-bold">$12,000</h2>
                    <span class="text-white/80 text-xs font-semibold flex items-center gap-1"><i class="fa-solid fa-arrow-trend-down"></i> 10%</span>
                </div>
                <p class="text-xs text-white/70 mt-1">$15,650 last month</p>
            </div>
            <div class="h-16 w-full relative z-10 mt-2 flex items-end justify-between px-1 gap-1 opacity-90">
                <div class="w-full bg-white rounded-t h-[40%]"></div>
                <div class="w-full bg-white rounded-t h-[60%]"></div>
                <div class="w-full bg-white rounded-t h-[30%] relative group">
                    <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-darkcard text-white text-[10px] px-2 py-1 rounded hidden group-hover:block whitespace-nowrap">$1,210.6</div>
                    <div class="w-full h-full bg-darknav rounded-t"></div>
                </div>
                <div class="w-full bg-white/50 rounded-t h-[40%]"></div>
                <div class="w-full bg-white/50 rounded-t h-[30%]"></div>
                <div class="w-full bg-white/50 rounded-t h-[50%]"></div>
                <div class="w-full bg-white/50 rounded-t h-[20%]"></div>
            </div>
            <div class="flex justify-between text-[10px] text-white/70 mt-1 relative z-10 font-medium">
                <span>1</span><span>4</span><span>8</span><span>12</span><span>16</span><span>20</span><span>24</span><span>30</span>
            </div>
            <div class="absolute bottom-[3.5rem] left-6 right-6 border-t border-dashed border-white/40 z-10"></div>
        </div>

        <!-- Total Orders -->
        <div class="bg-white p-6 rounded-[2rem] shadow-sm flex flex-col justify-between h-56" style="background: linear-gradient(180deg, #FFFFFF, #FFF5EC);">
            <div class="flex justify-between items-start">
                <h3 class="text-gray-500 font-medium">Total Orders</h3>
                <i class="fa-solid fa-grip-vertical text-gray-300 text-sm"></i>
            </div>
            <div>
                <div class="flex items-baseline gap-2">
                    <h2 class="text-4xl font-bold text-gray-900">$15,210</h2>
                    <span class="text-green-500 text-xs font-semibold flex items-center gap-1"><i class="fa-solid fa-arrow-trend-up"></i> 51%</span>
                </div>
                <p class="text-xs text-gray-400 mt-1">$12,000 last month</p>
            </div>
            <div class="h-20 w-full relative mt-2">
                <canvas id="lineChart"></canvas>
            </div>
        </div>

        <button class="w-full bg-darknav text-white py-4 rounded-2xl font-medium shadow-lg hover:bg-gray-800 transition flex items-center justify-center gap-2 mt-auto">
            <i class="fa-solid fa-arrow-up-from-bracket rotate-180"></i> Export statistics
        </button>
    </div>
</div>
