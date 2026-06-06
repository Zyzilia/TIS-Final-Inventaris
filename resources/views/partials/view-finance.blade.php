<!-- VIEW 4: Finance -->
<div id="view-finance" class="h-full flex flex-col p-8 overflow-y-auto hidden">
    <header class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Finance & Exchange</h1>
            <p class="text-gray-500 font-medium mt-1">Currency conversion metrics for international procurement</p>
        </div>
        @include('partials.header-actions')
    </header>

    <div class="grid grid-cols-3 gap-6">
        <div class="col-span-2 bg-white p-8 rounded-[2rem] shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-8">
                    <h3 class="font-extrabold text-gray-900 text-xl tracking-tight">Exchange Rate Converter</h3>
                    <span class="px-3 py-1 rounded-full bg-primary-50 text-primary-600 text-[10px] font-bold uppercase tracking-widest border border-primary-100">Live API Data</span>
                </div>
                
                <div class="bg-primary-50/30 p-8 rounded-2xl border border-primary-100/50 flex flex-col gap-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-primary-600 shadow-sm border border-primary-100">
                                <i class="fa-solid fa-dollar-sign"></i>
                            </div>
                            <span class="text-gray-600 font-bold">Official Rate:</span>
                        </div>
                        <span class="text-3xl font-black text-primary-600 tracking-tight" id="financeRateLabel">Rp 16,100 / USD</span>
                    </div>
                    
                    <p class="text-xs text-gray-400 font-medium flex items-center gap-2">
                        <i class="fa-solid fa-circle-info text-primary-400"></i>
                        Data provided by Open Exchange Rates API. Cached for performance.
                    </p>
                    
                    <hr class="border-primary-100/50">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider">USD Amount ($)</label>
                            <div class="relative group">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold">$</span>
                                <input type="number" id="financeUsdInput" value="100" 
                                    class="w-full pl-8 pr-4 py-3 bg-white border border-gray-200 rounded-xl focus:outline-none focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all font-bold text-gray-900" 
                                    oninput="runFinanceConverter()">
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider">IDR Amount (Rp)</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold">Rp</span>
                                <input type="text" id="financeIdrOutput" readonly 
                                    class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl outline-none font-black text-gray-900 shadow-inner">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-10 p-6 rounded-2xl bg-gray-50 border border-gray-100">
                <h4 class="font-bold text-gray-900 text-sm mb-3 flex items-center gap-2 uppercase tracking-wider">
                    <i class="fa-solid fa-calculator text-primary-500"></i>
                    Pricing Logic
                </h4>
                <p class="text-xs text-gray-500 leading-relaxed font-medium">
                    Base components are imported using USD supply price. The final sale price is computed as follows:<br>
                    <span class="inline-block mt-3 px-3 py-2 bg-white rounded-lg border border-gray-200 font-mono text-primary-600 font-bold">
                        Sale Price (IDR) = (Base USD * Exchange Rate) * (1 + Margin %)
                    </span>
                </p>
            </div>
        </div>

        <div class="card-standard p-8 flex flex-col justify-between">
            <div>
                <h3 class="font-extrabold text-gray-900 text-xl tracking-tight mb-8">System Connectivity</h3>
                <div class="space-y-6">
                    <div class="flex items-center justify-between p-4 rounded-xl bg-green-50 border border-green-100">
                        <span class="text-gray-600 font-bold text-sm uppercase tracking-tight">API Status</span>
                        <span class="font-black text-green-600 flex items-center gap-2 text-sm">
                            <span class="relative flex h-3 w-3">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                            </span>
                            ONLINE
                        </span>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between items-center text-sm px-2">
                            <span class="text-gray-500 font-medium">Base Currency</span>
                            <span class="font-extrabold text-gray-900">USD (United States Dollar)</span>
                        </div>
                        <div class="flex justify-between items-center text-sm px-2">
                            <span class="text-gray-500 font-medium">Target Currency</span>
                            <span class="font-extrabold text-gray-900">IDR (Indonesian Rupiah)</span>
                        </div>
                        <div class="flex justify-between items-center text-sm px-2">
                            <span class="text-gray-500 font-medium">Auto Cache TTL</span>
                            <span class="font-extrabold text-gray-900">24 Hours</span>
                        </div>
                        <div class="flex justify-between items-center text-sm px-2">
                            <span class="text-gray-500 font-medium">Last Sync</span>
                            <span class="font-extrabold text-gray-900">Today, 09:41 AM</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <button onclick="fetchExchangeRate()" class="w-full bg-primary-900 text-white font-bold py-4 rounded-xl hover:bg-primary-800 transition shadow-lg shadow-primary-900/20 flex items-center justify-center gap-3">
                <i class="fa-solid fa-arrows-rotate"></i>
                Force Refresh Rates
            </button>
        </div>
    </div>
</div>
