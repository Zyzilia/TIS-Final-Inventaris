<!-- Item Modal -->
<div id="itemModal" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-[60] hidden items-center justify-center p-4">
    <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-md overflow-hidden transform transition-all">
        <div class="bg-primary-900 px-8 py-6 flex justify-between items-center text-white">
            <div>
                <h3 id="modalTitle" class="font-extrabold text-xl tracking-tight">Add New Item</h3>
                <p class="text-primary-400 text-xs font-medium uppercase tracking-widest mt-0.5">Inventory Management</p>
            </div>
            <button onclick="closeItemModal()" class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center hover:bg-white/20 transition-colors"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>
        <form id="itemForm" class="p-8 flex flex-col gap-5">
            <input type="hidden" id="itemId">
            
            <div class="space-y-1.5">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider">Item Name</label>
                <input type="text" id="itemName" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all font-semibold text-gray-900">
            </div>

            <!-- Brand / Merk Section -->
            <div class="grid grid-cols-2 gap-5" id="brandFieldWrapper">
                <div id="brandSelectContainer" class="col-span-2 space-y-1.5">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider">Brand / Series</label>
                    <select id="itemBrandSelect" onchange="handleBrandSelectChange()" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-primary-500 transition-all font-semibold text-gray-900 cursor-pointer">
                        <!-- Dynamic options -->
                    </select>
                </div>
                <div id="customBrandContainer" class="hidden space-y-1.5">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider">Custom Brand</label>
                    <input type="text" id="itemBrandCustom" placeholder="Enter brand name..." class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-primary-500 transition-all font-semibold text-gray-900">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-5">
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider">Category</label>
                    <select id="itemCategory" onchange="updateBrandOptions()" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-primary-500 transition-all font-semibold text-gray-900 cursor-pointer">
                        <option value="1">GPU</option>
                        <option value="2">CPU</option>
                        <option value="3">RAM</option>
                        <option value="4">Storage</option>
                        <option value="5">Motherboard</option>
                        <option value="6">Power Supply (PSU)</option>
                        <option value="7">PC Case</option>
                        <option value="8">Cooling (Fan/AIO)</option>
                    </select>
                </div>
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider">SKU</label>
                    <input type="text" id="itemSku" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-primary-500 transition-all font-mono font-bold text-gray-900 uppercase">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-5">
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider">Initial Stock</label>
                    <input type="number" id="itemStock" required min="0" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-primary-500 transition-all font-bold text-gray-900">
                </div>
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider">Price (USD)</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold">$</span>
                        <input type="number" step="0.01" id="itemPriceUsd" required min="0" class="w-full pl-8 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-primary-500 transition-all font-bold text-gray-900">
                    </div>
                </div>
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider">Margin (%)</label>
                    <input type="number" step="0.1" id="itemMargin" required min="0" value="10" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-primary-500 transition-all font-bold text-gray-900">
                </div>
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider">Weight (g)</label>
                    <input type="number" id="itemWeight" required min="1" value="1000" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-primary-500 transition-all font-bold text-gray-900">
                </div>
            </div>

            <div class="mt-4 flex gap-4">
                <button type="button" onclick="closeItemModal()" class="flex-1 py-3.5 text-sm font-bold text-gray-500 bg-gray-100 hover:bg-gray-200 rounded-2xl transition-all">Cancel</button>
                <button type="submit" id="saveItemBtn" class="flex-2 py-3.5 px-8 text-sm font-black text-white bg-primary-600 hover:bg-primary-700 rounded-2xl transition-all shadow-lg shadow-primary-500/30">Save Component</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-[70] hidden items-center justify-center p-4">
    <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-sm overflow-hidden text-center p-10">
        <div class="w-24 h-24 bg-red-50 text-red-500 rounded-full flex items-center justify-center text-5xl mx-auto mb-6 shadow-sm border-8 border-white ring-8 ring-red-50/50">
            <i class="fa-solid fa-trash-can"></i>
        </div>
        <h3 class="font-black text-gray-900 text-2xl mb-2 tracking-tight">Confirm Deletion</h3>
        <p class="text-gray-500 font-medium text-sm mb-10">Are you sure you want to remove this component from the inventory? This action is permanent.</p>
        <div class="flex flex-col gap-3">
            <button id="confirmDeleteBtn" class="py-4 font-black text-white bg-red-500 hover:bg-red-600 rounded-2xl transition-all shadow-xl shadow-red-500/30">YES, DELETE NOW</button>
            <button onclick="closeDeleteModal()" class="py-4 font-bold text-gray-500 bg-gray-100 hover:bg-gray-200 rounded-2xl transition-all">Cancel</button>
        </div>
    </div>
</div>

<!-- Transaction Modal -->
<div id="txModal" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-[60] hidden items-center justify-center p-4">
    <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-md overflow-hidden">
        <div class="bg-primary-900 px-8 py-6 flex justify-between items-center text-white">
            <div>
                <h3 class="font-extrabold text-xl tracking-tight">Stock Movement</h3>
                <p class="text-primary-400 text-xs font-medium uppercase tracking-widest mt-0.5">Inventory Transaction</p>
            </div>
            <button onclick="closeTxModal()" class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center hover:bg-white/20 transition-colors"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>
        <form id="txForm" onsubmit="submitTransaction(event)" class="p-8 flex flex-col gap-5 max-h-[75vh] overflow-y-auto custom-scrollbar">
            <div class="space-y-1.5">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider">Select Component</label>
                <select id="txItemSelect" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-primary-500 transition-all font-semibold text-gray-900 cursor-pointer">
                    <!-- Loaded dynamically -->
                </select>
            </div>

            <div class="grid grid-cols-2 gap-5">
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider">Movement Type</label>
                    <select id="txTypeSelect" required onchange="toggleTxShipping()" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-primary-500 transition-all font-semibold text-gray-900 cursor-pointer">
                        <option value="in">Inbound (Restock)</option>
                        <option value="out">Outbound (Sale)</option>
                    </select>
                </div>
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider">Quantity</label>
                    <input type="number" id="txQuantityInput" required min="1" oninput="if(typeof updateShippingWeight === 'function') updateShippingWeight()" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-primary-500 transition-all font-bold text-gray-900">
                </div>
            </div>

            <div class="space-y-1.5">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider">Final Status</label>
                <select id="txStatusSelect" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-primary-500 transition-all font-semibold text-gray-900 cursor-pointer">
                    <option value="completed">Completed</option>
                    <option value="pending">Pending</option>
                </select>
            </div>

            <div class="space-y-1.5">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider">Notes / Partner Info</label>
                <textarea id="txNotesInput" rows="2" placeholder="e.g. Supplier restock, Client order #123..." class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-primary-500 transition-all font-medium text-gray-900"></textarea>
            </div>

            <!-- Integrated Shipping Section -->
            <div id="txShippingWrapper" class="hidden flex-col gap-4 border border-primary-100 rounded-2xl p-6 bg-primary-50/30">
                <div class="flex justify-between items-center">
                    <h4 class="font-bold text-primary-900 text-sm flex items-center gap-2">
                        <i class="fa-solid fa-truck-fast text-primary-600"></i> Shipping Calculator
                    </h4>
                    <span class="text-[10px] font-bold text-primary-400 uppercase tracking-widest bg-white px-2 py-1 rounded-md border border-primary-100">Optional</span>
                </div>
                
                <div class="space-y-4">
                    <div class="relative group">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Destination Area</label>
                        <input type="text" id="txDestSearch" placeholder="Search city or district..." class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-primary-500 transition-all" autocomplete="off">
                        <input type="hidden" id="txDestAreaId">
                        <div id="txDestResults" class="absolute w-full bg-white border border-gray-100 shadow-2xl rounded-xl mt-2 hidden z-[100] max-h-48 overflow-y-auto"></div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Weight (g)</label>
                            <input type="number" id="txShipWeight" value="1000" class="w-full px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-primary-500 transition-all font-bold">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Courier</label>
                            <select id="txShipCourier" class="w-full px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-primary-500 transition-all font-bold">
                                <option value="jne">JNE</option>
                                <option value="sicepat">SiCepat</option>
                                <option value="jnt">J&T Express</option>
                                <option value="anteraja">AnterAja</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <button type="button" onclick="calculateTxShipping()" id="btnCalcTxShipping" class="w-full bg-primary-600 text-white font-bold py-2.5 rounded-xl hover:bg-primary-700 transition-all shadow-md shadow-primary-500/20 text-xs">
                    Get Shipping Rates
                </button>
                
                <div id="txShippingResults" class="hidden flex-col gap-2 max-h-32 overflow-y-auto mt-2">
                    <!-- Results -->
                </div>
            </div>

            <div class="mt-4 flex gap-4">
                <button type="button" onclick="closeTxModal()" class="flex-1 py-3.5 text-sm font-bold text-gray-500 bg-gray-100 hover:bg-gray-200 rounded-2xl transition-all">Cancel</button>
                <button type="submit" class="flex-2 py-3.5 px-8 text-sm font-black text-white bg-primary-600 hover:bg-primary-700 rounded-2xl transition-all shadow-lg shadow-primary-500/30">Commit Record</button>
            </div>
        </form>
    </div>
</div>

<!-- Transaction Detail Modal -->
<div id="txDetailModal" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-[65] hidden items-center justify-center p-4">
    <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-lg overflow-hidden flex flex-col transform transition-all">
        <div class="bg-primary-900 px-8 py-6 flex justify-between items-center text-white">
            <h3 class="font-extrabold text-xl tracking-tight flex items-center gap-3">
                <i class="fa-solid fa-receipt text-primary-400"></i> Transaction Details
            </h3>
            <button onclick="closeTxDetailModal()" class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center hover:bg-white/20 transition-colors"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>
        
        <div class="p-8 overflow-y-auto flex-1 flex flex-col gap-6 bg-gray-50/50">
            <div class="flex justify-between items-start">
                <div>
                    <h4 class="font-black text-gray-900 text-2xl tracking-tight" id="detailTxItemName">Loading...</h4>
                    <p class="text-primary-600 font-bold text-xs uppercase tracking-widest mt-1" id="detailTxItemSku">SKU: -</p>
                </div>
                <div id="detailTxTypeBadge" class="scale-110 origin-right"></div>
            </div>

            <div class="grid grid-cols-2 gap-6 bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                <div class="space-y-1">
                    <span class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Timestamp</span>
                    <span class="font-bold text-gray-900" id="detailTxDate">-</span>
                </div>
                <div class="space-y-1">
                    <span class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Quantity Moved</span>
                    <span class="font-black text-2xl text-primary-600" id="detailTxQuantity">-</span>
                </div>
                <div class="col-span-2 border-t border-gray-50 pt-4">
                    <span class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Partner & Internal Notes</span>
                    <div class="font-semibold text-gray-700 text-sm leading-relaxed p-4 bg-gray-50 rounded-xl border border-gray-100" id="detailTxNotes">-</div>
                </div>
            </div>

            <div class="bg-primary-900 p-6 rounded-2xl shadow-xl flex items-center justify-between text-white">
                <div>
                    <span class="block text-[10px] font-bold text-primary-400 uppercase tracking-widest mb-1">CURRENT STATUS</span>
                    <div id="detailTxStatusBadge"></div>
                </div>
                <div class="text-right">
                    <span class="block text-[10px] font-bold text-primary-400 uppercase tracking-widest mb-2">UPDATE STATUS</span>
                    <select id="detailTxStatusSelect" class="px-4 py-2 bg-white/10 border border-white/20 rounded-xl text-sm focus:outline-none focus:bg-white focus:text-gray-900 transition-all font-bold cursor-pointer">
                        <option value="pending">Pending</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
            </div>
            
            <input type="hidden" id="detailTxId">
        </div>
        
        <div class="bg-white px-8 py-6 border-t border-gray-100 flex justify-end gap-4">
            <button type="button" onclick="closeTxDetailModal()" class="px-6 py-3 text-sm font-bold text-gray-500 bg-gray-100 hover:bg-gray-200 rounded-xl transition-all">Dismiss</button>
            <button type="button" onclick="saveTxDetailStatus()" id="saveTxDetailBtn" class="px-8 py-3 text-sm font-black text-white bg-primary-600 hover:bg-primary-700 rounded-xl transition-all shadow-lg shadow-primary-500/30">Update Transaction</button>
        </div>
    </div>
</div>

<!-- Partner Modal -->
<div id="partnerModal" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-[60] hidden items-center justify-center p-4">
    <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-md overflow-hidden">
        <div class="bg-primary-900 px-8 py-6 flex justify-between items-center text-white">
            <div>
                <h3 id="partnerModalTitle" class="font-extrabold text-xl tracking-tight">Add New Partner</h3>
                <p class="text-primary-400 text-xs font-medium uppercase tracking-widest mt-0.5">Contact Directory</p>
            </div>
            <button onclick="closePartnerModal()" class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center hover:bg-white/20 transition-colors"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>
        <form id="partnerForm" onsubmit="submitPartner(event)" class="p-8 flex flex-col gap-5">
            <div class="space-y-1.5">
                <label id="partnerNameLabel" class="block text-xs font-bold text-gray-500 uppercase tracking-wider">Partner Name</label>
                <input type="text" id="partnerNameInput" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-primary-500 transition-all font-bold text-gray-900">
            </div>

            <div id="partnerTypeWrapper" class="space-y-1.5">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider">Account Classification</label>
                <select id="partnerTypeSelect" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-primary-500 transition-all font-bold text-gray-900 cursor-pointer">
                    <option value="Retail Store">Retail Store</option>
                    <option value="Wholesale Distributor">Wholesale Distributor</option>
                </select>
            </div>

            <div class="space-y-1.5">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider">Contact Phone</label>
                <input type="text" id="partnerPhoneInput" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-primary-500 transition-all font-bold text-gray-900">
            </div>

            <div class="space-y-1.5">
                <label id="partnerLocationLabel" class="block text-xs font-bold text-gray-500 uppercase tracking-wider">Primary Address</label>
                <input type="text" id="partnerLocationInput" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-primary-500 transition-all font-medium text-gray-900">
            </div>

            <div class="mt-4 flex gap-4">
                <button type="button" onclick="closePartnerModal()" class="flex-1 py-3.5 text-sm font-bold text-gray-500 bg-gray-100 hover:bg-gray-200 rounded-2xl transition-all">Cancel</button>
                <button type="submit" class="flex-2 py-3.5 px-8 text-sm font-black text-white bg-primary-600 hover:bg-primary-700 rounded-2xl transition-all shadow-lg shadow-primary-500/30">Save Partner</button>
            </div>
        </form>
    </div>
</div>
