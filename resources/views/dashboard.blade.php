@extends('layouts.app')

@section('title', 'Overview - PC Parts Dashboard')

@section('content')
    <!-- Authentication Check & Overlay -->
    <div id="authCheck" class="fixed inset-0 bg-white z-50 flex items-center justify-center flex-col gap-4">
        <i class="fa-solid fa-circle-notch fa-spin text-4xl text-primary-600"></i>
        <p class="text-gray-500 font-medium">Checking authentication...</p>
    </div>

    @include('partials.sidebar')
    
    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        <!-- Main Content -->
        <main class="flex-1 overflow-hidden relative">
            @include('partials.view-dashboard')
            @include('partials.view-transactions')
            @include('partials.view-warehouse')
            @include('partials.view-finance')
            @include('partials.view-partners')
        </main>
    </div>

    @include('partials.modals')
@endsection

@section('scripts')
    <script>
        @include('partials.scripts.auth')
        @include('partials.scripts.navigation')
        @include('partials.scripts.items')
        @include('partials.scripts.activities')
        @include('partials.scripts.transactions')
        @include('partials.scripts.partners')
        @include('partials.scripts.finance')
        @include('partials.scripts.charts')

        @include('partials.scripts.modals')

        async function loadDashboardData() {
            loadItems();
            loadActivities();
            
            // Update additional stats
            try {
                const [txRes, supRes, custRes] = await Promise.all([
                    axios.get('/api/transactions'),
                    axios.get('/api/suppliers'),
                    axios.get('/api/customers')
                ]);

                const transactions = txRes.data.data;
                const suppliers = supRes.data.data;
                const customers = custRes.data.data;

                // Update Stock In/Out
                let stockIn = 0;
                let stockOut = 0;
                transactions.forEach(tx => {
                    if (tx.status === 'completed') {
                        if (tx.type === 'in') stockIn += tx.quantity;
                        else stockOut += tx.quantity;
                    }
                });

                document.getElementById('stat-stock-in').textContent = stockIn;
                document.getElementById('stat-stock-out').textContent = stockOut;
                document.getElementById('stat-partners').textContent = (suppliers.length + customers.length);

                // Update Charts with actual data
                if (typeof updateCharts === 'function') {
                    updateCharts(stockIn, stockOut);
                }

            } catch (error) {
                console.error('Failed to load dashboard stats:', error);
            }
        }
        loadDashboardData();
    </script>
@endsection
