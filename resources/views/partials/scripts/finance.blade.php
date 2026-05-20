let liveRate = 16100;

async function fetchExchangeRate() {
    const label = document.getElementById('financeRateLabel');
    if(!label) return;

    const useManual = localStorage.getItem('settings_use_manual_rate') === 'true';
    const manualRate = localStorage.getItem('settings_manual_rate');

    if (useManual && manualRate) {
        liveRate = Number(manualRate);
        label.textContent = `Rp ${liveRate.toLocaleString('id-ID')} / USD (Manual)`;
        runFinanceConverter();
        return;
    }

    try {
        const res = await axios.get('/api/proxy/currency-rates');
        if (res.data && res.data.success && res.data.data.rates.IDR) {
            liveRate = res.data.data.rates.IDR;
            label.textContent = `Rp ${Number(liveRate).toLocaleString('id-ID')} / USD`;
        }
    } catch (e) {
        console.warn("Failed to fetch live currency rates, using fallback: Rp 16,100");
        label.textContent = `Rp 16,100 / USD (Mock Mode)`;
    }
    runFinanceConverter();
}

function runFinanceConverter() {
    const usd = document.getElementById('financeUsdInput').value || 0;
    const output = document.getElementById('financeIdrOutput');
    if (!output) return;
    const converted = usd * liveRate;
    output.value = `Rp ${converted.toLocaleString('id-ID', {maximumFractionDigits: 0})}`;
}
