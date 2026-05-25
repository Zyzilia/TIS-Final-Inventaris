let activePartnerTab = 'suppliers';
const mockSuppliers = [
    { name: 'NVIDIA Corp', address: 'Silicon Valley, California, USA', phone: '+1-555-0199', category: 'GPU' },
    { name: 'AMD Corp', address: 'Santa Clara, California, USA', phone: '+1-555-0120', category: 'CPU' },
    { name: 'Corsair Memory', address: 'Fremont, California, USA', phone: '+1-555-0143', category: 'RAM & PSU' },
    { name: 'Samsung Corp', address: 'Suwon, South Korea', phone: '+82-2-1234-5678', category: 'Storage' },
    { name: 'ASUS Global', address: 'Beitou District, Taipei, Taiwan', phone: '+886-2-8143-7575', category: 'Motherboard' },
    { name: 'NZXT Corp', address: 'Los Angeles, California, USA', phone: '+1-555-0177', category: 'Case & Cooling' },
    { name: 'Intel Corp', address: 'Santa Clara, California, USA', phone: '+1-555-0150', category: 'CPU & GPU' }
];
const mockCustomers = [
    { name: 'Quantum PC Shop', type: 'Wholesale Distributor', phone: '+62-812-3456-7890', location: 'Jakarta, Indonesia' },
    { name: 'Toko Abadi Jaya', type: 'Retail Store', phone: '+62-821-9876-5432', location: 'Bandung, Indonesia' },
    { name: 'Medan Tech Store', type: 'Retail Store', phone: '+62-853-1111-2222', location: 'Medan, Indonesia' },
    { name: 'Surya Komputer', type: 'Wholesale Distributor', phone: '+62-878-3333-4444', location: 'Surabaya, Indonesia' }
];

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
                const suppliers = res.data.data;
                renderSuppliersList(suppliers);
            })
            .catch(err => {
                console.error(err);
                renderSuppliersList(mockSuppliers);
            });
    } else {
        container.innerHTML = `<div class="text-center py-12 text-gray-400 font-medium"><i class="fa-solid fa-circle-notch fa-spin text-xl text-primary-500 mb-3"></i><br>Syncing customer database...</div>`;
        axios.get('/api/customers')
            .then(res => {
                const customers = res.data.data;
                renderCustomersList(customers);
            })
            .catch(err => {
                console.error(err);
                renderCustomersList(mockCustomers);
            });
    }
}

function renderSuppliersList(suppliersList) {
    const container = document.getElementById('partnerContainer');
    if (!container) return;

    let html = `
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50/50 sticky top-0 z-10 backdrop-blur-sm">
                <tr class="text-gray-400 text-[11px] font-bold uppercase tracking-wider border-b border-gray-100">
                    <th class="py-4 px-8">Supplier Name</th>
                    <th class="py-4 px-4">Provided Category</th>
                    <th class="py-4 px-4">Phone Contact</th>
                    <th class="py-4 px-8">Address</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
    `;
    suppliersList.forEach(sup => {
        html += `
            <tr class="hover:bg-primary-50/30 transition-colors group">
                <td class="py-5 px-8 font-extrabold text-gray-900">${sup.name}</td>
                <td class="py-5 px-4"><span class="px-3 py-1 rounded-lg bg-primary-50 text-primary-600 font-bold border border-primary-100 text-xs">${sup.category || 'Components'}</span></td>
                <td class="py-5 px-4 text-gray-600 font-mono text-xs font-bold">${sup.phone}</td>
                <td class="py-5 px-8 text-gray-500 font-medium text-xs">${sup.address}</td>
            </tr>
        `;
    });
    html += '</tbody></table>';
    container.innerHTML = html;
}

function renderCustomersList(customersList) {
    const container = document.getElementById('partnerContainer');
    if (!container) return;

    let html = `
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50/50 sticky top-0 z-10 backdrop-blur-sm">
                <tr class="text-gray-400 text-[11px] font-bold uppercase tracking-wider border-b border-gray-100">
                    <th class="py-4 px-8">Customer Name</th>
                    <th class="py-4 px-4">Account Type</th>
                    <th class="py-4 px-4">Phone Contact</th>
                    <th class="py-4 px-8">Base Location</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
    `;
    customersList.forEach(cust => {
        html += `
            <tr class="hover:bg-primary-50/30 transition-colors group">
                <td class="py-5 px-8 font-extrabold text-gray-900">${cust.name}</td>
                <td class="py-5 px-4"><span class="px-3 py-1 rounded-lg bg-blue-50 text-blue-600 font-bold border border-blue-100 text-xs">${cust.type}</span></td>
                <td class="py-5 px-4 text-gray-600 font-mono text-xs font-bold">${cust.phone}</td>
                <td class="py-5 px-8 text-gray-500 font-medium text-xs">${cust.location}</td>
            </tr>
        `;
    });
    html += '</tbody></table>';
    container.innerHTML = html;
}

function openPartnerModal() {
    const modal = document.getElementById('partnerModal');
    if (!modal) return;

    const title = document.getElementById('partnerModalTitle');
    const nameLabel = document.getElementById('partnerNameLabel');
    const locationLabel = document.getElementById('partnerLocationLabel');
    const typeWrapper = document.getElementById('partnerTypeWrapper');

    if (activePartnerTab === 'suppliers') {
        title.textContent = 'Add New Supplier';
        nameLabel.textContent = 'Supplier Name';
        locationLabel.textContent = 'Address';
        typeWrapper.classList.add('hidden');
    } else {
        title.textContent = 'Add New Customer';
        nameLabel.textContent = 'Customer Name';
        locationLabel.textContent = 'Location / City';
        typeWrapper.classList.remove('hidden');
    }

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closePartnerModal() {
    const modal = document.getElementById('partnerModal');
    if (!modal) return;
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.getElementById('partnerForm').reset();
}

async function submitPartner(event) {
    event.preventDefault();

    const name = document.getElementById('partnerNameInput').value;
    const phone = document.getElementById('partnerPhoneInput').value;
    const locationOrAddress = document.getElementById('partnerLocationInput').value;
    
    let url = '/api/suppliers';
    let data = {
        name: name,
        phone: phone,
        address: locationOrAddress
    };

    if (activePartnerTab === 'customers') {
        const type = document.getElementById('partnerTypeSelect').value;
        url = '/api/customers';
        data = {
            name: name,
            type: type,
            phone: phone,
            location: locationOrAddress
        };
    }

    try {
        await axios.post(url, data);
        alert(activePartnerTab === 'suppliers' ? 'Supplier berhasil ditambahkan!' : 'Customer berhasil ditambahkan!');
        closePartnerModal();
        loadPartners();
    } catch (err) {
        console.error(err);
        const errMsg = err.response && err.response.data && err.response.data.message 
            ? err.response.data.message 
            : 'Gagal menyimpan partner';
        alert(errMsg);
    }
}
