<?php

namespace Zain\BillForge\Gateways;

use Zain\BillForge\Contracts\PaymentGatewayInterface;

class AuthorizeNetGateway implements PaymentGatewayInterface
{
    protected $credentials = [];

    public function setCredentials(array $credentials)
    {
        $this->credentials = $credentials;
    }

    public function createCheckoutSession($package, $user, $successUrl, $cancelUrl)
    {
        if (empty($this->credentials['secret_key'])) {
            throw new \Exception("Authorize.net credentials not configured.");
        }

        if (empty($package->authorize_plan_id)) {
            throw new \Exception("Authorize Plan ID not configured for package: " . $package->name);
        }

        // Dummy authorize checkout logic
        return $successUrl . '?authorize_token=DUMMY_TOKEN_FOR_TESTING';
    }

    public function cancelSubscription($subscriptionId)
    {
        return true;
    }

    public function verifyWebhook($payload, $signature, $secret)
    {
        return json_decode($payload);
    }
}
