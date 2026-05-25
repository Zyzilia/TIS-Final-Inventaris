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
                <div>
                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Stock In</p>
                    <h3 class="text-2xl font-bold text-gray-900" id="stat-stock-in">0</h3>
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
            <div class="card-standard p-6 flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Active Partners</p>
                    <h3 class="text-2xl font-bold text-gray-900" id="stat-partners">0</h3>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            <!-- Sales Analytics (Span 2) -->
            <div class="xl:col-span-2 space-y-8">
                <div class="card-standard p-8">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">Inventory Flow</h2>
                            <p class="text-sm text-gray-500 font-medium">Monthly inbound vs outbound trends</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="flex items-center gap-1.5 text-xs font-bold text-gray-600">
                                <span class="w-2.5 h-2.5 rounded-full bg-primary-500"></span> Inbound
                            </span>
                            <span class="flex items-center gap-1.5 text-xs font-bold text-gray-600">
                                <span class="w-2.5 h-2.5 rounded-full bg-primary-200"></span> Outbound
                            </span>
                        </div>
                    </div>
                    <div class="h-80 w-full relative">
                        <canvas id="barChart"></canvas>
                    </div>
                </div>

                <!-- Category Summary Table -->
                <div class="card-standard overflow-hidden">
                    <div class="p-8 border-b border-gray-100 flex items-center justify-between">
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">Category Insights</h2>
                            <p class="text-sm text-gray-500 font-medium">Distribution and stock health across categories</p>
                        </div>
                        <button onclick="switchView('categories')" class="text-primary-600 hover:text-primary-700 font-bold text-sm flex items-center gap-2">
                            View Warehouse <i class="fa-solid fa-arrow-right text-xs"></i>
                        </button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-gray-50/50">
                                <tr class="text-gray-400 text-[11px] font-bold uppercase tracking-wider border-b border-gray-100">
                                    <th class="py-4 px-8">Category Name</th>
                                    <th class="py-4 px-4">Active Models</th>
                                    <th class="py-4 px-4">Warehouse Stock</th>
                                    <th class="py-4 px-4 text-right">Est. Sales Value</th>
                                    <th class="py-4 px-8 text-center">Market Share</th>
                                </tr>
                            </thead>
                            <tbody id="categorySalesTableBody" class="text-sm divide-y divide-gray-50">
                                <tr>
                                    <td colspan="5" class="py-12 text-center text-gray-400">
                                        <i class="fa-solid fa-circle-notch fa-spin text-primary-500 text-xl mb-2"></i>
                                        <p class="font-medium">Aggregating warehouse data...</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Side Column -->
            <div class="space-y-8">
                <!-- Market Regions Doughnut -->
                <div class="card-standard p-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-2">Regional Sales</h2>
                    <p class="text-sm text-gray-500 font-medium mb-8">Sales distribution by location</p>
                    
                    <div class="relative flex flex-col items-center">
                        <div class="w-48 h-48 mb-8">
                            <canvas id="doughnutChart"></canvas>
                        </div>
                        <div class="w-full space-y-3">
                            <div class="flex items-center justify-between p-3 rounded-xl bg-primary-50/50 border border-primary-100/50">
                                <div class="flex items-center gap-3">
                                    <span class="w-3 h-3 rounded-full bg-primary-600"></span>
                                    <span class="text-sm font-bold text-gray-700">Jakarta</span>
                                </div>
                                <span id="doughnut-pct-jkt" class="text-primary-600 font-extrabold">45%</span>
                            </div>
                            <div class="flex items-center justify-between p-3 rounded-xl bg-gray-50 border border-gray-100">
                                <div class="flex items-center gap-3">
                                    <span class="w-3 h-3 rounded-full bg-primary-300"></span>
                                    <span class="text-sm font-bold text-gray-700">Surabaya</span>
                                </div>
                                <span id="doughnut-pct-sby" class="text-gray-500 font-extrabold">20%</span>
                            </div>
                            <div class="flex items-center justify-between p-3 rounded-xl bg-gray-50 border border-gray-100">
                                <div class="flex items-center gap-3">
                                    <span class="w-3 h-3 rounded-full bg-primary-900"></span>
                                    <span class="text-sm font-bold text-gray-700">Bandung</span>
                                </div>
                                <span id="doughnut-pct-bdg" class="text-gray-900 font-extrabold">18%</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Last Activity -->
                <div class="card-standard flex flex-col h-[520px]">
                    <div class="p-8 border-b border-gray-100 flex items-center justify-between">
                        <h2 class="text-xl font-bold text-gray-900">Activity Log</h2>
                        <span class="w-8 h-8 rounded-lg bg-gray-100 text-gray-400 flex items-center justify-center text-xs">
                            <i class="fa-solid fa-bolt"></i>
                        </span>
                    </div>
                    <div class="flex-1 overflow-y-auto p-6 space-y-4 custom-scrollbar" id="activityList">
                        <!-- JS Populated -->
                    </div>
                    <div class="p-4 bg-gray-50 text-center">
                        <button class="text-primary-600 hover:text-primary-700 font-bold text-xs uppercase tracking-widest">
                            View All Logs
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Panel (Optional/Secondary side) -->
    <!-- We've integrated most stats into the main flow, but we can keep a thin side panel for live ticker/conversions if needed -->
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 10px; }
</style>
