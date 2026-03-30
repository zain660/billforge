<?php

namespace Zain\BillForge\Gateways;

use Zain\BillForge\Contracts\PaymentGatewayInterface;
use Stripe\StripeClient;

class StripeGateway implements PaymentGatewayInterface
{
    protected $credentials = [];
    protected $stripe;

    public function setCredentials(array $credentials)
    {
        $this->credentials = $credentials;
        if (!empty($credentials['secret_key'])) {
            $this->stripe = new StripeClient($credentials['secret_key']);
        }
    }

    public function createCheckoutSession($package, $user, $successUrl, $cancelUrl, $coupon = null)
    {
        if (!$this->stripe) {
            throw new \Exception("Stripe credentials not configured.");
        }

        if (empty($package->stripe_price_id)) {
            throw new \Exception("Stripe Price ID not configured for package: " . $package->name);
        }

        $sessionData = [
            'success_url' => $successUrl . '&session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => $cancelUrl,
            'mode' => 'subscription',
            'line_items' => [[
                'price' => $package->stripe_price_id,
                'quantity' => 1,
            ]],
            'client_reference_id' => $user->id,
            'customer_email' => $user->email ?? null,
        ];

        // Apply coupon if valid
        if ($coupon) {
            if (!empty($coupon->stripe_coupon_id)) {
                // If the admin mapped this to a real Stripe coupon
                $sessionData['discounts'] = [['coupon' => $coupon->stripe_coupon_id]];
            } else {
                // If it's a locally managed discount without a Stripe ID attached
                // NOTE: Stripe recommends creating the coupon via API first. 
                // For a dynamic local coupon, we would need to generate an ephemeral coupon in Stripe.
                $stripeCoupon = $this->stripe->coupons->create([
                    $coupon->type === 'percentage' ? 'percent_off' : 'amount_off' => 
                        $coupon->type === 'percentage' ? $coupon->value : ($coupon->value * 100),
                    'currency' => $coupon->type === 'fixed' ? config('subscriptions.currency', 'USD') : null,
                    'duration' => 'once', // Defaulting to one-time discount for simple local coupons
                    'name' => 'Promo Code: ' . $coupon->code,
                ]);

                $sessionData['discounts'] = [['coupon' => $stripeCoupon->id]];
                
                // Optionally save back to DB so we don't recreate it every time
                $coupon->update(['stripe_coupon_id' => $stripeCoupon->id]);
            }
        }

        // Apply Trial Period
        if (!empty($package->trial_days) && $package->trial_days > 0) {
            $sessionData['subscription_data'] = [
                'trial_period_days' => (int) $package->trial_days,
            ];
        }

        $session = $this->stripe->checkout->sessions->create($sessionData);

        return $session->url;
    }

    public function cancelSubscription($subscriptionId)
    {
        if (!$this->stripe) {
            throw new \Exception("Stripe credentials not configured.");
        }

        return $this->stripe->subscriptions->cancel($subscriptionId, []);
    }

    public function createBillingPortalSession($user, $returnUrl)
    {
        if (!$this->stripe) {
            throw new \Exception("Stripe credentials not configured.");
        }

        // The user must have a Stripe Customer ID. In a real app, you'd store this on the User model.
        // For this package, we'll try to find an active subscription to get the customer ID.
        $subscription = \Zain\BillForge\Models\Subscription::where('user_id', $user->id)
            ->where('gateway_key', 'stripe')
            ->first();

        if (!$subscription || !$subscription->gateway_subscription_id) {
            throw new \Exception("No Stripe subscription found for this user to manage.");
        }

        $stripeSub = $this->stripe->subscriptions->retrieve($subscription->gateway_subscription_id);

        $session = $this->stripe->billingPortal->sessions->create([
            'customer' => $stripeSub->customer,
            'return_url' => $returnUrl,
        ]);

        return $session->url;
    }

    public function verifyWebhook($payload, $signature, $secret)
    {
        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $signature, $secret
            );
            return current($event);
        }
        catch (\UnexpectedValueException $e) {
            return false;
        }
        catch (\Stripe\Exception\SignatureVerificationException $e) {
            return false;
        }
    }
}
