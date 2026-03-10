@extends('subscriptions::layouts.admin')

@section('title', '| Packages')
@section('page_title', 'Subscription Packages')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <p class="text-gray-500">Manage your subscription tiers, pricing, and access permissions.</p>
        <a href="{{ route('subscriptions.admin.packages.create') }}"
            class="text-white font-medium py-2 px-4 rounded-xl transition shadow-sm hover:shadow flex items-center"
            style="background-color: var(--sub-primary, #3B82F6);">
            <i class="fa-solid fa-plus mr-2"></i> Create Package
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider border-b border-gray-100">
                        <th class="px-6 py-4 font-semibold">Package details</th>
                        <th class="px-6 py-4 font-semibold">Pricing</th>
                        <th class="px-6 py-4 font-semibold">Protected Routes</th>
                        <th class="px-6 py-4 font-semibold">Status</th>
                        <th class="px-6 py-4 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($packages as $package)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900 text-base mb-1">{{ $package->name }}</div>
                                <div class="text-gray-500 text-sm truncate max-w-xs">
                                    {{ $package->description ?? 'No description' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900">{{ number_format($package->price, 2) }} <span
                                        class="text-sm font-normal text-gray-500 uppercase">{{ $package->currency }}</span>
                                </div>
                                <div class="text-gray-500 text-sm capitalize">{{ $package->billing_cycle }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    {{ $package->routes_count }} Routes
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $package->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $package->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <a href="{{ route('subscriptions.admin.packages.edit', $package) }}"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-blue-600 hover:bg-blue-50 transition">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <form action="{{ route('subscriptions.admin.packages.destroy', $package) }}" method="POST"
                                    class="inline-block"
                                    onsubmit="return confirm('Are you sure you want to delete this package?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-red-600 hover:bg-red-50 transition">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-50 mb-4">
                                    <i class="fa-solid fa-box-open text-2xl text-gray-400"></i>
                                </div>
                                <p class="text-lg font-medium text-gray-900 mb-1">No packages found</p>
                                <p class="mb-4">Get started by creating your first subscription package.</p>
                                <a href="{{ route('subscriptions.admin.packages.create') }}"
                                    class="inline-flex items-center font-medium opacity-90 hover:opacity-100"
                                    style="color: var(--sub-primary, #3B82F6);">
                                    <i class="fa-solid fa-plus mr-2"></i> Create Package
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
