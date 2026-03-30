<?php

namespace Zain\BillForge\Services;

use Zain\BillForge\Contracts\GatewayManagerInterface;
use Zain\BillForge\Models\GatewaySetting;

class GatewayManager implements GatewayManagerInterface
{
    protected $activeGatewayKey = null;

    public function getActiveGateway()
    {
        if ($this->activeGatewayKey) {
            return $this->driver($this->activeGatewayKey);
        }

        $activeSetting = GatewaySetting::active()->first();
        if ($activeSetting) {
            $this->activeGatewayKey = $activeSetting->key;

            return $this->driver($activeSetting->key);
        }

        return null; // No active gateway configured
    }

    public function setActiveGateway(string $key)
    {
        $this->activeGatewayKey = $key;
    }

    public function driver(?string $key = null)
    {
        $key = $key ?: $this->activeGatewayKey;

        $gateways = config('subscriptions.gateways', []);
        // dd($gateways);
        if (! isset($gateways['stripe']) || ! isset($gateways['paypal']) || ! isset($gateways['authorize_net'])) {
            throw new \Exception("Gateway [{$key}] not supported.");
        }
        $activeGateways = GatewaySetting::where('is_active', 1)->get();
        foreach ($activeGateways as $key) {

            // dd();
            $class = $gateways[strtolower($key->name)]['class'];
            $gateway = new $class;

            // Load credentials from DB
            $settings = GatewaySetting::where('id', $key->id)->first();
            if ($settings) {
                $gateway->setCredentials([
                    'public_key' => $settings->public_key,
                    'secret_key' => $settings->secret_key,
                    'webhook_secret' => $settings->webhook_secret,
                    'additional_settings' => $settings->additional_settings,
                ]);
            }
        }

        return $gateway;
    }
}
