<!-- VIEW 2: Transactions -->
<div id="view-transactions" class="flex-1 flex flex-col p-8 overflow-y-auto hidden">
    <header class="flex justify-between items-end mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Stock Transactions</h1>
            <p class="text-gray-500 text-sm mt-1">History of stock movements and shipments</p>
        </div>
        <div class="flex items-center gap-4">
            <button onclick="openTxModal()" class="bg-accent text-white px-5 py-2.5 rounded-xl font-semibold text-sm hover:bg-violet-600 transition shadow-sm flex items-center gap-2">
                <i class="fa-solid fa-plus text-xs"></i> Add Transaction
            </button>
            @include('partials.header-actions')
        </div>
    </header>
    <div class="bg-white p-8 rounded-[2rem] shadow-sm flex-1 flex flex-col">
        <div class="flex gap-4 mb-6">
            <div class="flex-1 relative">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-3.5 text-gray-400"></i>
                <input type="text" id="txSearch" oninput="filterTransactions()" placeholder="Search by item name or SKU..." class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-accent">
            </div>
            <select id="txFilter" onchange="filterTransactions()" class="px-4 py-3 border border-gray-200 rounded-xl text-sm bg-white focus:outline-none focus:border-accent">
                <option value="">All Transactions</option>
                <option value="IN">Inbound (Restock)</option>
                <option value="OUT">Outbound (Sale)</option>
            </select>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="text-gray-400 text-xs border-b border-gray-100 pb-4">
                        <th class="pb-4 font-medium px-2">Date</th>
                        <th class="pb-4 font-medium">Item Name</th>
                        <th class="pb-4 font-medium">SKU</th>
                        <th class="pb-4 font-medium">Type</th>
                        <th class="pb-4 font-medium text-center">Quantity</th>
                        <th class="pb-4 font-medium">Supplier / Client</th>
                        <th class="pb-4 font-medium">Status</th>
                    </tr>
                </thead>
                <tbody id="txTableBody">
                    <!-- JS Loaded -->
                </tbody>
            </table>
        </div>
    </div>
</div>
