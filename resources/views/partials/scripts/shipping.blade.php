// Shipping Modal Logic
const shippingModal = document.getElementById('shippingModal');

// Shipping Mock Data Fallback
const mockProvinces = [
    { province_id: "6", province: "DKI Jakarta" },
    { province_id: "9", province: "Jawa Barat" },
    { province_id: "10", province: "Jawa Tengah" },
    { province_id: "11", province: "Jawa Timur" },
    { province_id: "3", province: "Banten" }
];

const mockCities = {
    "6": [
        { city_id: "153", city_name: "Jakarta Selatan", type: "Kota" },
        { city_id: "152", city_name: "Jakarta Pusat", type: "Kota" },
        { city_id: "151", city_name: "Jakarta Barat", type: "Kota" }
    ],
    "9": [
        { city_id: "23", city_name: "Bandung", type: "Kota" },
        { city_id: "54", city_name: "Bogor", type: "Kabupaten" },
        { city_id: "115", city_name: "Depok", type: "Kota" }
    ],
    "10": [
        { city_id: "399", city_name: "Semarang", type: "Kota" },
        { city_id: "445", city_name: "Surakarta", type: "Kota" }
    ],
    "11": [
        { city_id: "444", city_name: "Surabaya", type: "Kota" },
        { city_id: "255", city_name: "Malang", type: "Kabupaten" }
    ],
    "3": [
        { city_id: "457", city_name: "Tangerang", type: "Kota" },
        { city_id: "402", city_name: "Serang", type: "Kota" }
    ]
};

const mockRates = {
    "jne": [
        { name: "Jalur Nugraha Ekakurir (JNE)", service: "REG", description: "Layanan Reguler", cost: [{ value: 12000, etd: "2-3" }] },
        { name: "Jalur Nugraha Ekakurir (JNE)", service: "YES", description: "Yakin Esok Sampai", cost: [{ value: 22000, etd: "1" }] },
        { name: "Jalur Nugraha Ekakurir (JNE)", service: "OKE", description: "Ongkos Kirim Ekonomis", cost: [{ value: 9000, etd: "4-5" }] }
    ],
    "pos": [
        { name: "POS Indonesia", service: "Pos Reguler", description: "Pos Reguler Hantaran", cost: [{ value: 10000, etd: "3-5" }] },
        { name: "POS Indonesia", service: "Pos Nextday", description: "Pos Sameday/Nextday Hantaran", cost: [{ value: 20000, etd: "1" }] }
    ],
    "tiki": [
        { name: "Titipan Kilat (TIKI)", service: "REG", description: "Regular Service", cost: [{ value: 11000, etd: "2-3" }] },
        { name: "Titipan Kilat (TIKI)", service: "ONS", description: "Over Night Service", cost: [{ value: 21000, etd: "1" }] }
    ]
};

async function openShippingModal() {
    shippingModal.classList.remove('hidden');
    shippingModal.classList.add('flex');
    if (document.getElementById('originProvince').options.length === 0) {
        await loadProvinces();
    }
}

function closeShippingModal() {
    shippingModal.classList.add('hidden');
    shippingModal.classList.remove('flex');
}

async function loadProvinces() {
    try {
        const res = await axios.get('/api/proxy/provinces');
        let options = '<option value="">Select Province...</option>';
        res.data.data.forEach(p => {
            options += `<option value="${p.province_id}">${p.province}</option>`;
        });
        document.getElementById('originProvince').innerHTML = options;
        document.getElementById('destProvince').innerHTML = options;
    } catch (e) {
        console.warn('RajaOngkir API offline/invalid key. Loading mock provinces instead.');
        let options = '<option value="">Select Province (Mock Mode)...</option>';
        mockProvinces.forEach(p => {
            options += `<option value="${p.province_id}">${p.province}</option>`;
        });
        document.getElementById('originProvince').innerHTML = options;
        document.getElementById('destProvince').innerHTML = options;
    }
}

async function loadCities(type, provinceId) {
    const target = type === 'origin' ? 'originCity' : 'destCity';
    if (!provinceId) {
        document.getElementById(target).innerHTML = '<option value="">Select City...</option>';
        return;
    }
    try {
        const res = await axios.get(`/api/proxy/cities?province=${provinceId}`);
        let options = '<option value="">Select City...</option>';
        res.data.data.forEach(c => {
            options += `<option value="${c.city_id}">${c.type} ${c.city_name}</option>`;
        });
        document.getElementById(target).innerHTML = options;
    } catch (e) {
        console.warn('RajaOngkir API offline/invalid key. Loading mock cities instead.');
        let options = '<option value="">Select City (Mock Mode)...</option>';
        const cities = mockCities[provinceId] || [];
        cities.forEach(c => {
            options += `<option value="${c.city_id}">${c.type} ${c.city_name}</option>`;
        });
        document.getElementById(target).innerHTML = options;
    }
}

async function calculateShipping() {
    const btn = document.getElementById('btnCalcShipping');
    const resContainer = document.getElementById('shippingResults');
    
    const origin = document.getElementById('originCity').value;
    const dest = document.getElementById('destCity').value;
    const weight = document.getElementById('shipWeight').value;
    const courier = document.getElementById('shipCourier').value;
    
    if(!origin || !dest || !weight || !courier) {
        alert("Please fill all fields");
        return;
    }
    
    btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Calculating...';
    btn.disabled = true;
    resContainer.classList.add('hidden');
    
    try {
        const res = await axios.post('/api/proxy/shipping-cost', {
            origin, destination: dest, weight, courier
        });
        
        let html = '<h4 class="font-bold text-gray-800 border-b border-gray-100 pb-2 mb-2 mt-2">Available Services</h4>';
        
        const results = res.data.data[0];
        if (results && results.costs && results.costs.length > 0) {
            results.costs.forEach(cost => {
                const costData = cost.cost[0];
                html += `
                <div class="border border-gray-200 rounded-xl p-4 flex justify-between items-center hover:border-accent transition hover:shadow-sm">
                    <div>
                        <div class="font-bold text-gray-900">${results.name} - <span class="text-accent">${cost.service}</span></div>
                        <div class="text-xs text-gray-500 mt-1">${cost.description}</div>
                        <div class="text-[10px] font-semibold text-blue-600 bg-blue-50 border border-blue-100 inline-block px-2 py-1 rounded mt-2">EST: ${costData.etd.replace('HARI', '').replace('hari', '')} Days</div>
                    </div>
                    <div class="text-lg font-bold text-gray-800">
                        Rp ${Number(costData.value).toLocaleString('id-ID')}
                    </div>
                </div>
                `;
            });
        } else {
            html += '<div class="text-gray-500 italic text-sm text-center py-4">No services available for this route.</div>';
        }
        
        resContainer.innerHTML = html;
        resContainer.classList.remove('hidden');
        resContainer.classList.add('flex');
    } catch (e) {
        console.warn("RajaOngkir calculate failed. Falling back to local mock calculator.");
        
        let html = `
            <div class="bg-amber-50 border border-amber-200 text-amber-800 rounded-xl p-3 text-xs mb-2 flex items-center gap-2">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <span>Demo Mode: Simulating rates because RajaOngkir API Key is invalid.</span>
            </div>
            <h4 class="font-bold text-gray-800 border-b border-gray-100 pb-2 mb-2 mt-2">Available Services (Simulated)</h4>
        `;
        
        const multiplier = Math.max(1, Math.ceil(weight / 1000));
        const services = mockRates[courier] || [];
        
        services.forEach(serv => {
            const costData = serv.cost[0];
            const finalCost = costData.value * multiplier;
            html += `
            <div class="border border-gray-200 rounded-xl p-4 flex justify-between items-center hover:border-accent transition hover:shadow-sm">
                <div>
                    <div class="font-bold text-gray-900">${serv.name} - <span class="text-accent">${serv.service}</span></div>
                    <div class="text-xs text-gray-500 mt-1">${serv.description}</div>
                    <div class="text-[10px] font-semibold text-blue-600 bg-blue-50 border border-blue-100 inline-block px-2 py-1 rounded mt-2">EST: ${costData.etd} Days</div>
                </div>
                <div class="text-lg font-bold text-gray-800">
                    Rp ${finalCost.toLocaleString('id-ID')}
                </div>
            </div>
            `;
        });
        
        resContainer.innerHTML = html;
        resContainer.classList.remove('hidden');
        resContainer.classList.add('flex');
    } finally {
        btn.innerHTML = 'Calculate Cost';
        btn.disabled = false;
    }
}
