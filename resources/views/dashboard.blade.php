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

        function loadDashboardStats() {
            axios.get('/api/dashboard/stats', {
                headers: { 'Authorization': 'Bearer ' + localStorage.getItem('token') }
            })
            .then(response => {
                const data = response.data;
                window.dashboardStats = data;

                // Update Customers
                document.getElementById('stat-customers-total').textContent = new Intl.NumberFormat('en-US').format(data.customers.total);
                document.getElementById('stat-customers-growth').innerHTML = `<i class="fa-solid ${data.customers.growth >= 0 ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down'}"></i> ${Math.abs(data.customers.growth)}%`;
                document.getElementById('stat-customers-growth').className = `text-xs font-semibold flex items-center gap-1 ${data.customers.growth >= 0 ? 'text-green-500' : 'text-red-500'}`;
                document.getElementById('stat-customers-last').textContent = `${data.customers.last_month} users last month`;
                document.getElementById('stat-customers-retail-bar').style.width = `${data.customers.retail_percentage}%`;
                document.getElementById('stat-customers-wholesale-bar').style.width = `${data.customers.wholesale_percentage}%`;
                document.getElementById('stat-customers-wholesale-bar').textContent = `${data.customers.wholesale_percentage}%`;

                // Update Incoming Stock
                document.getElementById('stat-incoming-total').textContent = new Intl.NumberFormat('en-US').format(data.incoming.total) + ' Unit';
                document.getElementById('stat-incoming-growth').innerHTML = `<i class="fa-solid ${data.incoming.growth >= 0 ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down'}"></i> ${Math.abs(data.incoming.growth)}%`;
                document.getElementById('stat-incoming-last').textContent = `${new Intl.NumberFormat('en-US').format(data.incoming.last_month)} units last month`;

                // Update Outgoing Stock
                document.getElementById('stat-outgoing-total').textContent = new Intl.NumberFormat('en-US').format(data.outgoing.total) + ' Unit';
                document.getElementById('stat-outgoing-growth').innerHTML = `<i class="fa-solid ${data.outgoing.growth >= 0 ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down'}"></i> ${Math.abs(data.outgoing.growth)}%`;
                document.getElementById('stat-outgoing-growth').className = `text-xs font-semibold flex items-center gap-1 ${data.outgoing.growth >= 0 ? 'text-green-500' : 'text-red-500'}`;
                document.getElementById('stat-outgoing-last').textContent = `${new Intl.NumberFormat('en-US').format(data.outgoing.last_month)} units last month`;

                // Update Category Sales if function exists
                if (typeof renderCategorySalesSummary === 'function') {
                    renderCategorySalesSummary();
                }
                if (typeof loadCategoriesGrid === 'function') {
                    loadCategoriesGrid();
                }
            })
            .catch(error => {
                console.error('Failed to load dashboard stats:', error);
            });
        }

        function loadDashboardData() {
            loadItems();
            loadActivities();
            loadDashboardStats();
        }
        
        // Setup export button
        document.getElementById('btn-export-stats').addEventListener('click', function() {
            // simple visual feedback
            const btn = this;
            const originalContent = btn.innerHTML;
            btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Exporting...';
            setTimeout(() => {
                btn.innerHTML = originalContent;
                alert('Statistics exported successfully!');
            }, 1000);
        });

        loadDashboardData();
    </script>
@endsection
