<!-- VIEW 3: Categories / Gudang -->
<div id="view-categories" class="flex-1 flex flex-col p-8 overflow-y-auto hidden">
    <!-- GRID VIEW (Warehouse Main) -->
    <div id="warehouseGridContainer" class="flex flex-col flex-1">
        <header class="flex justify-between items-end mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Gudang & Rak Inventaris</h1>
                <p class="text-gray-500 text-sm mt-1">Pilih rak penyimpanan untuk mengelola stok komponen</p>
            </div>
            @include('partials.header-actions')
        </header>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="categoryGrid">
            <!-- Dynamic Category Cards -->
        </div>
    </div>

    <!-- SHELF VIEW (Cabinet Contents) -->
    <div id="warehouseShelfContainer" class="hidden flex-col flex-1">
        <header class="flex justify-between items-end mb-8">
            <div>
                <button onclick="backToWarehouseGrid()" class="text-xs font-semibold text-accent hover:text-violet-600 transition flex items-center gap-1 mb-2"><i class="fa-solid fa-arrow-left"></i> Kembali ke Gudang Utama</button>
                <h1 class="text-3xl font-bold text-gray-900" id="shelfTitle">Rak GPU</h1>
                <p class="text-gray-500 text-sm mt-1" id="shelfSubtitle">Daftar inventaris komponen di dalam rak GPU</p>
            </div>
            <div class="flex gap-3">
                <button id="addShelfItemBtn" class="bg-accent text-white text-xs font-semibold px-4 py-2.5 rounded-lg hover:bg-violet-600 transition shadow-sm"><i class="fa-solid fa-plus mr-1"></i> Tambah Barang</button>
            </div>
        </header>
        
        <div class="bg-white p-6 rounded-[2rem] shadow-sm flex-1">
            <div class="flex justify-between items-center mb-6">
                <h2 class="font-semibold text-gray-800 text-lg">Stok Rak Aktif</h2>
                <input type="text" id="shelfSearchInput" oninput="filterShelfItems()" placeholder="Cari berdasarkan nama/SKU..." class="px-4 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-accent w-64">
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-gray-400 text-xs border-b border-gray-100">
                            <th class="pb-4 font-medium px-2">Nama Barang</th>
                            <th class="pb-4 font-medium">SKU</th>
                            <th class="pb-4 font-medium">Stok</th>
                            <th class="pb-4 font-medium">Harga Beli (Rp)</th>
                            <th class="pb-4 font-medium">Margin</th>
                            <th class="pb-4 font-medium">Harga Jual Akhir</th>
                            <th class="pb-4 font-medium text-center px-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="shelfTableBody" class="text-sm">
                        <!-- JS populated -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
