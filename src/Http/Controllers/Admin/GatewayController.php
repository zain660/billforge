<?php

namespace Zain\LaravelSubscriptions\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Zain\LaravelSubscriptions\Models\GatewaySetting;

class GatewayController extends Controller
{
    public function index()
    {
        $gateways = GatewaySetting::all();

        // Ensure defaults exist in DB
        $availableKeys = array_keys(config('subscriptions.gateways', []));
        // dd($availableKeys);
        foreach ($availableKeys as $key => $gateway_name) {
            // dd($gateway_name, $availableKeys[$key]);
            if ($gateway_name != $availableKeys[$key]) {
                GatewaySetting::create([
                    'gateway_key' => $gateway_name,
                    'name' => ucfirst(str_replace('_', ' ', $gateway_name)),
                    'is_active' => false,
                ]);
            }
        }

        return view('subscriptions::admin.gateways.index', compact('gateways'));
    }

    public function activate($key)
    {
        // dd($key);
        GatewaySetting::query()->update(['is_active' => false]);
        $GatewaySetting = GatewaySetting::where('id', $key)->update(['is_active' => true]);
        // dd();
        return redirect()->route('subscriptions.admin.gateways.index')->with('success', ucfirst(GatewaySetting::where('id', $key)->first()->name) . ' gateway activated.');
    }

    public function update(Request $request, $key)
    {
        $setting = GatewaySetting::where('id', $key)->firstOrFail();

        $validated = $request->validate([
            'public_key' => 'nullable|string',
            'secret_key' => 'nullable|string',
            'webhook_secret' => 'nullable|string',
        ]);

        $setting->update($validated);

        return redirect()->route('subscriptions.admin.gateways.index')->with('success', 'Credentials updated successfully.');
    }
}
