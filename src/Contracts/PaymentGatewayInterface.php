<?php

namespace Zain\BillForge\Contracts;

interface PaymentGatewayInterface
{
    /**
     * Set the gateway credentials from database/settings
     */
    public function setCredentials(array $credentials);

    /**
     * Create a checkout session/redirect url for a given package and user
     */
    public function createCheckoutSession($package, $user, $successUrl, $cancelUrl, $coupon = null);

    /**
     * Cancel an active subscription
     */
    public function cancelSubscription($subscriptionId);

    /**
     * Create a billing portal session/redirect url for a given user
     */
    public function createBillingPortalSession($user, $returnUrl);

    /**
     * Verify incoming webhook payload
     */
    public function verifyWebhook($payload, $signature, $secret);
}
