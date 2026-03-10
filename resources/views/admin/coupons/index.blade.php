@extends('subscriptions::layouts.admin')

@section('title', '| Coupons')
@section('page_title', 'Promo Codes & Coupons')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <p class="text-gray-500">Manage discount codes that users can apply at checkout.</p>
        <a href="{{ route('subscriptions.admin.coupons.create') }}"
            class="text-white font-medium py-2 px-4 rounded-xl transition shadow-sm hover:shadow flex items-center"
            style="background-color: var(--sub-primary, #3B82F6);">
            <i class="fa-solid fa-plus mr-2"></i> Create Coupon
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider border-b border-gray-100">
                        <th class="px-6 py-4 font-semibold">Code</th>
                        <th class="px-6 py-4 font-semibold">Discount</th>
                        <th class="px-6 py-4 font-semibold">Usage</th>
                        <th class="px-6 py-4 font-semibold">Expires</th>
                        <th class="px-6 py-4 font-semibold">Status</th>
                        <th class="px-6 py-4 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($coupons as $coupon)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <span
                                    class="font-mono font-bold text-gray-900 bg-gray-100 px-2 py-1 rounded">{{ $coupon->code }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900">
                                    @if ($coupon->type === 'percentage')
                                        {{ number_format($coupon->value, 0) }}% OFF
                                    @else
                                        ${{ number_format($coupon->value, 2) }} OFF
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $coupon->used_count }} / {{ $coupon->max_uses ?? '∞' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $coupon->valid_until ? $coupon->valid_until->format('M d, Y') : 'Never' }}
                            </td>
                            <td class="px-6 py-4">
                                @if ($coupon->isValid())
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Valid
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Expired/Maxed
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <a href="{{ route('subscriptions.admin.coupons.edit', $coupon) }}"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-blue-600 hover:bg-blue-50 transition">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <form action="{{ route('subscriptions.admin.coupons.destroy', $coupon) }}" method="POST"
                                    class="inline-block" onsubmit="return confirm('Delete this coupon FOREVER?');">
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
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-50 mb-4">
                                    <i class="fa-solid fa-ticket text-2xl text-gray-400"></i>
                                </div>
                                <p class="text-lg font-medium text-gray-900 mb-1">No coupons found</p>
                                <p class="mb-4">Create your first promo code to boost sales.</p>
                                <a href="{{ route('subscriptions.admin.coupons.create') }}"
                                    class="inline-flex items-center font-medium opacity-90 hover:opacity-100"
                                    style="color: var(--sub-primary, #3B82F6);">
                                    <i class="fa-solid fa-plus mr-2"></i> Create Coupon
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($coupons->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $coupons->links() }}
            </div>
        @endif
    </div>
@endsection
