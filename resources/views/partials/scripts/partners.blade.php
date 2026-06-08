let activePartnerTab = 'suppliers';
let currentSuppliers = [];
let currentCustomers = [];

function switchPartnerTab(tabName) {
    activePartnerTab = tabName;
    
    const tabSup = document.getElementById('partnerTabSuppliers');
    const tabCust = document.getElementById('partnerTabCustomers');
    if(!tabSup || !tabCust) return;
    
    if (tabName === 'suppliers') {
        tabSup.className = 'pb-4 border-b-2 border-primary-600 font-extrabold text-primary-600 text-sm transition-all';
        tabCust.className = 'pb-4 border-b-2 border-transparent font-bold text-gray-500 hover:text-gray-800 text-sm transition-all';
    } else {
        tabCust.className = 'pb-4 border-b-2 border-primary-600 font-extrabold text-primary-600 text-sm transition-all';
        tabSup.className = 'pb-4 border-b-2 border-transparent font-bold text-gray-500 hover:text-gray-800 text-sm transition-all';
    }

    const addBtn = document.getElementById('addPartnerBtn');
    if (addBtn) {
        addBtn.innerHTML = tabName === 'suppliers' 
            ? '<i class="fa-solid fa-plus text-xs"></i> Add Supplier'
            : '<i class="fa-solid fa-plus text-xs"></i> Add Customer';
    }

    loadPartners();
}

function loadPartners() {
    const container = document.getElementById('partnerContainer');
    if (!container) return;
    container.innerHTML = '';
    
    if (activePartnerTab === 'suppliers') {
        container.innerHTML = `<div class="text-center py-12 text-gray-400 font-medium"><i class="fa-solid fa-circle-notch fa-spin text-xl text-primary-500 mb-3"></i><br>Syncing supplier data...</div>`;
        axios.get('/api/suppliers')
            .then(res => {
                currentSuppliers = res.data.data;
                renderSuppliersList(currentSuppliers);
            })
            .catch(err => {
                console.error(err);
                container.innerHTML = `<div class="text-center py-12 text-red-500 font-medium">Gagal memuat data supplier</div>`;
            });
    } else {
        container.innerHTML = `<div class="text-center py-12 text-gray-400 font-medium"><i class="fa-solid fa-circle-notch fa-spin text-xl text-primary-500 mb-3"></i><br>Syncing customer database...</div>`;
        axios.get('/api/customers')
            .then(res => {
                currentCustomers = res.data.data;
                renderCustomersList(currentCustomers);
            })
            .catch(err => {
                console.error(err);
                container.innerHTML = `<div class="text-center py-12 text-red-500 font-medium">Gagal memuat data customer</div>`;
            });
    }
}

function renderSuppliersList(suppliersList) {
    const container = document.getElementById('partnerContainer');
    if (!container) return;

    // Check for admin role to show/hide actions
    const userStr = localStorage.getItem('user_data');
    const user = userStr ? JSON.parse(userStr) : null;
    const isAdmin = user && user.role === 'admin';

    let html = `
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50/50 sticky top-0 z-10 backdrop-blur-sm">
                <tr class="text-gray-400 text-[11px] font-bold uppercase tracking-wider border-b border-gray-100">
                    <th class="py-4 px-8">Supplier Name</th>
                    <th class="py-4 px-4">Provided Category</th>
                    <th class="py-4 px-4">Phone Contact</th>
                    <th class="py-4 px-4">Address</th>
                    <th class="py-4 px-8 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
    `;
    suppliersList.forEach(sup => {
        const actionButtons = isAdmin ? `
            <button onclick="openPartnerModal(${sup.id})" class="text-primary-600 hover:text-white bg-white hover:bg-primary-600 border border-gray-200 hover:border-primary-600 w-8 h-8 rounded-lg shadow-sm transition-all" title="Edit Partner"><i class="fa-solid fa-pen text-xs"></i></button>
            <button onclick="deletePartner(${sup.id})" class="text-red-500 hover:text-white bg-white hover:bg-red-500 border border-gray-200 hover:border-red-500 w-8 h-8 rounded-lg shadow-sm transition-all" title="Delete Partner"><i class="fa-solid fa-trash text-xs"></i></button>
        ` : `<span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Locked</span>`;

        html += `
            <tr class="hover:bg-primary-50/30 transition-colors group text-sm">
                <td class="py-4 px-8 font-extrabold text-gray-900">${sup.name}</td>
                <td class="py-4 px-4"><span class="px-3 py-1 rounded-lg bg-primary-50 text-primary-600 font-bold border border-primary-100 text-[10px] uppercase">${sup.category || 'Components'}</span></td>
                <td class="py-4 px-4 text-gray-600 font-mono text-xs font-bold">${sup.phone}</td>
                <td class="py-4 px-4 text-gray-500 font-medium text-xs">${sup.address}</td>
                <td class="py-4 px-8 text-right space-x-2">
                    ${actionButtons}
                </td>
            </tr>
        `;
    });
    if(suppliersList.length === 0) {
        html += `<tr><td colspan="5" class="text-center py-12 text-gray-400 font-medium italic">Belum ada data supplier</td></tr>`;
    }
    html += '</tbody></table>';
    container.innerHTML = html;
}

function renderCustomersList(customersList) {
    const container = document.getElementById('partnerContainer');
    if (!container) return;

    // Check for admin role to show/hide actions
    const userStr = localStorage.getItem('user_data');
    const user = userStr ? JSON.parse(userStr) : null;
    const isAdmin = user && user.role === 'admin';

    let html = `
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50/50 sticky top-0 z-10 backdrop-blur-sm">
                <tr class="text-gray-400 text-[11px] font-bold uppercase tracking-wider border-b border-gray-100">
                    <th class="py-4 px-8">Customer Name</th>
                    <th class="py-4 px-4">Account Type</th>
                    <th class="py-4 px-4">Phone Contact</th>
                    <th class="py-4 px-4">Base Location</th>
                    <th class="py-4 px-8 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
    `;
    customersList.forEach(cust => {
        const actionButtons = isAdmin ? `
            <button onclick="openPartnerModal(${cust.id})" class="text-primary-600 hover:text-white bg-white hover:bg-primary-600 border border-gray-200 hover:border-primary-600 w-8 h-8 rounded-lg shadow-sm transition-all" title="Edit Partner"><i class="fa-solid fa-pen text-xs"></i></button>
            <button onclick="deletePartner(${cust.id})" class="text-red-500 hover:text-white bg-white hover:bg-red-500 border border-gray-200 hover:border-red-500 w-8 h-8 rounded-lg shadow-sm transition-all" title="Delete Partner"><i class="fa-solid fa-trash text-xs"></i></button>
        ` : `<span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Locked</span>`;

        html += `
            <tr class="hover:bg-primary-50/30 transition-colors group text-sm">
                <td class="py-4 px-8 font-extrabold text-gray-900">${cust.name}</td>
                <td class="py-4 px-4"><span class="px-3 py-1 rounded-lg bg-blue-50 text-blue-600 font-bold border border-blue-100 text-[10px] uppercase">${cust.type}</span></td>
                <td class="py-4 px-4 text-gray-600 font-mono text-xs font-bold">${cust.phone}</td>
                <td class="py-4 px-4 text-gray-500 font-medium text-xs">${cust.location}</td>
                <td class="py-4 px-8 text-right space-x-2">
                    ${actionButtons}
                </td>
            </tr>
        `;
    });
    if(customersList.length === 0) {
        html += `<tr><td colspan="5" class="text-center py-12 text-gray-400 font-medium italic">Belum ada data customer</td></tr>`;
    }
    html += '</tbody></table>';
    container.innerHTML = html;
}

function openPartnerModal(id = null) {
    const modal = document.getElementById('partnerModal');
    if (!modal) return;

    const form = document.getElementById('partnerForm');
    form.reset();
    document.getElementById('partnerId').value = '';

    const title = document.getElementById('partnerModalTitle');
    const nameLabel = document.getElementById('partnerNameLabel');
    const locationLabel = document.getElementById('partnerLocationLabel');
    const typeWrapper = document.getElementById('partnerTypeWrapper');

    if (activePartnerTab === 'suppliers') {
        title.textContent = id ? 'Edit Supplier' : 'Add New Supplier';
        nameLabel.textContent = 'Supplier Name';
        locationLabel.textContent = 'Address';
        typeWrapper.classList.add('hidden');
        
        if (id) {
            const sup = currentSuppliers.find(s => s.id === id);
            if (sup) {
                document.getElementById('partnerId').value = sup.id;
                document.getElementById('partnerNameInput').value = sup.name;
                document.getElementById('partnerPhoneInput').value = sup.phone;
                document.getElementById('partnerLocationInput').value = sup.address;
            }
        }
    } else {
        title.textContent = id ? 'Edit Customer' : 'Add New Customer';
        nameLabel.textContent = 'Customer Name';
        locationLabel.textContent = 'Location / City';
        typeWrapper.classList.remove('hidden');
        
        if (id) {
            const cust = currentCustomers.find(c => c.id === id);
            if (cust) {
                document.getElementById('partnerId').value = cust.id;
                document.getElementById('partnerNameInput').value = cust.name;
                document.getElementById('partnerPhoneInput').value = cust.phone;
                document.getElementById('partnerLocationInput').value = cust.location;
                document.getElementById('partnerTypeSelect').value = cust.type;
            }
        }
    }

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closePartnerModal() {
    const modal = document.getElementById('partnerModal');
    if (!modal) return;
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

async function submitPartner(event) {
    event.preventDefault();

    const id = document.getElementById('partnerId').value;
    const name = document.getElementById('partnerNameInput').value;
    const phone = document.getElementById('partnerPhoneInput').value;
    const locationOrAddress = document.getElementById('partnerLocationInput').value;
    
    let url = activePartnerTab === 'suppliers' ? '/api/suppliers' : '/api/customers';
    let data = {
        name: name,
        phone: phone,
    };

    if (activePartnerTab === 'suppliers') {
        data.address = locationOrAddress;
    } else {
        data.location = locationOrAddress;
        data.type = document.getElementById('partnerTypeSelect').value;
    }

    try {
        if (id) {
            await axios.put(`${url}/${id}`, data);
            alert('Data partner berhasil diperbarui!');
        } else {
            await axios.post(url, data);
            alert('Partner berhasil ditambahkan!');
        }
        closePartnerModal();
        loadPartners();
        if (typeof loadDashboardData === 'function') loadDashboardData();
    } catch (err) {
        console.error(err);
        const errMsg = err.response && err.response.data && err.response.data.message 
            ? err.response.data.message 
            : 'Gagal menyimpan partner';
        alert(errMsg);
    }
}

async function deletePartner(id) {
    if (!confirm('Apakah Anda yakin ingin menghapus partner ini?')) return;

    const url = activePartnerTab === 'suppliers' ? `/api/suppliers/${id}` : `/api/customers/${id}`;

    try {
        await axios.delete(url);
        alert('Partner berhasil dihapus!');
        loadPartners();
        if (typeof loadDashboardData === 'function') loadDashboardData();
    } catch (err) {
        console.error(err);
        const errMsg = err.response && err.response.data && err.response.data.message 
            ? err.response.data.message 
            : 'Gagal menghapus partner';
        alert(errMsg);
    }
}