let currentTransactions = [];

async function renderTransactions() {
    const tbody = document.getElementById('txTableBody');
    if (!tbody) return;

    try {
        const res = await axios.get('/api/transactions');
        currentTransactions = res.data.data;
        filterTransactions();
    } catch (err) {
        console.error(err);
        tbody.innerHTML = `<tr><td colspan="7" class="text-center text-red-500 py-4">Gagal memuat data transaksi</td></tr>`;
    }
}

function filterTransactions() {
    const tbody = document.getElementById('txTableBody');
    if (!tbody) return;

    const searchQuery = document.getElementById('txSearch').value.toLowerCase();
    const filterType = document.getElementById('txFilter').value.toLowerCase();
    const statusFilter = document.getElementById('txStatusFilter') ? document.getElementById('txStatusFilter').value.toLowerCase() : '';
    const dateFilter = document.getElementById('txDateFilter').value; // YYYY-MM-DD

    const filtered = currentTransactions.filter(tx => {
        const itemName = tx.item ? tx.item.name.toLowerCase() : '';
        const itemSku = tx.item ? tx.item.sku.toLowerCase() : '';
        const matchesSearch = itemName.includes(searchQuery) || itemSku.includes(searchQuery);
        const matchesType = !filterType || tx.type.toLowerCase() === filterType;
        const matchesStatus = !statusFilter || tx.status.toLowerCase() === statusFilter;
        
        let matchesDate = true;
        if (dateFilter) {
            const txDate = tx.created_at.substring(0, 10);
            matchesDate = (txDate === dateFilter);
        }
        
        return matchesSearch && matchesType && matchesStatus && matchesDate;
    });

    tbody.innerHTML = '';

    if (filtered.length === 0) {
        tbody.innerHTML = `<tr><td colspan="7" class="text-center text-gray-500 py-8">Tidak ada transaksi ditemukan</td></tr>`;
        return;
    }

    filtered.forEach(tx => {
        const dateStr = new Date(tx.created_at).toLocaleDateString('id-ID', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });

        const isIncoming = tx.type === 'in';
        const typeBadge = isIncoming 
            ? `<span class="bg-green-50 text-green-600 px-3 py-1 rounded-full text-xs font-semibold border border-green-100 inline-flex items-center gap-1"><i class="fa-solid fa-arrow-down"></i> Inbound</span>`
            : `<span class="bg-indigo-50 text-indigo-600 px-3 py-1 rounded-full text-xs font-semibold border border-indigo-100 inline-flex items-center gap-1"><i class="fa-solid fa-arrow-up"></i> Outbound</span>`;

        const isCompleted = tx.status === 'completed';
        const statusBadge = isCompleted 
            ? `<span class="bg-emerald-50 text-emerald-600 px-3 py-1 rounded-full text-xs font-semibold border border-emerald-100 inline-flex items-center gap-1"><i class="fa-solid fa-circle-check"></i> Completed</span>`
            : `<span class="bg-amber-50 text-amber-600 px-3 py-1 rounded-full text-xs font-semibold border border-amber-100 inline-flex items-center gap-1"><i class="fa-solid fa-circle-notch fa-spin"></i> Pending</span>`;

        const statusColumn = `
            <div class="flex items-center gap-2">
                ${statusBadge}
                <button onclick="openTxDetailModal(${tx.id})" class="text-accent hover:text-violet-700 font-medium text-xs flex items-center justify-center bg-violet-50 hover:bg-violet-100 border border-violet-100 w-7 h-7 rounded-md shadow-sm transition" title="Lihat Detail Transaksi">
                    <i class="fa-solid fa-eye text-xs"></i>
                </button>
            </div>
        `;

        const partnerName = isIncoming 
            ? (tx.item && tx.item.supplier ? tx.item.supplier.name : 'Supplier')
            : (tx.notes || 'Customer');

        tbody.innerHTML += `
            <tr class="border-b border-gray-50 hover:bg-gray-50 transition text-xs">
                <td class="py-4 px-2 text-gray-500 font-medium">${dateStr}</td>
                <td class="py-4 font-semibold text-gray-800 text-sm">${tx.item ? tx.item.name : 'Unknown Item'}</td>
                <td class="py-4 font-mono text-gray-400">${tx.item ? tx.item.sku : '-'}</td>
                <td class="py-4">${typeBadge}</td>
                <td class="py-4 text-center font-bold ${isIncoming ? 'text-green-600' : 'text-indigo-600'}">${isIncoming ? '+' : '-'}${tx.quantity}</td>
                <td class="py-4 text-gray-700 font-medium">${partnerName}</td>
                <td class="py-4">${statusColumn}</td>
            </tr>
        `;
    });
}

function openTxModal() {
    const modal = document.getElementById('txModal');
    if (!modal) return;

    const select = document.getElementById('txItemSelect');
    if (select) {
        select.innerHTML = '<option value="">-- Choose Item --</option>';
        axios.get('/api/items')
            .then(res => {
                const items = res.data.data;
                items.forEach(item => {
                    select.innerHTML += `<option value="${item.id}" data-weight="${item.weight || 1000}">${item.name} (${item.sku}) - Stock: ${item.stock}</option>`;
                });
            })
            .catch(err => console.error(err));
    }

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeTxModal() {
    const modal = document.getElementById('txModal');
    if (!modal) return;
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.getElementById('txForm').reset();
}

async function submitTransaction(event) {
    event.preventDefault();

    const itemId = document.getElementById('txItemSelect').value;
    const type = document.getElementById('txTypeSelect').value;
    const quantity = document.getElementById('txQuantityInput').value;
    const status = document.getElementById('txStatusSelect').value;
    const notes = document.getElementById('txNotesInput').value;

    try {
        const res = await axios.post('/api/transactions', {
            item_id: itemId,
            type: type,
            quantity: quantity,
            status: status,
            notes: notes
        });

        alert('Transaksi berhasil ditambahkan!');
        closeTxModal();
        
        renderTransactions();
        if (typeof loadDashboardData === 'function') {
            loadDashboardData();
        }
    } catch (err) {
        console.error(err);
        const errMsg = err.response && err.response.data && err.response.data.message 
            ? err.response.data.message 
            : 'Gagal menambahkan transaksi';
        alert(errMsg);
    }
}

// --- Transaction Detail Modal ---

function openTxDetailModal(id) {
    const tx = currentTransactions.find(t => t.id === id);
    if (!tx) return;

    const modal = document.getElementById('txDetailModal');
    
    document.getElementById('detailTxId').value = tx.id;
    document.getElementById('detailTxItemName').textContent = tx.item ? tx.item.name : 'Unknown Item';
    document.getElementById('detailTxItemSku').textContent = `SKU: ${tx.item ? tx.item.sku : '-'}`;
    
    const isIncoming = tx.type === 'in';
    document.getElementById('detailTxTypeBadge').innerHTML = isIncoming 
        ? `<span class="bg-green-50 text-green-600 px-3 py-1 rounded-full text-xs font-semibold border border-green-100 inline-flex items-center gap-1"><i class="fa-solid fa-arrow-down"></i> Inbound</span>`
        : `<span class="bg-indigo-50 text-indigo-600 px-3 py-1 rounded-full text-xs font-semibold border border-indigo-100 inline-flex items-center gap-1"><i class="fa-solid fa-arrow-up"></i> Outbound</span>`;
        
    const dateStr = new Date(tx.created_at).toLocaleDateString('id-ID', {
        year: 'numeric', month: 'long', day: 'numeric',
        hour: '2-digit', minute: '2-digit'
    });
    document.getElementById('detailTxDate').textContent = dateStr;
    
    document.getElementById('detailTxQuantity').textContent = `${isIncoming ? '+' : '-'}${tx.quantity} Unit`;
    
    const partnerName = isIncoming 
        ? (tx.item && tx.item.supplier ? tx.item.supplier.name : 'Supplier')
        : (tx.notes || 'Customer');
    
    // Notes logic: replace newline with <br>
    const notesHtml = tx.notes ? tx.notes.replace(/\n/g, '<br>') : '-';
    document.getElementById('detailTxNotes').innerHTML = `<strong>Partner:</strong> ${partnerName}<br><br><span class="text-xs text-gray-500">${notesHtml}</span>`;

    const isCompleted = tx.status === 'completed';
    document.getElementById('detailTxStatusBadge').innerHTML = isCompleted 
        ? `<span class="bg-emerald-50 text-emerald-600 px-3 py-1 rounded-full text-xs font-semibold border border-emerald-100 inline-flex items-center gap-1"><i class="fa-solid fa-circle-check"></i> Completed</span>`
        : `<span class="bg-amber-50 text-amber-600 px-3 py-1 rounded-full text-xs font-semibold border border-amber-100 inline-flex items-center gap-1"><i class="fa-solid fa-circle-notch fa-spin"></i> Pending</span>`;
        
    document.getElementById('detailTxStatusSelect').value = tx.status;
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeTxDetailModal() {
    const modal = document.getElementById('txDetailModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

async function saveTxDetailStatus() {
    const id = document.getElementById('detailTxId').value;
    const newStatus = document.getElementById('detailTxStatusSelect').value;
    
    const btn = document.getElementById('saveTxDetailBtn');
    btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Menyimpan...';
    btn.disabled = true;

    try {
        await axios.put(`/api/transactions/${id}`, {
            status: newStatus
        });

        alert('Status transaksi berhasil diubah!');
        closeTxDetailModal();
        renderTransactions();
        if (typeof loadDashboardData === 'function') {
            loadDashboardData();
        }
    } catch (err) {
        console.error(err);
        const errMsg = err.response && err.response.data && err.response.data.message 
            ? err.response.data.message 
            : 'Gagal mengubah status transaksi';
        alert(errMsg);
    } finally {
        btn.innerHTML = 'Simpan Perubahan';
        btn.disabled = false;
    }
}


// --- Integrated Shipping Functions ---

function updateShippingWeight() {
    const select = document.getElementById('txItemSelect');
    const qtyInput = document.getElementById('txQuantityInput');
    const weightInput = document.getElementById('txShipWeight');
    
    if (select && select.selectedIndex > 0) {
        const option = select.options[select.selectedIndex];
        const unitWeight = parseInt(option.getAttribute('data-weight')) || 1000;
        const qty = parseInt(qtyInput.value) || 1;
        weightInput.value = unitWeight * qty;
    }
}

document.getElementById('txItemSelect')?.addEventListener('change', updateShippingWeight);
document.getElementById('txQuantityInput')?.addEventListener('input', updateShippingWeight);

function toggleTxShipping() {
    const type = document.getElementById('txTypeSelect').value;
    const wrapper = document.getElementById('txShippingWrapper');
    
    if (type === 'out') {
        wrapper.classList.remove('hidden');
        wrapper.classList.add('flex');
    } else {
        wrapper.classList.add('hidden');
        wrapper.classList.remove('flex');
    }
}

// Biteship Area Autocomplete
let searchTimeout = null;
const txDestSearch = document.getElementById('txDestSearch');
const txDestResults = document.getElementById('txDestResults');
const txDestAreaId = document.getElementById('txDestAreaId');

if (txDestSearch) {
    txDestSearch.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const query = this.value;
        
        if (query.length < 3) {
            txDestResults.classList.add('hidden');
            return;
        }

        searchTimeout = setTimeout(async () => {
            txDestResults.innerHTML = '<div class="p-3 text-xs text-gray-500 text-center"><i class="fa-solid fa-circle-notch fa-spin"></i> Mencari...</div>';
            txDestResults.classList.remove('hidden');
            try {
                const res = await axios.get(`/api/proxy/areas?q=${encodeURIComponent(query)}`);
                const areas = res.data.data;
                
                if (areas.length === 0) {
                    txDestResults.innerHTML = '<div class="p-3 text-xs text-gray-500 text-center">Area tidak ditemukan</div>';
                    return;
                }

                let html = '';
                areas.forEach(area => {
                    html += `
                        <div class="p-3 text-xs border-b border-gray-50 hover:bg-gray-50 cursor-pointer" 
                             onclick="selectArea('${area.id}', '${area.name}')">
                            <div class="font-bold text-gray-800">${area.name}</div>
                        </div>
                    `;
                });
                txDestResults.innerHTML = html;
            } catch (e) {
                txDestResults.innerHTML = '<div class="p-3 text-xs text-red-500 text-center">Gagal memuat API</div>';
            }
        }, 500);
    });
}

function selectArea(id, name) {
    txDestAreaId.value = id;
    txDestSearch.value = name;
    txDestResults.classList.add('hidden');
}

// Hide dropdown when clicking outside
document.addEventListener('click', function(e) {
    if (txDestSearch && txDestResults && !txDestSearch.contains(e.target) && !txDestResults.contains(e.target)) {
        txDestResults.classList.add('hidden');
    }
});

async function calculateTxShipping() {
    const btn = document.getElementById('btnCalcTxShipping');
    const resContainer = document.getElementById('txShippingResults');
    
    // Default origin to Malang (Lowokwaru - Jl. Sunan Kalijaga / Sigura-gura)
    const origin = "IDNP11IDNC250IDND2618IDZ65145";
    
    const dest = document.getElementById('txDestAreaId').value;
    const weight = document.getElementById('txShipWeight').value;
    const courier = document.getElementById('txShipCourier').value;
    
    if(!dest || !weight || !courier) {
        alert("Pilih lokasi tujuan dari dropdown hasil pencarian!");
        return;
    }
    
    btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Loading...';
    btn.disabled = true;
    resContainer.classList.add('hidden');
    
    try {
        const res = await axios.post('/api/proxy/shipping-cost', {
            origin, destination: dest, weight, courier
        });
        
        let html = '';
        const results = res.data.data[0];
        if (results && results.costs && results.costs.length > 0) {
            results.costs.forEach(cost => {
                const costData = cost.cost[0];
                const costStr = Number(costData.value).toLocaleString('id-ID');
                const serviceName = `${results.name} - ${cost.service}`;
                html += `
                <div class="border border-gray-200 rounded-lg p-2 flex justify-between items-center bg-white hover:border-accent transition cursor-pointer" onclick="selectTxShippingCost('${serviceName}', '${costStr}')">
                    <div>
                        <div class="font-bold text-gray-900 text-[10px]">${serviceName}</div>
                        <div class="text-[9px] text-gray-500">${cost.description} (EST: ${costData.etd} Hari)</div>
                    </div>
                    <div class="text-xs font-bold text-accent">
                        Rp ${costStr}
                    </div>
                </div>
                `;
            });
        } else {
            html += '<div class="text-gray-500 italic text-[10px] text-center py-2">No services available.</div>';
        }
        
        resContainer.innerHTML = html;
        resContainer.classList.remove('hidden');
        resContainer.classList.add('flex');
    } catch (e) {
        console.error("API Error:", e);
        resContainer.innerHTML = `<div class="text-red-500 text-[10px] text-center py-2">Gagal menghitung ongkir. API Key bermasalah atau limit habis.</div>`;
        resContainer.classList.remove('hidden');
        resContainer.classList.add('flex');
        alert('Gagal mengambil tarif pengiriman dari API asli.');
    } finally {
        btn.innerHTML = 'Check Shipping Cost';
        btn.disabled = false;
    }
}

function selectTxShippingCost(serviceName, costStr) {
    const notesInput = document.getElementById('txNotesInput');
    const shipText = `[Shipping: ${serviceName} - Rp ${costStr}]`;
    
    if (notesInput.value) {
        notesInput.value += `\n${shipText}`;
    } else {
        notesInput.value = shipText;
    }
    
    // Highlight effect
    notesInput.classList.add('ring-2', 'ring-accent', 'ring-offset-1');
    setTimeout(() => {
        notesInput.classList.remove('ring-2', 'ring-accent', 'ring-offset-1');
    }, 1000);
}
