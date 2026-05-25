<!-- VIEW 3: Categories / Gudang -->
<div id="view-categories" class="h-full flex flex-col p-8 overflow-y-auto hidden">
    <!-- GRID VIEW (Warehouse Main) -->
    <div id="warehouseGridContainer" class="flex flex-col flex-1">
        <header class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Warehouse & Inventory</h1>
                <p class="text-gray-500 font-medium mt-1">Select a category or shelf to manage component stock</p>
            </div>
            @include('partials.header-actions')
        </header>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="categoryGrid">
            <!-- Dynamic Category Cards -->
        </div>
    </div>

    <!-- SHELF VIEW (Cabinet Contents) -->
    <div id="warehouseShelfContainer" class="hidden flex-col flex-1">
        <header class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <button onclick="backToWarehouseGrid()" class="text-xs font-bold text-primary-600 hover:text-primary-700 transition flex items-center gap-2 mb-2 group">
                    <i class="fa-solid fa-arrow-left group-hover:-translate-x-1 transition-transform"></i> 
                    BACK TO MAIN WAREHOUSE
                </button>
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight" id="shelfTitle">Loading Shelf...</h1>
                <p class="text-gray-500 font-medium mt-1" id="shelfSubtitle">Manage items and stock levels in this category</p>
            </div>
            <div class="flex gap-3">
                <button id="addShelfItemBtn" class="bg-primary-600 text-white text-sm font-bold px-5 py-2.5 rounded-xl hover:bg-primary-700 transition shadow-lg shadow-primary-500/20 flex items-center gap-2">
                    <i class="fa-solid fa-plus text-xs"></i> Add New Item
                </button>
                @include('partials.header-actions')
            </div>
        </header>
        
        <div class="card-standard flex-1 flex flex-col overflow-hidden">
            <div class="p-6 border-b border-gray-100 bg-gray-50/30 flex items-center justify-between gap-4">
                <h2 class="font-bold text-gray-900">Current Shelf Inventory</h2>
                <div class="relative group w-72">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-primary-600 transition-colors"></i>
                    <input type="text" id="shelfSearchInput" oninput="filterShelfItems()" placeholder="Search by name or SKU..." 
                        class="w-full pl-10 pr-4 py-2 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all">
                </div>
            </div>
            <div class="flex-1 overflow-x-auto overflow-y-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50/50 sticky top-0 z-10 backdrop-blur-sm">
                        <tr class="text-gray-400 text-[11px] font-bold uppercase tracking-wider border-b border-gray-100">
                            <th class="py-4 px-8">Item Name</th>
                            <th class="py-4 px-4">SKU</th>
                            <th class="py-4 px-4">Current Stock</th>
                            <th class="py-4 px-4">Unit Price (Rp)</th>
                            <th class="py-4 px-4">Margin</th>
                            <th class="py-4 px-4">Selling Price</th>
                            <th class="py-4 px-8 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="shelfTableBody" class="text-sm divide-y divide-gray-50">
                        <!-- JS populated -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
