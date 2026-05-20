let activePartnerTab = 'suppliers';
const mockSuppliers = [
    { name: 'NVIDIA Corp', address: 'Silicon Valley, California, USA', phone: '+1-555-0199', category: 'GPU' },
    { name: 'AMD Corp', address: 'Santa Clara, California, USA', phone: '+1-555-0120', category: 'CPU' },
    { name: 'Corsair Memory', address: 'Fremont, California, USA', phone: '+1-555-0143', category: 'RAM & PSU' },
    { name: 'Samsung Corp', address: 'Suwon, South Korea', phone: '+82-2-1234-5678', category: 'Storage' },
    { name: 'ASUS Global', address: 'Beitou District, Taipei, Taiwan', phone: '+886-2-8143-7575', category: 'Motherboard' },
    { name: 'NZXT Corp', address: 'Los Angeles, California, USA', phone: '+1-555-0177', category: 'Case & Cooling' }
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
        tabSup.className = 'pb-3 border-b-2 border-accent font-semibold text-accent';
        tabCust.className = 'pb-3 border-b-2 border-transparent font-medium text-gray-500 hover:text-gray-800';
    } else {
        tabCust.className = 'pb-3 border-b-2 border-accent font-semibold text-accent';
        tabSup.className = 'pb-3 border-b-2 border-transparent font-medium text-gray-500 hover:text-gray-800';
    }
    loadPartners();
}

function loadPartners() {
    const container = document.getElementById('partnerContainer');
    if (!container) return;
    container.innerHTML = '';
    
    if (activePartnerTab === 'suppliers') {
        let html = `
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="text-gray-400 text-xs border-b border-gray-100 pb-4">
                        <th class="pb-4 font-medium px-2">Supplier Name</th>
                        <th class="pb-4 font-medium">Provided Category</th>
                        <th class="pb-4 font-medium">Phone</th>
                        <th class="pb-4 font-medium">Address</th>
                    </tr>
                </thead>
                <tbody>
        `;
        mockSuppliers.forEach(sup => {
            html += `
                <tr class="border-b border-gray-50 hover:bg-gray-50 transition text-xs">
                    <td class="py-4 px-2 font-semibold text-gray-800">${sup.name}</td>
                    <td class="py-4"><span class="px-2.5 py-1 rounded bg-violet-50 text-accent font-medium border border-violet-100">${sup.category}</span></td>
                    <td class="py-4 text-gray-600 font-mono">${sup.phone}</td>
                    <td class="py-4 text-gray-500">${sup.address}</td>
                </tr>
            `;
        });
        html += '</tbody></table>';
        container.innerHTML = html;
    } else {
        let html = `
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="text-gray-400 text-xs border-b border-gray-100 pb-4">
                        <th class="pb-4 font-medium px-2">Customer Name</th>
                        <th class="pb-4 font-medium">Type</th>
                        <th class="pb-4 font-medium">Phone</th>
                        <th class="pb-4 font-medium">Location</th>
                    </tr>
                </thead>
                <tbody>
        `;
        mockCustomers.forEach(cust => {
            html += `
                <tr class="border-b border-gray-50 hover:bg-gray-50 transition text-xs">
                    <td class="py-4 px-2 font-semibold text-gray-800">${cust.name}</td>
                    <td class="py-4"><span class="px-2.5 py-1 rounded bg-blue-50 text-blue-600 font-medium border border-blue-100">${cust.type}</span></td>
                    <td class="py-4 text-gray-600 font-mono">${cust.phone}</td>
                    <td class="py-4 text-gray-500">${cust.location}</td>
                </tr>
            `;
        });
        html += '</tbody></table>';
        container.innerHTML = html;
    }
}
