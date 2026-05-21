<!-- Item Modal -->
<div id="itemModal" class="fixed inset-0 bg-gray-900/50 z-[60] hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
            <h3 id="modalTitle" class="font-semibold text-gray-800 text-lg">Add New Item</h3>
            <button onclick="closeItemModal()" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="itemForm" class="p-6 flex flex-col gap-4">
            <input type="hidden" id="itemId">
            
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Item Name</label>
                <input type="text" id="itemName" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-accent">
            </div>

            <!-- Brand / Merk Section -->
            <div class="grid grid-cols-2 gap-4" id="brandFieldWrapper">
                <div id="brandSelectContainer" class="col-span-2">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Merk / Seri Komponen</label>
                    <select id="itemBrandSelect" onchange="handleBrandSelectChange()" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-accent bg-white">
                        <!-- Dynamic options -->
                    </select>
                </div>
                <div id="customBrandContainer" class="hidden">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Merk Kustom</label>
                    <input type="text" id="itemBrandCustom" placeholder="Ketik merk kustom..." class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-accent">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Category</label>
                    <select id="itemCategory" onchange="updateBrandOptions()" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-accent bg-white">
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
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">SKU</label>
                    <input type="text" id="itemSku" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-accent">
                </div>
            </div>

            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Stock</label>
                    <input type="number" id="itemStock" required min="0" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-accent">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Price (USD)</label>
                    <input type="number" step="0.01" id="itemPriceUsd" required min="0" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-accent">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Margin (%)</label>
                    <input type="number" step="0.1" id="itemMargin" required min="0" value="10" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-accent">
                </div>
            </div>

            <div class="mt-4 flex gap-3 justify-end">
                <button type="button" onclick="closeItemModal()" class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition">Cancel</button>
                <button type="submit" id="saveItemBtn" class="px-4 py-2 text-sm font-semibold text-white bg-accent hover:bg-violet-600 rounded-lg transition">Save Item</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-900/50 z-[70] hidden items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-sm overflow-hidden text-center p-8">
        <div class="w-20 h-20 bg-red-50 text-red-500 rounded-full flex items-center justify-center text-4xl mx-auto mb-5 shadow-sm border-4 border-white ring-4 ring-red-50">
            <i class="fa-solid fa-trash-can"></i>
        </div>
        <h3 class="font-bold text-gray-900 text-xl mb-2">Delete Item?</h3>
        <p class="text-gray-500 text-sm mb-8">Are you sure you want to delete this component? This action cannot be undone.</p>
        <div class="flex gap-3 justify-center">
            <button onclick="closeDeleteModal()" class="px-5 py-3 font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition w-1/2">Cancel</button>
            <button id="confirmDeleteBtn" class="px-5 py-3 font-semibold text-white bg-red-500 hover:bg-red-600 rounded-xl transition w-1/2 shadow-lg shadow-red-500/30">Yes, Delete</button>
        </div>
    </div>
</div>

<!-- Category Sales Detail Modal -->
<div id="salesDetailModal" class="fixed inset-0 bg-gray-900/50 z-[80] hidden items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-3xl overflow-hidden flex flex-col max-h-[85vh]">
        <div class="bg-darknav px-6 py-5 flex justify-between items-center text-white shrink-0">
            <h3 class="font-bold text-lg flex items-center gap-2"><i class="fa-solid fa-chart-line text-accent"></i> Detail Penjualan - <span id="salesDetailCategoryName">GPU</span></h3>
            <button onclick="closeSalesDetailModal()" class="text-gray-400 hover:text-white transition"><i class="fa-solid fa-xmark text-xl"></i></button>
        </div>
        
        <div class="p-6 overflow-y-auto flex-1 flex flex-col gap-6">
            <!-- Mini Stats inside Modal -->
            <div class="grid grid-cols-3 gap-4">
                <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100">
                    <span class="text-xs text-gray-400 font-medium">Total Model Terjual</span>
                    <div class="text-xl font-bold text-gray-800 mt-1" id="salesDetailModelCount">0 Model</div>
                </div>
                <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100">
                    <span class="text-xs text-gray-400 font-medium">Total Unit Terjual</span>
                    <div class="text-xl font-bold text-green-500 mt-1" id="salesDetailUnitsSold">0 Unit</div>
                </div>
                <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100">
                    <span class="text-xs text-gray-400 font-medium">Total Omset Penjualan</span>
                    <div class="text-xl font-bold text-accent mt-1" id="salesDetailTotalRevenue">Rp 0</div>
                </div>
            </div>

            <div class="flex-1 flex flex-col min-h-0">
                <h4 class="font-semibold text-gray-700 text-sm mb-3">Rincian Per Produk</h4>
                <div class="overflow-x-auto border border-gray-100 rounded-2xl">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-gray-50 text-gray-400 border-b border-gray-100">
                                <th class="py-3 px-4 font-semibold">Nama Komponen</th>
                                <th class="py-3 px-4 font-semibold">SKU</th>
                                <th class="py-3 px-4 font-semibold text-right">Stok Gudang</th>
                                <th class="py-3 px-4 font-semibold text-right">Harga Jual (Rp)</th>
                                <th class="py-3 px-4 font-semibold text-right">Unit Terjual</th>
                                <th class="py-3 px-4 font-semibold text-right">Subtotal Omset</th>
                            </tr>
                        </thead>
                        <tbody id="salesDetailTableBody" class="text-gray-700">
                            <!-- Dynamic rows -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Shipping Calculator Modal -->
<div id="shippingModal" class="fixed inset-0 bg-gray-900/50 z-[80] hidden items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-2xl overflow-hidden flex flex-col max-h-[90vh]">
        <div class="bg-darknav px-6 py-5 flex justify-between items-center text-white shrink-0">
            <h3 class="font-bold text-lg flex items-center gap-2"><i class="fa-solid fa-truck-fast text-accent"></i> Shipping Calculator</h3>
            <button onclick="closeShippingModal()" class="text-gray-400 hover:text-white transition"><i class="fa-solid fa-xmark text-xl"></i></button>
        </div>
        
        <div class="p-6 overflow-y-auto flex-1 flex flex-col gap-6">
            <!-- Inputs -->
            <div class="grid grid-cols-2 gap-6 bg-gray-50 p-5 rounded-2xl border border-gray-100">
                <div class="space-y-4">
                    <h4 class="font-semibold text-gray-800 text-sm border-b border-gray-200 pb-2"><i class="fa-solid fa-location-dot text-red-500 mr-1"></i> Origin</h4>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Province</label>
                        <select id="originProvince" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm bg-white focus:outline-none focus:border-accent" onchange="loadCities('origin', this.value)"></select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">City</label>
                        <select id="originCity" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm bg-white focus:outline-none focus:border-accent"></select>
                    </div>
                </div>
                <div class="space-y-4">
                    <h4 class="font-semibold text-gray-800 text-sm border-b border-gray-200 pb-2"><i class="fa-solid fa-map-pin text-blue-500 mr-1"></i> Destination</h4>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Province</label>
                        <select id="destProvince" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm bg-white focus:outline-none focus:border-accent" onchange="loadCities('dest', this.value)"></select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">City</label>
                        <select id="destCity" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm bg-white focus:outline-none focus:border-accent"></select>
                    </div>
                </div>
                <div class="col-span-2 grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Weight (grams)</label>
                        <input type="number" id="shipWeight" value="1000" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-accent">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Courier</label>
                        <select id="shipCourier" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm bg-white focus:outline-none focus:border-accent">
                            <option value="jne">JNE</option>
                            <option value="pos">POS Indonesia</option>
                            <option value="tiki">TIKI</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Action -->
            <button onclick="calculateShipping()" id="btnCalcShipping" class="w-full bg-accent text-white font-semibold py-3 rounded-xl hover:bg-violet-600 transition shadow-lg shadow-violet-500/30">
                Calculate Cost
            </button>

            <!-- Results -->
            <div id="shippingResults" class="hidden flex-col gap-3">
                <!-- results go here -->
            </div>
        </div>
    </div>
</div>

<!-- Tooltip Element -->
<div id="chartjs-tooltip" style="opacity: 0;">
    <div class="font-bold mb-1" id="tooltip-title">July, 14</div>
    <div class="flex items-center gap-2 text-[10px]"><span class="w-2 h-2 rounded-full border border-gray-400 inline-block" id="tt-c1"></span> <span id="tooltip-v1">Sold 104</span></div>
    <div class="flex items-center gap-2 text-[10px]"><span class="w-2 h-2 rounded-full bg-accent inline-block" id="tt-c2"></span> <span id="tooltip-v2">Return 0</span></div>
</div>

<!-- Settings Modal -->
<div id="settingsModal" class="fixed inset-0 bg-gray-900/50 z-[80] hidden items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden flex flex-col max-h-[90vh]">
        <div class="bg-darknav px-6 py-5 flex justify-between items-center text-white shrink-0">
            <h3 class="font-bold text-lg flex items-center gap-2"><i class="fa-solid fa-gear text-accent"></i> Pengaturan Sistem</h3>
            <button onclick="closeSettingsModal()" class="text-gray-400 hover:text-white transition"><i class="fa-solid fa-xmark text-xl"></i></button>
        </div>
        
        <form id="settingsForm" onsubmit="saveSettings(event)" class="p-6 overflow-y-auto flex-1 flex flex-col gap-5">
            <div>
                <h4 class="font-bold text-gray-800 text-xs border-b border-gray-100 pb-2 mb-3">KONFIGURASI PRODUK</h4>
                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Margin Keuntungan Bawaan (%)</label>
                        <input type="number" step="0.1" id="settingsDefaultMargin" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-accent">
                        <span class="text-[10px] text-gray-400 mt-1 block">Margin keuntungan default saat menambahkan barang baru.</span>
                    </div>
                </div>
            </div>

            <div>
                <h4 class="font-bold text-gray-800 text-xs border-b border-gray-100 pb-2 mb-3">NILAI TUKAR MATA UANG (USD/IDR)</h4>
                <div class="space-y-3">
                    <div class="flex items-center gap-2">
                        <input type="checkbox" id="settingsUseManualRate" onchange="toggleManualRateInput()" class="rounded text-accent focus:ring-accent">
                        <label for="settingsUseManualRate" class="text-xs font-semibold text-gray-700">Gunakan Nilai Tukar Manual (Bekukan Kurs)</label>
                    </div>
                    <div id="settingsManualRateWrapper" class="hidden">
                        <label class="block text-xs font-medium text-gray-600 mb-1">Nilai Tukar Manual (Rp)</label>
                        <input type="number" id="settingsManualRate" placeholder="Misal: 16200" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-accent">
                        <span class="text-[10px] text-gray-400 mt-1 block">Kurs ini akan digunakan untuk semua konversi harga barang.</span>
                    </div>
                </div>
            </div>

            <div class="mt-4 flex gap-3 justify-end border-t border-gray-100 pt-4">
                <button type="button" onclick="closeSettingsModal()" class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition">Batal</button>
                <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-accent hover:bg-violet-600 rounded-lg transition">Simpan Pengaturan</button>
            </div>
        </form>
    </div>
</div>

<!-- Transaction Modal -->
<div id="txModal" class="fixed inset-0 bg-gray-900/50 z-[60] hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
            <h3 class="font-semibold text-gray-800 text-lg">Add Stock Transaction</h3>
            <button onclick="closeTxModal()" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="txForm" onsubmit="submitTransaction(event)" class="p-6 flex flex-col gap-4">
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Select Item / Component</label>
                <select id="txItemSelect" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-accent bg-white">
                    <!-- Loaded dynamically -->
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Type</label>
                    <select id="txTypeSelect" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-accent bg-white">
                        <option value="in">Inbound (Restock)</option>
                        <option value="out">Outbound (Sale)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Quantity</label>
                    <input type="number" id="txQuantityInput" required min="1" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-accent">
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                <select id="txStatusSelect" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-accent bg-white">
                    <option value="completed">Completed</option>
                    <option value="pending">Pending</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Notes / Description</label>
                <textarea id="txNotesInput" rows="2" placeholder="e.g. Distributed to Surabaya, Supplier restock etc." class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-accent"></textarea>
            </div>

            <div class="mt-4 flex gap-3 justify-end">
                <button type="button" onclick="closeTxModal()" class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition">Cancel</button>
                <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-accent hover:bg-violet-600 rounded-lg transition">Save Transaction</button>
            </div>
        </form>
    </div>
</div>

<!-- Partner Modal -->
<div id="partnerModal" class="fixed inset-0 bg-gray-900/50 z-[60] hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
            <h3 id="partnerModalTitle" class="font-semibold text-gray-800 text-lg">Add New Supplier</h3>
            <button onclick="closePartnerModal()" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="partnerForm" onsubmit="submitPartner(event)" class="p-6 flex flex-col gap-4">
            <div>
                <label id="partnerNameLabel" class="block text-xs font-medium text-gray-700 mb-1">Supplier Name</label>
                <input type="text" id="partnerNameInput" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-accent">
            </div>

            <div id="partnerTypeWrapper">
                <label class="block text-xs font-medium text-gray-700 mb-1">Customer Type</label>
                <select id="partnerTypeSelect" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-accent bg-white">
                    <option value="Retail Store">Retail Store</option>
                    <option value="Wholesale Distributor">Wholesale Distributor</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Phone</label>
                <input type="text" id="partnerPhoneInput" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-accent">
            </div>

            <div>
                <label id="partnerLocationLabel" class="block text-xs font-medium text-gray-700 mb-1">Address</label>
                <input type="text" id="partnerLocationInput" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-accent">
            </div>

            <div class="mt-4 flex gap-3 justify-end">
                <button type="button" onclick="closePartnerModal()" class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition">Cancel</button>
                <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-accent hover:bg-violet-600 rounded-lg transition">Save Partner</button>
            </div>
        </form>
    </div>
</div>
