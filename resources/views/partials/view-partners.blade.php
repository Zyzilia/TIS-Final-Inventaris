<!-- VIEW 5: Partners -->
<div id="view-partners" class="flex-1 flex flex-col p-8 overflow-y-auto hidden">
    <header class="flex justify-between items-end mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Partners Directory</h1>
            <p class="text-gray-500 text-sm mt-1">Official supplier list and retail client network</p>
        </div>
        <div class="flex items-center gap-4">
            <button id="addPartnerBtn" onclick="openPartnerModal()" class="bg-accent text-white px-5 py-2.5 rounded-xl font-semibold text-sm hover:bg-violet-600 transition shadow-sm flex items-center gap-2">
                <i class="fa-solid fa-plus text-xs"></i> Add Supplier
            </button>
            @include('partials.header-actions')
        </div>
    </header>
    <div class="bg-white p-8 rounded-[2rem] shadow-sm flex-1 flex flex-col">
        <div class="flex gap-6 border-b border-gray-200 mb-6">
            <button class="pb-3 border-b-2 border-accent font-semibold text-accent" id="partnerTabSuppliers" onclick="switchPartnerTab('suppliers')">Suppliers</button>
            <button class="pb-3 border-b-2 border-transparent font-medium text-gray-500 hover:text-gray-800" id="partnerTabCustomers" onclick="switchPartnerTab('customers')">Customers</button>
        </div>
        <div id="partnerContainer" class="overflow-x-auto flex-1">
            <!-- Dynamic partners directory -->
        </div>
    </div>
</div>
