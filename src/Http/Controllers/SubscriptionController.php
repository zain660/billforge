<?php

namespace Zain\BillForge\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Zain\BillForge\Contracts\GatewayManagerInterface;
use Zain\BillForge\Models\Subscription;
use Zain\BillForge\Models\SubscriptionPackage;

class SubscriptionController extends Controller
{
    public function pricing()
    {
        $packages = SubscriptionPackage::where('is_active', true)->with('routes')->get();
        // Assuming the user has the HasSubscriptions trait
        $user = auth()->user();
        $activeSubscription = $user ? $user->activeSubscription() : null;

        return view('subscriptions::pricing', compact('packages', 'activeSubscription'));
    }

    public function mySubscription()
    {
        $user = auth()->user();
        $subscription = $user->activeSubscription();
        $allSubscriptions = $user->subscriptions()->latest()->get();

        return view('subscriptions::my-subscription', compact('subscription', 'allSubscriptions'));
    }

    public function checkout(Request $request, SubscriptionPackage $package, GatewayManagerInterface $gatewayManager)
    {
        $user = auth()->user();

        $gateway = $gatewayManager->getActiveGateway();
        if (! $gateway) {
            return back()->with('error', 'No payment gateway is currently active. Please contact support.');
        }

        $coupon = null;
        if ($request->filled('promo_code')) {
            $coupon = \Zain\BillForge\Models\SubscriptionCoupon::where('code', $request->promo_code)->first();
            
            if (!$coupon || !$coupon->isValid()) {
                return back()->with('error', 'Invalid or expired promo code.');
            }
        }

        try {
            $activeGatewayKey = \Zain\BillForge\Models\GatewaySetting::active()->first()->key ?? 'system';
            $successUrl = route('subscriptions.success').'?package_id='.$package->id.'&gateway='.$activeGatewayKey;
            $cancelUrl = route('subscriptions.cancel');

            // Pass the coupon object to the gateway as the 5th parameter
            $checkoutUrl = $gateway->createCheckoutSession($package, $user, $successUrl, $cancelUrl, $coupon);

            return redirect($checkoutUrl);
        } catch (\Exception $e) {
            return back()->with('error', 'Checkout error: '.$e->getMessage());
        }
    }

    public function success(Request $request)
    {
        $user = auth()->user();
        $packageId = $request->get('package_id');

        if ($packageId) {
            // In a real production app, webhooks should handle the actual subscription creation.
            // For this basic flow, we'll provision the subscription upon successful return.

            // Cancel any old active subscriptions
            Subscription::where('user_id', $user->id)
                ->where('status', 'active')
                ->update(['status' => 'cancelled']);

            $package = SubscriptionPackage::find($packageId);
            // dd($packageId, $package);
            $gatewayKey = $request->get('gateway', 'system');
            
            Subscription::create([
                'user_id' => $user->id,
                'package_id' => $package->id,
                'gateway_key' => $gatewayKey,
                'gateway_subscription_id' => $request->get('session_id', 'local_'.uniqid()),
                'status' => 'active',
                'trial_ends_at' => null,
                'ends_at' => $package->billing_cycle === 'yearly' ? now()->addYear() : ($package->billing_cycle === 'monthly' ? now()->addMonth() : null),
            ]);

            return redirect()->route('subscriptions.my')->with('success', 'Subscription successful! Thank you for subscribing to '.$package->name);
        }

        return redirect()->route('subscriptions.my');
    }

    public function cancel()
    {
        return redirect()->route('subscriptions.pricing')->with('error', 'Checkout was cancelled.');
    }

    public function billingPortal(GatewayManagerInterface $gatewayManager)
    {
        $user = auth()->user();

        $gateway = $gatewayManager->getActiveGateway();
        if (!$gateway) {
            return back()->with('error', 'No active payment gateway.');
        }

        try {
            if (method_exists($gateway, 'createBillingPortalSession')) {
                $returnUrl = route('subscriptions.my');
                $portalUrl = $gateway->createBillingPortalSession($user, $returnUrl);
                
                return redirect($portalUrl);
            }

            return back()->with('error', 'Your current payment gateway does not support a self-service billing portal.');
        } catch (\Exception $e) {
            return back()->with('error', 'Unable to access billing portal: ' . $e->getMessage());
        }
    }
}
