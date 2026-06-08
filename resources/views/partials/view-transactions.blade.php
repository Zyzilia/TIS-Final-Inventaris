<!-- VIEW 2: Transactions -->
<div id="view-transactions" class="h-full flex flex-col p-8 overflow-y-auto hidden">
    <header class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Stock Transactions</h1>
            <p class="text-gray-500 font-medium mt-1">History of stock movements and shipments</p>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="openTxModal()" id="btnAddTransaction" class="bg-primary-600 text-white px-5 py-2.5 rounded-xl font-bold text-sm hover:bg-primary-700 transition shadow-lg shadow-primary-500/20 flex items-center gap-2">
                <i class="fa-solid fa-plus text-xs"></i> Add Transaction
            </button>
            @include('partials.header-actions')
        </div>
    </header>

    <div class="card-standard flex-1 flex flex-col overflow-hidden">
        <!-- Filters Header -->
        <div class="p-6 border-b border-gray-100 bg-gray-50/30 flex flex-wrap gap-4">
            <div class="flex-1 min-w-[300px] relative group">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-primary-600 transition-colors"></i>
                <input type="text" id="txSearch" oninput="filterTransactions()" placeholder="Search by item name or SKU..." 
                    class="w-full pl-11 pr-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all">
            </div>
            <div class="flex items-center gap-3">
                <select id="txFilter" onchange="filterTransactions()" 
                    class="px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-600 focus:outline-none focus:border-primary-500 transition-all cursor-pointer">
                    <option value="">All Types</option>
                    <option value="IN">Inbound (Restock)</option>
                    <option value="OUT">Outbound (Sale)</option>
                </select>
                <select id="txStatusFilter" onchange="filterTransactions()" 
                    class="px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-600 focus:outline-none focus:border-primary-500 transition-all cursor-pointer">
                    <option value="">All Statuses</option>
                    <option value="completed">Completed</option>
                    <option value="pending">Pending</option>
                </select>
                <input type="date" id="txDateFilter" onchange="filterTransactions()" 
                    class="px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-600 focus:outline-none focus:border-primary-500 transition-all cursor-pointer" title="Filter by date">
            </div>
        </div>

        <!-- Table Content -->
        <div class="flex-1 overflow-x-auto overflow-y-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50/50 sticky top-0 z-10 backdrop-blur-sm">
                    <tr class="text-gray-400 text-[11px] font-bold uppercase tracking-wider border-b border-gray-100">
                        <th class="py-4 px-6">Date</th>
                        <th class="py-4 px-4">Item Name</th>
                        <th class="py-4 px-4">SKU</th>
                        <th class="py-4 px-4">Type</th>
                        <th class="py-4 px-4 text-center">Quantity</th>
                        <th class="py-4 px-4">Supplier / Client</th>
                        <th class="py-4 px-6 text-right">Status</th>
                    </tr>
                </thead>
                <tbody id="txTableBody" class="text-sm divide-y divide-gray-50">
                    <!-- JS Loaded -->
                </tbody>
            </table>
        </div>
        
        <!-- Pagination Placeholder -->
        <div class="p-4 border-t border-gray-100 bg-gray-50/30 flex items-center justify-between">
            <p class="text-xs text-gray-500 font-medium">Showing <span class="text-gray-900 font-bold">1-10</span> of <span class="text-gray-900 font-bold">45</span> transactions</p>
            <div class="flex items-center gap-2">
                <button class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-gray-400 hover:bg-white transition-colors"><i class="fa-solid fa-chevron-left text-[10px]"></i></button>
                <button class="w-8 h-8 rounded-lg bg-primary-600 text-white flex items-center justify-center text-xs font-bold shadow-md shadow-primary-500/20">1</button>
                <button class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-gray-600 hover:bg-white transition-colors text-xs font-bold">2</button>
                <button class="w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-gray-400 hover:bg-white transition-colors"><i class="fa-solid fa-chevron-right text-[10px]"></i></button>
            </div>
        </div>
    </div>
</div>
