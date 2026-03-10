<?php

namespace Zain\LaravelSubscriptions\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Zain\LaravelSubscriptions\Models\Subscription;
use Zain\LaravelSubscriptions\Models\SubscriptionPackage;
use Zain\LaravelSubscriptions\Models\GatewaySetting;

class DashboardController extends Controller
{
    public function index()
    {
        // Calculate Monthly Recurring Revenue (MRR)
        // This is a basic estimation: summing the price of all active subscriptions.
        // For 'yearly' we divide by 12, for 'lifetime' we might exclude or treat differently.
        $mrr = Subscription::where('status', 'active')
            ->join('subscription_packages', 'subscriptions.package_id', '=', 'subscription_packages.id')
            ->selectRaw('SUM(CASE 
                WHEN billing_cycle = "yearly" THEN price / 12 
                WHEN billing_cycle = "monthly" THEN price 
                ELSE 0 END) as mrr')
            ->value('mrr');

        $stats = [
            'total_packages' => SubscriptionPackage::count(),
            'active_packages' => SubscriptionPackage::where('is_active', true)->count(),
            'total_subscriptions' => Subscription::count(),
            'active_subscriptions' => Subscription::where('status', 'active')->count(),
            'active_gateway' => GatewaySetting::active()->first() ? GatewaySetting::active()->first()->name : 'None',
            'mrr' => $mrr ?? 0.00,
        ];

        return view('subscriptions::admin.dashboard', compact('stats'));
    }
}
