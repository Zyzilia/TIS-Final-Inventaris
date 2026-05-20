@extends('layouts.app')

@section('title', 'Overview - PC Parts Dashboard')

@section('content')
    <!-- Authentication Check & Overlay -->
    <div id="authCheck" class="fixed inset-0 bg-white z-50 flex items-center justify-center flex-col gap-4">
        <i class="fa-solid fa-circle-notch fa-spin text-4xl text-accent"></i>
        <p class="text-gray-500 font-medium">Checking authentication...</p>
    </div>

    <div class="glass-panel bg-bgmain relative">
        @include('partials.sidebar')
        
        <!-- Main Content -->
        <main class="flex-1 flex overflow-hidden relative">
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
        @include('partials.scripts.shipping')
        @include('partials.scripts.modals')

        function loadDashboardData() {
            loadItems();
            loadActivities();
        }
        loadDashboardData();
    </script>
@endsection
