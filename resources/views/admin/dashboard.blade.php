@extends('subscriptions::layouts.admin')

@section('title', '| Dashboard')
@section('page_title', 'Dashboard Overview')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Active Subscriptions Card -->
        <div
            class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col hover:shadow-md transition duration-200">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-gray-500 font-medium text-sm tracking-wide">Active Subscriptions</h3>
                <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-500">
                    <i class="fa-solid fa-users text-lg"></i>
                </div>
            </div>
            <div class="text-3xl font-bold text-gray-900">{{ $stats['active_subscriptions'] }}</div>
            <div class="text-sm text-gray-400 mt-2">Total vs {{ $stats['total_subscriptions'] }} historical</div>
        </div>

        <!-- Active Packages Card -->
        <div
            class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col hover:shadow-md transition duration-200">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-gray-500 font-medium text-sm tracking-wide">Available Packages</h3>
                <div class="w-10 h-10 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-500">
                    <i class="fa-solid fa-box text-lg"></i>
                </div>
            </div>
            <div class="text-3xl font-bold text-gray-900">{{ $stats['active_packages'] }}</div>
            <div class="text-sm text-gray-400 mt-2">Active out of {{ $stats['total_packages'] }} total</div>
        </div>

        <!-- Revenue/Dummy Card -->
        <div
            class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col hover:shadow-md transition duration-200">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-gray-500 font-medium text-sm tracking-wide">MRR (Estimated)</h3>
                <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-500">
                    <i class="fa-solid fa-chart-line text-lg"></i>
                </div>
            </div>
            <div class="text-3xl font-bold text-gray-900">${{ number_format($stats['mrr'], 2) }}</div>
            <div class="text-sm text-gray-400 mt-2">Based on active subscriptions</div>
        </div>

        <!-- Active Gateway -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col hover:shadow-md transition duration-200 relative overflow-hidden group border-b-2"
            style="border-bottom-color: var(--sub-primary, #3B82F6);">
            <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                style="background: linear-gradient(to bottom right, color-mix(in srgb, var(--sub-primary, #3B82F6) 10%, transparent), color-mix(in srgb, var(--sub-btn, #2563EB) 5%, transparent));">
            </div>
            <div class="flex items-center justify-between mb-4 relative z-10">
                <h3 class="text-gray-500 font-medium text-sm tracking-wide">Active Gateway</h3>
                <div class="w-10 h-10 rounded-full flex items-center justify-center"
                    style="background-color: color-mix(in srgb, var(--sub-primary, #3B82F6) 15%, transparent); color: var(--sub-primary, #3B82F6);">
                    <i class="fa-solid fa-satellite-dish text-lg"></i>
                </div>
            </div>
            <div class="text-3xl font-bold text-gray-900">{{ $stats['active_gateway'] }}</div>
            <div class="text-sm mt-2 font-medium"
                style="color: color-mix(in srgb, var(--sub-primary, #3B82F6) 80%, black);">
                Click to configure <i class="fa-solid fa-arrow-right ml-1"></i>
            </div>
            <a href="{{ route('subscriptions.admin.gateways.index') }}" class="absolute inset-0 z-20"></a>
        </div>
    </div>
@endsection
