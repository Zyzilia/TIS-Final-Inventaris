// Dummy Data for PC Parts
const pcPartsData = [
    { name: 'NVIDIA RTX 4090 GPU', sku: 'GPU-4090-FE', stock: 15, price: 28000000, old_price: 30000000, items_sold: 45, type: 'gpu', category_id: 1, brand: 'Nvidia GeForce' },
    { name: 'AMD Ryzen 9 7950X', sku: 'CPU-AMD-7950', stock: 32, price: 9500000, old_price: 10500000, items_sold: 120, type: 'cpu', category_id: 2, brand: 'AMD Ryzen' },
    { name: 'Corsair Vengeance 32GB DDR5', sku: 'RAM-COR-32D5', stock: 85, price: 2100000, old_price: 2500000, items_sold: 310, type: 'ram', category_id: 3, brand: 'Corsair Vengeance' },
    { name: 'Samsung 990 PRO 2TB NVMe', sku: 'SSD-SAM-2TB', stock: 120, price: 3200000, old_price: 3200000, items_sold: 450, type: 'ssd', category_id: 4, brand: 'Samsung PRO/EVO' },
    { name: 'ASUS ROG Crosshair X670E', sku: 'MB-ASUS-X670', stock: 20, price: 8500000, old_price: 9000000, items_sold: 65, type: 'mb', category_id: 5, brand: 'ASUS ROG/TUF/Prime' }
];

const categoryMeta = {
    1: { name: 'GPU', icon: 'fa-solid fa-microchip', bg: 'bg-indigo-50', text: 'text-indigo-500', border: 'border-indigo-100' },
    2: { name: 'CPU', icon: 'fa-solid fa-server', bg: 'bg-blue-50', text: 'text-blue-500', border: 'border-blue-100' },
    3: { name: 'RAM', icon: 'fa-solid fa-memory', bg: 'bg-green-50', text: 'text-green-500', border: 'border-green-100' },
    4: { name: 'Storage', icon: 'fa-solid fa-hard-drive', bg: 'bg-slate-50', text: 'text-slate-500', border: 'border-slate-100' },
    5: { name: 'Motherboard', icon: 'fa-solid fa-chess-board', bg: 'bg-red-50', text: 'text-red-500', border: 'border-red-100' },
    6: { name: 'Power Supply (PSU)', icon: 'fa-solid fa-plug', bg: 'bg-amber-50', text: 'text-amber-500', border: 'border-amber-100' },
    7: { name: 'PC Case', icon: 'fa-solid fa-computer', bg: 'bg-zinc-50', text: 'text-zinc-500', border: 'border-zinc-100' },
    8: { name: 'Cooling (Fan/AIO)', icon: 'fa-solid fa-fan', bg: 'bg-teal-50', text: 'text-teal-500', border: 'border-teal-100' }
};

const categoryBrands = {
    1: ['Nvidia GeForce', 'AMD Radeon', 'Intel Arc', 'Lainnya (Ketik Manual)'],
    2: ['AMD Ryzen', 'Intel Core', 'Intel Xeon', 'AMD EPYC', 'Lainnya (Ketik Manual)'],
    3: ['Corsair Vengeance', 'G.Skill Trident Z', 'Kingston FURY', 'TeamGroup T-Force', 'ADATA XPG', 'Crucial Pro', 'Lainnya (Ketik Manual)'],
    4: ['Samsung PRO/EVO', 'WD Black/Blue', 'Crucial MX/P-Series', 'Seagate FireCuda', 'Kingston KC/NV-Series', 'Lainnya (Ketik Manual)'],
    5: ['ASUS ROG/TUF/Prime', 'MSI MEG/MPG/MAG', 'Gigabyte AORUS/Gaming', 'ASRock Taichi/Phantom', 'Lainnya (Ketik Manual)'],
    6: ['Corsair RM/SF-Series', 'Seasonic FOCUS/PRIME', 'EVGA SuperNOVA', 'Cooler Master MWE', 'be quiet! Straight Power', 'Lainnya (Ketik Manual)'],
    7: ['NZXT H-Series', 'Lian Li O11/Lancool', 'Corsair iCUE/Carbide', 'Phanteks NV/Eclipse', 'Fractal Design North/Torrent', 'Lainnya (Ketik Manual)'],
    8: ['NZXT Kraken', 'Corsair iCUE H-Series', 'Noctua NH-Series', 'Deepcool LT/LS', 'Thermalright Peerless Assassin', 'Arctic Liquid Freezer', 'Lainnya (Ketik Manual)']
};

function getIconHtml(type) {
    const icons = {
        'gpu': '<i class="fa-solid fa-microchip text-accent"></i>',
        'cpu': '<i class="fa-solid fa-server text-blue-500"></i>',
        'ram': '<i class="fa-solid fa-memory text-green-500"></i>',
        'ssd': '<i class="fa-solid fa-hard-drive text-gray-700"></i>',
        'mb': '<i class="fa-solid fa-chess-board text-red-500"></i>',
        'psu': '<i class="fa-solid fa-plug text-yellow-500"></i>',
        'case': '<i class="fa-solid fa-computer text-gray-800"></i>',
        'cooling': '<i class="fa-solid fa-fan text-teal-400"></i>'
    };
    return icons[type] || '<i class="fa-solid fa-box text-gray-500"></i>';
}

let currentItems = [];
let selectedCategoryShelf = null;

function getItemSalesCount(item) {
    return (item.id * 17 + item.name.length * 3 + 12) % 150 + 5;
}

async function loadItems() {
    try {
        const res = await axios.get('/api/items');
        currentItems = res.data.data;
        
        renderCategorySalesSummary();
        if (selectedCategoryShelf) {
            renderShelfItems();
        }
        if (activeView === 'categories' && !selectedCategoryShelf) {
            loadCategoriesGrid();
        }
    } catch (err) {
        console.error(err);
        const tbody = document.getElementById('categorySalesTableBody');
        if (tbody) tbody.innerHTML = `<tr><td colspan="6" class="text-center text-red-500 py-4">Gagal memuat data</td></tr>`;
    }
}

function renderCategorySalesSummary() {
    const tbody = document.getElementById('categorySalesTableBody');
    if (!tbody) return;
    tbody.innerHTML = '';

    const groups = {};
    for (let i = 1; i <= 8; i++) {
        groups[i] = { count: 0, totalStock: 0, totalSold: 0, totalRevenue: 0 };
    }

    let items = currentItems;
    if(!items || items.length === 0) items = pcPartsData;

    items.forEach(item => {
        const catId = item.category_id || 1;
        if (!groups[catId]) {
            groups[catId] = { count: 0, totalStock: 0, totalSold: 0, totalRevenue: 0 };
        }
        const sold = getItemSalesCount(item);
        groups[catId].count += 1;
        groups[catId].totalStock += Number(item.stock || 0);
        groups[catId].totalSold += sold;
        groups[catId].totalRevenue += sold * Number(item.price || 0);
    });

    let totalGlobalSold = 0;
    Object.keys(groups).forEach(catId => {
        totalGlobalSold += groups[catId].totalSold;
    });

    Object.keys(categoryMeta).forEach(catId => {
        const meta = categoryMeta[catId];
        const stats = groups[catId];
        
        const percentage = totalGlobalSold > 0 ? Math.round((stats.totalSold / totalGlobalSold) * 100) : 0;

        tbody.innerHTML += `
            <tr class="border-b border-gray-50 hover:bg-gray-50 transition text-xs">
                <td class="py-4 px-2 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center text-sm border border-gray-100 ${meta.bg} ${meta.text}">
                        <i class="${meta.icon}"></i>
                    </div>
                    <span class="font-semibold text-gray-800 text-sm">${meta.name}</span>
                </td>
                <td class="py-4 font-medium text-gray-700">${stats.count} Model</td>
                <td class="py-4 font-semibold text-gray-800">${stats.totalStock} Unit</td>
                <td class="py-4 text-right font-bold text-gray-600 px-4">Rp ${Number(stats.totalRevenue).toLocaleString('id-ID', {maximumFractionDigits: 0})}</td>
                <td class="py-4 px-6">
                    <div class="flex items-center gap-2">
                        <div class="w-full bg-gray-100 rounded-full h-2">
                            <div class="bg-accent h-2 rounded-full" style="width: ${percentage}%"></div>
                        </div>
                        <span class="font-semibold text-gray-600 w-8 text-right">${percentage}%</span>
                    </div>
                </td>
                <td class="py-4 text-center">
                    <button onclick="openSalesDetailModal(${catId})" class="text-accent hover:text-violet-600 mx-1 bg-violet-50 hover:bg-violet-100 w-8 h-8 rounded-full flex items-center justify-center transition border border-violet-100 inline-flex mx-auto" title="Detail Penjualan">
                        <i class="fa-solid fa-eye text-xs"></i>
                    </button>
                </td>
            </tr>
        `;
    });
}

function loadCategoriesGrid() {
    const grid = document.getElementById('categoryGrid');
    if (!grid) return;
    grid.innerHTML = '';

    const groups = {};
    for (let i = 1; i <= 8; i++) {
        groups[i] = { count: 0, totalStock: 0, totalValue: 0 };
    }

    let items = currentItems;
    if(!items || items.length === 0) items = pcPartsData;

    items.forEach(item => {
        const catId = item.category_id || 1;
        if (!groups[catId]) {
            groups[catId] = { count: 0, totalStock: 0, totalValue: 0 };
        }
        groups[catId].count += 1;
        groups[catId].totalStock += Number(item.stock || 0);
        groups[catId].totalValue += Number(item.stock || 0) * Number(item.price || 0);
    });

    Object.keys(categoryMeta).forEach(catId => {
        const meta = categoryMeta[catId];
        const stats = groups[catId];

        grid.innerHTML += `
            <div onclick="openCategoryShelf(${catId})" class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm flex flex-col justify-between hover:shadow-lg hover:border-accent/30 hover:-translate-y-1 transition duration-300 cursor-pointer">
                <div class="flex justify-between items-start">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-xl ${meta.bg} ${meta.text} border ${meta.border}">
                        <i class="${meta.icon}"></i>
                    </div>
                    <span class="text-xs font-semibold text-gray-400 bg-gray-50 px-2.5 py-1 rounded-full border border-gray-100">${stats.count} Models</span>
                </div>
                <div class="mt-6">
                    <h3 class="font-bold text-gray-800 text-lg">${meta.name}</h3>
                    <div class="flex justify-between items-baseline mt-4 border-b border-gray-50 pb-2">
                        <span class="text-xs text-gray-400">Total Stock:</span>
                        <span class="font-bold text-gray-800 text-sm">${stats.totalStock} Units</span>
                    </div>
                    <div class="flex justify-between items-baseline mt-2">
                        <span class="text-xs text-gray-400">Asset Value:</span>
                        <span class="font-bold text-accent text-sm">Rp ${stats.totalValue.toLocaleString('id-ID', {maximumFractionDigits: 0})}</span>
                    </div>
                </div>
            </div>
        `;
    });
}

function openCategoryShelf(catId) {
    selectedCategoryShelf = parseInt(catId);
    const meta = categoryMeta[catId];
    
    document.getElementById('shelfTitle').textContent = `Rak ${meta.name}`;
    document.getElementById('shelfSubtitle').textContent = `Daftar inventaris komponen di dalam rak ${meta.name}`;
    
    document.getElementById('warehouseGridContainer').classList.add('hidden');
    document.getElementById('warehouseShelfContainer').classList.remove('hidden');
    document.getElementById('warehouseShelfContainer').classList.add('flex');

    const addBtn = document.getElementById('addShelfItemBtn');
    addBtn.onclick = () => {
        openItemModalWithCategory(catId);
    };

    renderShelfItems();
}

function backToWarehouseGrid() {
    selectedCategoryShelf = null;
    document.getElementById('warehouseShelfContainer').classList.add('hidden');
    document.getElementById('warehouseShelfContainer').classList.remove('flex');
    document.getElementById('warehouseGridContainer').classList.remove('hidden');
    loadCategoriesGrid();
}

function openItemModalWithCategory(catId) {
    openItemModal();
    const catSelect = document.getElementById('itemCategory');
    catSelect.value = catId;
}

function renderShelfItems() {
    const tbody = document.getElementById('shelfTableBody');
    if (!tbody || !selectedCategoryShelf) return;
    tbody.innerHTML = '';

    const search = document.getElementById('shelfSearchInput').value.toLowerCase();
    let items = currentItems;
    if(!items || items.length === 0) items = pcPartsData;

    const shelfItems = items.filter(item => {
        const matchesCategory = parseInt(item.category_id) === selectedCategoryShelf;
        const matchesSearch = item.name.toLowerCase().includes(search) || item.sku.toLowerCase().includes(search);
        return matchesCategory && matchesSearch;
    });

    if (shelfItems.length === 0) {
        tbody.innerHTML = `<tr><td colspan="7" class="text-center text-gray-500 py-8">Tidak ada barang di dalam rak ini</td></tr>`;
        return;
    }

    shelfItems.forEach(item => {
        let basePriceIDR = item.price / (1 + (item.profit_margin / 100));
        tbody.innerHTML += `
            <tr class="border-b border-gray-50 hover:bg-gray-50 transition text-xs">
                <td class="py-4 px-2">
                    <div class="flex flex-col">
                        <span class="font-semibold text-gray-800 text-sm max-w-xs truncate" title="${item.name}">${item.name}</span>
                        ${item.brand ? `<span class="text-[10px] text-gray-400 font-medium mt-0.5">${item.brand}</span>` : ''}
                    </div>
                </td>
                <td class="py-4 font-mono text-gray-500">${item.sku}</td>
                <td class="py-4 font-bold text-gray-800">${item.stock} Unit</td>
                <td class="py-4 text-gray-500">Rp ${basePriceIDR.toLocaleString('id-ID', {maximumFractionDigits: 0})}</td>
                <td class="py-4"><span class="text-green-500 font-semibold bg-green-50 px-2 py-0.5 rounded text-[10px]">+${item.profit_margin}%</span></td>
                <td class="py-4 font-semibold text-gray-800">Rp ${Number(item.price).toLocaleString('id-ID', {maximumFractionDigits: 0})}</td>
                <td class="py-4 text-center px-2">
                    <button onclick="openItemModal(${item.id})" class="text-blue-500 hover:text-blue-700 mx-1" title="Edit"><i class="fa-solid fa-pen-to-square"></i></button>
                    <button onclick="confirmDelete(${item.id})" class="text-red-500 hover:text-red-700 mx-1" title="Hapus"><i class="fa-solid fa-trash"></i></button>
                </td>
            </tr>
        `;
    });
}

function filterShelfItems() {
    renderShelfItems();
}
