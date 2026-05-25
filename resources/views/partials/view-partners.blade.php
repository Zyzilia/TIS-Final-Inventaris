<!-- VIEW 5: Partners -->
<div id="view-partners" class="h-full flex flex-col p-8 overflow-y-auto hidden">
    <header class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Partners Directory</h1>
            <p class="text-gray-500 font-medium mt-1">Official supplier list and retail client network</p>
        </div>
        <div class="flex items-center gap-3">
            <button id="addPartnerBtn" onclick="openPartnerModal()" class="bg-primary-600 text-white px-5 py-2.5 rounded-xl font-bold text-sm hover:bg-primary-700 transition shadow-lg shadow-primary-500/20 flex items-center gap-2">
                <i class="fa-solid fa-plus text-xs"></i> Add Supplier
            </button>
            @include('partials.header-actions')
        </div>
    </header>

    <div class="card-standard flex-1 flex flex-col overflow-hidden">
        <div class="px-8 pt-6 border-b border-gray-100 flex items-center gap-8 bg-gray-50/30">
            <button class="pb-4 border-b-2 border-primary-600 font-extrabold text-primary-600 text-sm transition-all" id="partnerTabSuppliers" onclick="switchPartnerTab('suppliers')">
                <i class="fa-solid fa-truck-field mr-2"></i> Official Suppliers
            </button>
            <button class="pb-4 border-b-2 border-transparent font-bold text-gray-500 hover:text-gray-800 text-sm transition-all" id="partnerTabCustomers" onclick="switchPartnerTab('customers')">
                <i class="fa-solid fa-users-viewfinder mr-2"></i> Retail Customers
            </button>
        </div>
        
        <div id="partnerContainer" class="flex-1 overflow-x-auto overflow-y-auto">
            <!-- Dynamic partners directory -->
        </div>
    </div>
</div>
