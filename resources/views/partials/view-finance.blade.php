<!-- VIEW 4: Finance -->
<div id="view-finance" class="flex-1 flex flex-col p-8 overflow-y-auto hidden">
    <header class="flex justify-between items-end mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Finance & Kurs Gateway</h1>
            <p class="text-gray-500 text-sm mt-1">Currency conversion metrics for international supplies</p>
        </div>
        @include('partials.header-actions')
    </header>

    <div class="grid grid-cols-3 gap-6">
        <div class="col-span-2 bg-white p-8 rounded-[2rem] shadow-sm flex flex-col justify-between">
            <div>
                <h3 class="font-bold text-gray-800 text-lg mb-6">Exchange Rate Converter</h3>
                <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100 flex flex-col gap-4">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600 font-medium">Official Rate:</span>
                        <span class="text-2xl font-bold text-accent" id="financeRateLabel">Rp 16,100 / USD</span>
                    </div>
                    <div class="text-xs text-gray-400">Live feed from Open Exchange Rates API. Cached automatically.</div>
                    <hr class="border-gray-200 my-2">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1">USD Amount ($)</label>
                            <input type="number" id="financeUsdInput" value="100" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:border-accent" oninput="runFinanceConverter()">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1">IDR Amount (Rp)</label>
                            <input type="text" id="financeIdrOutput" readonly class="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-xl outline-none font-semibold text-gray-800">
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-6">
                <h4 class="font-semibold text-gray-700 text-sm mb-3">Profit Margin Policy</h4>
                <p class="text-xs text-gray-500 leading-relaxed">
                    Base components are imported using USD supply price. The final sale price is computed as follows:<br>
                    <span class="font-mono text-accent">Final Price (IDR) = (Base USD * Live Exchange Rate) * (1 + Margin %)</span>
                </p>
            </div>
        </div>
        <div class="bg-white p-8 rounded-[2rem] shadow-sm flex flex-col justify-between">
            <div>
                <h3 class="font-bold text-gray-800 text-lg mb-4">Live API Stats</h3>
                <div class="space-y-4 text-sm mt-6">
                    <div class="flex justify-between pb-2 border-b border-gray-50">
                        <span class="text-gray-500">API Status</span>
                        <span class="font-semibold text-green-500 flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-green-500"></span> Online</span>
                    </div>
                    <div class="flex justify-between pb-2 border-b border-gray-50">
                        <span class="text-gray-500">Base Base</span>
                        <span class="font-semibold">USD</span>
                    </div>
                    <div class="flex justify-between pb-2 border-b border-gray-50">
                        <span class="text-gray-500">Target Currency</span>
                        <span class="font-semibold">IDR</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Cache TTL</span>
                        <span class="font-semibold">24 Hours</span>
                    </div>
                </div>
            </div>
            <button onclick="fetchExchangeRate()" class="w-full bg-accent text-white font-semibold py-3 rounded-xl hover:bg-violet-600 transition mt-6">Force Refresh Rates</button>
        </div>
    </div>
</div>
