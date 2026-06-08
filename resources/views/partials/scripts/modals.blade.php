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
function getSettingsModal() { return document.getElementById('settingsModal'); }

function loadSettings() {
    const defaultMargin = localStorage.getItem('settings_default_margin') || '10';
    const useManualRate = localStorage.getItem('settings_use_manual_rate') === 'true';
    const manualRate = localStorage.getItem('settings_manual_rate') || '16200';

    const marginEl = document.getElementById('settingsDefaultMargin');
    const useRateEl = document.getElementById('settingsUseManualRate');
    const rateEl = document.getElementById('settingsManualRate');

    if(marginEl) marginEl.value = defaultMargin;
    if(useRateEl) useRateEl.checked = useManualRate;
    if(rateEl) rateEl.value = manualRate;

    toggleManualRateInput();
}

function toggleManualRateInput() {
    const checkEl = document.getElementById('settingsUseManualRate');
    if(!checkEl) return;
    const checked = checkEl.checked;
    const wrapper = document.getElementById('settingsManualRateWrapper');
    if (checked) {
        if(wrapper) wrapper.classList.remove('hidden');
        document.getElementById('settingsManualRate').required = true;
    } else {
        if(wrapper) wrapper.classList.add('hidden');
        document.getElementById('settingsManualRate').required = false;
    }
}

function openSettingsModal() {
    loadSettings();
    const modal = getSettingsModal();
    if(modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
}

function closeSettingsModal() {
    const modal = getSettingsModal();
    if(modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
}

// Item Modal Logic
function getItemModal() { return document.getElementById('itemModal'); }
function getItemForm() { return document.getElementById('itemForm'); }

function openItemModal(id = null) {
    const form = getItemForm();
    if(form) form.reset();
    
    const idEl = document.getElementById('itemId');
    const titleEl = document.getElementById('modalTitle');
    const catEl = document.getElementById('itemCategory');
    const marginEl = document.getElementById('itemMargin');

    if(idEl) idEl.value = '';
    if(titleEl) titleEl.textContent = 'Add New Item';
    
    // Set default category
    if(catEl) catEl.value = 1;
    updateBrandOptions();

    const defaultMargin = localStorage.getItem('settings_default_margin') || '10';
    if(marginEl) marginEl.value = defaultMargin;
    
    if(id) {
        const item = currentItems.find(i => i.id === id);
        if(item) {
            if(titleEl) titleEl.textContent = 'Edit Item';
            if(idEl) idEl.value = item.id;
            const nameEl = document.getElementById('itemName');
            if(nameEl) nameEl.value = item.name;
            if(catEl) catEl.value = item.category_id || 1;
            
            // Update brand options based on edit item's category
            updateBrandOptions();

            // Check if item's brand is in standard options
            const standardBrands = categoryBrands[item.category_id || 1] || [];
            const brandSelectEl = document.getElementById('itemBrandSelect');
            if (brandSelectEl) {
                if (item.brand && standardBrands.includes(item.brand) && item.brand !== 'Lainnya (Ketik Manual)') {
                    brandSelectEl.value = item.brand;
                    handleBrandSelectChange();
                } else if (item.brand) {
                    brandSelectEl.value = 'Lainnya (Ketik Manual)';
                    handleBrandSelectChange();
                    const customBrandEl = document.getElementById('itemBrandCustom');
                    if(customBrandEl) customBrandEl.value = item.brand;
                }
            }

            const skuEl = document.getElementById('itemSku');
            if(skuEl) skuEl.value = item.sku;
            const stockEl = document.getElementById('itemStock');
            if(stockEl) stockEl.value = item.stock;
            const priceEl = document.getElementById('itemPriceUsd');
            if(priceEl) priceEl.value = item.price_usd;
            if(marginEl) marginEl.value = item.profit_margin;
            const weightEl = document.getElementById('itemWeight');
            if(weightEl) weightEl.value = item.weight || 1000;
        }
    }
    const modal = getItemModal();
    if(modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
}

function openItemModalWithCategory(catId) {
    openItemModal();
    const catSelect = document.getElementById('itemCategory');
    if (catSelect) {
        catSelect.value = catId;
        updateBrandOptions();
    }
}

function closeItemModal() {
    const modal = getItemModal();
    if(modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
}

const itemFormElem = document.getElementById('itemForm');
if(itemFormElem) {
    itemFormElem.addEventListener('submit', async (e) => {
        e.preventDefault();
        const btn = document.getElementById('saveItemBtn');
        if(btn) {
            btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i>';
            btn.disabled = true;
        }

        const id = document.getElementById('itemId').value;
        
        const brandSelectEl = document.getElementById('itemBrandSelect');
        let brandVal = brandSelectEl ? brandSelectEl.value : '';
        if (brandVal === 'Lainnya (Ketik Manual)') {
            const customBrandEl = document.getElementById('itemBrandCustom');
            brandVal = customBrandEl ? customBrandEl.value : '';
        }

        const data = {
            category_id: document.getElementById('itemCategory').value,
            brand: brandVal,
            name: document.getElementById('itemName').value,
            sku: document.getElementById('itemSku').value,
            stock: document.getElementById('itemStock').value,
            price_usd: document.getElementById('itemPriceUsd').value,
            profit_margin: document.getElementById('itemMargin').value,
            weight: document.getElementById('itemWeight').value,
        };

        try {
            if (id) {
                await axios.put(`/api/items/${id}`, data);
            } else {
                await axios.post('/api/items', data);
            }
            closeItemModal();
            
            // Refresh data
            if (typeof loadDashboardData === 'function') await loadDashboardData();
            
            // If in warehouse shelf view, refresh the shelf items
            if (typeof renderShelfItems === 'function' && selectedCategoryShelf) {
                renderShelfItems();
            }
        } catch (error) {
            alert(error.response?.data?.message || 'Failed to save item');
        } finally {
            if(btn) {
                btn.innerHTML = 'Save Item';
                btn.disabled = false;
            }
        }
    });
}

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
