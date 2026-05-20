function updateBrandOptions() {
    const catId = document.getElementById('itemCategory').value || 1;
    const select = document.getElementById('itemBrandSelect');
    if(!select) return;

    select.innerHTML = '';
    const brands = categoryBrands[catId] || ['Lainnya (Ketik Manual)'];
    brands.forEach(b => {
        const opt = document.createElement('option');
        opt.value = b;
        opt.textContent = b;
        select.appendChild(opt);
    });

    handleBrandSelectChange();
}

function handleBrandSelectChange() {
    const select = document.getElementById('itemBrandSelect');
    const customContainer = document.getElementById('customBrandContainer');
    const selectContainer = document.getElementById('brandSelectContainer');

    if(select.value === 'Lainnya (Ketik Manual)') {
        customContainer.classList.remove('hidden');
        selectContainer.className = 'col-span-1';
        document.getElementById('itemBrandCustom').required = true;
    } else {
        customContainer.classList.add('hidden');
        selectContainer.className = 'col-span-2';
        document.getElementById('itemBrandCustom').required = false;
    }
}

// Settings Modal Logic
const settingsModal = document.getElementById('settingsModal');

function loadSettings() {
    const defaultMargin = localStorage.getItem('settings_default_margin') || '10';
    const useManualRate = localStorage.getItem('settings_use_manual_rate') === 'true';
    const manualRate = localStorage.getItem('settings_manual_rate') || '16200';

    document.getElementById('settingsDefaultMargin').value = defaultMargin;
    document.getElementById('settingsUseManualRate').checked = useManualRate;
    document.getElementById('settingsManualRate').value = manualRate;

    toggleManualRateInput();
}

function toggleManualRateInput() {
    const checked = document.getElementById('settingsUseManualRate').checked;
    const wrapper = document.getElementById('settingsManualRateWrapper');
    if (checked) {
        wrapper.classList.remove('hidden');
        document.getElementById('settingsManualRate').required = true;
    } else {
        wrapper.classList.add('hidden');
        document.getElementById('settingsManualRate').required = false;
    }
}

function openSettingsModal() {
    loadSettings();
    settingsModal.classList.remove('hidden');
    settingsModal.classList.add('flex');
}

function closeSettingsModal() {
    settingsModal.classList.add('hidden');
    settingsModal.classList.remove('flex');
}

function saveSettings(e) {
    e.preventDefault();
    const defaultMargin = document.getElementById('settingsDefaultMargin').value;
    const useManualRate = document.getElementById('settingsUseManualRate').checked;
    const manualRate = document.getElementById('settingsManualRate').value;

    localStorage.setItem('settings_default_margin', defaultMargin);
    localStorage.setItem('settings_use_manual_rate', useManualRate);
    localStorage.setItem('settings_manual_rate', manualRate);

    closeSettingsModal();
    fetchExchangeRate();
    loadItems();
    alert('Pengaturan berhasil disimpan!');
}

// Item Modal Logic
const itemModal = document.getElementById('itemModal');
const itemForm = document.getElementById('itemForm');

function openItemModal(id = null) {
    itemForm.reset();
    document.getElementById('itemId').value = '';
    document.getElementById('modalTitle').textContent = 'Add New Item';
    
    // Set default category
    document.getElementById('itemCategory').value = 1;
    updateBrandOptions();

    const defaultMargin = localStorage.getItem('settings_default_margin') || '10';
    document.getElementById('itemMargin').value = defaultMargin;
    
    if(id) {
        const item = currentItems.find(i => i.id === id);
        if(item) {
            document.getElementById('modalTitle').textContent = 'Edit Item';
            document.getElementById('itemId').value = item.id;
            document.getElementById('itemName').value = item.name;
            document.getElementById('itemCategory').value = item.category_id || 1;
            
            // Update brand options based on edit item's category
            updateBrandOptions();

            // Check if item's brand is in standard options
            const standardBrands = categoryBrands[item.category_id || 1] || [];
            if (item.brand && standardBrands.includes(item.brand) && item.brand !== 'Lainnya (Ketik Manual)') {
                document.getElementById('itemBrandSelect').value = item.brand;
                handleBrandSelectChange();
            } else if (item.brand) {
                document.getElementById('itemBrandSelect').value = 'Lainnya (Ketik Manual)';
                handleBrandSelectChange();
                document.getElementById('itemBrandCustom').value = item.brand;
            }

            document.getElementById('itemSku').value = item.sku;
            document.getElementById('itemStock').value = item.stock;
            document.getElementById('itemPriceUsd').value = item.price_usd;
            document.getElementById('itemMargin').value = item.profit_margin;
        }
    }
    itemModal.classList.remove('hidden');
    itemModal.classList.add('flex');
}

function closeItemModal() {
    itemModal.classList.add('hidden');
    itemModal.classList.remove('flex');
}

itemForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    const btn = document.getElementById('saveItemBtn');
    btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i>';
    btn.disabled = true;

    const id = document.getElementById('itemId').value;
    
    let brandVal = document.getElementById('itemBrandSelect').value;
    if (brandVal === 'Lainnya (Ketik Manual)') {
        brandVal = document.getElementById('itemBrandCustom').value;
    }

    const data = {
        category_id: document.getElementById('itemCategory').value,
        brand: brandVal,
        name: document.getElementById('itemName').value,
        sku: document.getElementById('itemSku').value,
        stock: document.getElementById('itemStock').value,
        price_usd: document.getElementById('itemPriceUsd').value,
        profit_margin: document.getElementById('itemMargin').value,
    };

    try {
        if (id) {
            await axios.put(`/api/items/${id}`, data);
        } else {
            await axios.post('/api/items', data);
        }
        closeItemModal();
        loadDashboardData();
    } catch (error) {
        alert(error.response?.data?.message || 'Failed to save item');
    } finally {
        btn.innerHTML = 'Save Item';
        btn.disabled = false;
    }
});

// Delete Modal Logic
let itemToDelete = null;
const deleteModal = document.getElementById('deleteModal');

function confirmDelete(id) {
    itemToDelete = id;
    deleteModal.classList.remove('hidden');
    deleteModal.classList.add('flex');
}

function closeDeleteModal() {
    itemToDelete = null;
    deleteModal.classList.add('hidden');
    deleteModal.classList.remove('flex');
}

document.getElementById('confirmDeleteBtn').addEventListener('click', async (e) => {
    if(!itemToDelete) return;
    const btn = e.target;
    btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i>';
    btn.disabled = true;
    try {
        await axios.delete(`/api/items/${itemToDelete}`);
        closeDeleteModal();
        loadDashboardData();
    } catch (error) {
        alert(error.response?.data?.message || 'Failed to delete item');
    } finally {
        btn.innerHTML = 'Yes, Delete';
        btn.disabled = false;
    }
});

// Sales Detail Modal Logic
const salesDetailModal = document.getElementById('salesDetailModal');

function openSalesDetailModal(catId) {
    const meta = categoryMeta[catId];
    document.getElementById('salesDetailCategoryName').textContent = meta.name;

    const tbody = document.getElementById('salesDetailTableBody');
    tbody.innerHTML = '';

    let items = currentItems;
    if(!items || items.length === 0) items = pcPartsData;

    const categoryItems = items.filter(item => parseInt(item.category_id) === parseInt(catId));

    let totalModels = categoryItems.length;
    let totalUnitsSold = 0;
    let totalRevenue = 0;

    if (categoryItems.length === 0) {
        tbody.innerHTML = `<tr><td colspan="6" class="text-center text-gray-500 py-6">Tidak ada penjualan tercatat untuk kategori ini</td></tr>`;
    } else {
        categoryItems.forEach(item => {
            const sold = getItemSalesCount(item);
            const revenue = sold * Number(item.price || 0);

            totalUnitsSold += sold;
            totalRevenue += revenue;

            tbody.innerHTML += `
                <tr class="border-b border-gray-50 hover:bg-gray-50 transition text-xs">
                    <td class="py-3 px-4 text-left">
                        <div class="flex flex-col">
                            <span class="font-semibold text-gray-800 text-sm max-w-xs truncate" title="${item.name}">${item.name}</span>
                            ${item.brand ? `<span class="text-[10px] text-gray-400 font-medium mt-0.5">${item.brand}</span>` : ''}
                        </div>
                    </td>
                    <td class="py-3 px-4 font-mono text-gray-500">${item.sku}</td>
                    <td class="py-3 px-4 text-right font-medium text-gray-600">${item.stock} Unit</td>
                    <td class="py-3 px-4 text-right font-semibold text-gray-800">Rp ${Number(item.price).toLocaleString('id-ID', {maximumFractionDigits: 0})}</td>
                    <td class="py-3 px-4 text-right font-bold text-green-600">${sold}</td>
                    <td class="py-3 px-4 text-right font-bold text-accent">Rp ${revenue.toLocaleString('id-ID', {maximumFractionDigits: 0})}</td>
                </tr>
            `;
        });
    }

    document.getElementById('salesDetailModelCount').textContent = `${totalModels} Model`;
    document.getElementById('salesDetailUnitsSold').textContent = `${totalUnitsSold} Unit`;
    document.getElementById('salesDetailTotalRevenue').textContent = `Rp ${totalRevenue.toLocaleString('id-ID', {maximumFractionDigits: 0})}`;

    salesDetailModal.classList.remove('hidden');
    salesDetailModal.classList.add('flex');
}

function closeSalesDetailModal() {
    salesDetailModal.classList.add('hidden');
    salesDetailModal.classList.remove('flex');
}
