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
                <button onclick="toggleTransactionStatus(${tx.id}, '${tx.status}')" class="text-gray-400 hover:text-accent font-medium text-xs flex items-center justify-center bg-gray-50 border border-gray-200 w-6 h-6 rounded-md shadow-sm transition" title="Ubah Status">
                    <i class="fa-solid fa-arrows-rotate text-[10px]"></i>
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
                    select.innerHTML += `<option value="${item.id}">${item.name} (${item.sku}) - Stock: ${item.stock}</option>`;
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

async function toggleTransactionStatus(id, currentStatus) {
    const newStatus = currentStatus === 'completed' ? 'pending' : 'completed';
    const confirmMsg = `Ubah status transaksi #${id} menjadi ${newStatus.toUpperCase()}?`;
    if (!confirm(confirmMsg)) return;

    try {
        await axios.put(`/api/transactions/${id}`, {
            status: newStatus
        });

        alert('Status transaksi berhasil diubah!');
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
    }
}

// --- Integrated Shipping Functions ---

function toggleTxShipping() {
    const type = document.getElementById('txTypeSelect').value;
    const wrapper = document.getElementById('txShippingWrapper');
    
    if (type === 'out') {
        wrapper.classList.remove('hidden');
        wrapper.classList.add('flex');
        
        // Load provinces if not loaded yet
        const provSelect = document.getElementById('txDestProvince');
        if (provSelect.options.length <= 1) {
            loadTxProvinces();
        }
    } else {
        wrapper.classList.add('hidden');
        wrapper.classList.remove('flex');
    }
}

async function loadTxProvinces() {
    const provSelect = document.getElementById('txDestProvince');
    try {
        const res = await axios.get('/api/proxy/provinces');
        let options = '<option value="">Select Province...</option>';
        res.data.data.forEach(p => {
            options += `<option value="${p.province_id}">${p.province}</option>`;
        });
        provSelect.innerHTML = options;
    } catch (e) {
        console.error('API Error:', e);
        provSelect.innerHTML = '<option value="">Failed to load API Provinces</option>';
        alert('Gagal mengambil data Provinsi dari API asli. Periksa API Key Anda.');
    }
}

async function loadTxCities(provinceId) {
    const citySelect = document.getElementById('txDestCity');
    if (!provinceId) {
        citySelect.innerHTML = '<option value="">Select City...</option>';
        return;
    }
    try {
        const res = await axios.get(`/api/proxy/cities?province=${provinceId}`);
        let options = '<option value="">Select City...</option>';
        res.data.data.forEach(c => {
            options += `<option value="${c.city_id}">${c.type} ${c.city_name}</option>`;
        });
        citySelect.innerHTML = options;
    } catch (e) {
        console.error('API Error:', e);
        citySelect.innerHTML = '<option value="">Failed to load API Cities</option>';
        alert('Gagal mengambil data Kota dari API asli. Periksa API Key Anda.');
    }
}

async function calculateTxShipping() {
    const btn = document.getElementById('btnCalcTxShipping');
    const resContainer = document.getElementById('txShippingResults');
    
    // Default origin to Jakarta Selatan (153)
    const origin = "153"; 
    
    const dest = document.getElementById('txDestCity').value;
    const weight = document.getElementById('txShipWeight').value;
    const courier = document.getElementById('txShipCourier').value;
    
    if(!dest || !weight || !courier) {
        alert("Please fill destination city, weight, and courier!");
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
