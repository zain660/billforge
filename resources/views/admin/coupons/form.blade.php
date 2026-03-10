@extends('subscriptions::layouts.admin')

@section('title', '| ' . (isset($coupon) ? 'Edit' : 'Create') . ' Coupon')
@section('page_title', (isset($coupon) ? 'Edit' : 'Create') . ' Coupon')

@section('content')
    <div class="max-w-4xl mx-auto">
        <form
            action="{{ isset($coupon) ? route('subscriptions.admin.coupons.update', $coupon) : route('subscriptions.admin.coupons.store') }}"
            method="POST" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            @csrf
            @if (isset($coupon))
                @method('PUT')
            @endif

            <div class="p-8 flex flex-col md:flex-row gap-8">
                <div class="md:w-1/3">
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Coupon Details</h3>
                    <p class="text-sm text-gray-500">Define the discount code your users will type in during checkout.</p>
                </div>

                <div class="md:w-2/3 space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Coupon Code <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="code" value="{{ old('code', $coupon->code ?? '') }}" required
                            class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-lg font-mono uppercase focus:ring-blue-500 focus:border-blue-500 block px-4 py-2.5 outline-none transition shadow-sm"
                            placeholder="e.g. SUMMER2024">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Discount Type <span
                                    class="text-red-500">*</span></label>
                            <select name="type" required
                                class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block px-4 py-2.5 outline-none transition shadow-sm">
                                <option value="percentage"
                                    {{ old('type', $coupon->type ?? 'percentage') == 'percentage' ? 'selected' : '' }}>
                                    Percentage (%)</option>
                                <option value="fixed" {{ old('type', $coupon->type ?? '') == 'fixed' ? 'selected' : '' }}>
                                    Fixed Amount ($)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Discount Value <span
                                    class="text-red-500">*</span></label>
                            <input type="number" step="0.01" name="value"
                                value="{{ old('value', $coupon->value ?? '') }}" required
                                class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block px-4 py-2.5 outline-none transition shadow-sm"
                                placeholder="e.g. 20">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Max Uses (Optional)</label>
                            <input type="number" name="max_uses" value="{{ old('max_uses', $coupon->max_uses ?? '') }}"
                                class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block px-4 py-2.5 outline-none transition shadow-sm"
                                placeholder="e.g. 100">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Expiration Date (Optional)</label>
                            <input type="date" name="valid_until"
                                value="{{ old('valid_until', isset($coupon->valid_until) ? $coupon->valid_until->format('Y-m-d') : '') }}"
                                class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block px-4 py-2.5 outline-none transition shadow-sm">
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-100">
                        <label class="block text-sm font-medium text-gray-700 mb-1"><i
                                class="fa-brands fa-stripe text-[#635BFF] mr-1"></i> Stripe Coupon ID (Optional)</label>
                        <p class="text-xs text-gray-500 mb-2">If you created this coupon in Stripe directly, paste the exact
                            Stripe ID here so checkout matches.</p>
                        <input type="text" name="stripe_coupon_id"
                            value="{{ old('stripe_coupon_id', $coupon->stripe_coupon_id ?? '') }}"
                            class="w-full bg-white border border-gray-200 text-gray-900 rounded-lg focus:ring-[#635BFF] focus:border-[#635BFF] block px-4 py-2.5 outline-none transition shadow-sm"
                            placeholder="e.g. Z4OV52SU">
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 p-6 flex items-center justify-end gap-3">
                <a href="{{ route('subscriptions.admin.coupons.index') }}"
                    class="px-5 py-2.5 text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 font-medium transition shadow-sm">Cancel</a>
                <button type="submit"
                    class="px-5 py-2.5 text-white bg-blue-600 border border-transparent rounded-xl hover:bg-blue-700 font-medium transition shadow-sm shadow-blue-500/30 flex items-center"
                    style="background-color: var(--sub-primary, #3B82F6);">
                    <i class="fa-solid fa-save mr-2"></i> Save Coupon
                </button>
            </div>
        </form>
    </div>
@endsection
