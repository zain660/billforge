<?php

namespace Zain\BillForge\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Zain\BillForge\Models\SubscriptionCoupon;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $coupons = SubscriptionCoupon::query()
            ->when($search, function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(15);

        return view('subscriptions::admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('subscriptions::admin.coupons.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:subscription_coupons,code',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0.01',
            'max_uses' => 'nullable|integer|min:1',
            'valid_until' => 'nullable|date',
            'stripe_coupon_id' => 'nullable|string', // Admin might create this in Stripe directly 
        ]);

        SubscriptionCoupon::create($validated);

        return redirect()->route('subscriptions.admin.coupons.index')
            ->with('success', 'Coupon created successfully.');
    }

    public function edit(SubscriptionCoupon $coupon)
    {
        return view('subscriptions::admin.coupons.form', compact('coupon'));
    }

    public function update(Request $request, SubscriptionCoupon $coupon)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:subscription_coupons,code,'.$coupon->id,
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0.01',
            'max_uses' => 'nullable|integer|min:1',
            'valid_until' => 'nullable|date',
            'stripe_coupon_id' => 'nullable|string',
        ]);

        $coupon->update($validated);

        return redirect()->route('subscriptions.admin.coupons.index')
            ->with('success', 'Coupon updated successfully.');
    }

    public function destroy(SubscriptionCoupon $coupon)
    {
        // Typically you might not want to delete them if they have been used historically for reporting.
        // But for simplicity:
        $coupon->delete();

        return redirect()->route('subscriptions.admin.coupons.index')
            ->with('success', 'Coupon deleted.');
    }
}
