<?php

namespace Zain\BillForge\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Zain\BillForge\Models\SubscriptionSetting;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = SubscriptionSetting::getMap();
        return view('subscriptions::admin.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'app_name'          => 'nullable|string|max:100',
            'logo'              => 'nullable|image|max:2048',
            'primary_color'     => 'nullable|string|max:20',
            'secondary_color'   => 'nullable|string|max:20',
            'button_color'      => 'nullable|string|max:20',
            'button_text_color' => 'nullable|string|max:20',
            'header_bg_color'   => 'nullable|string|max:20',
            'card_bg_color'     => 'nullable|string|max:20',
            'text_color'        => 'nullable|string|max:20',
            'badge_color'       => 'nullable|string|max:20',
        ]);

        $textFields = [
            'app_name', 'primary_color', 'secondary_color', 'button_color',
            'button_text_color', 'header_bg_color', 'card_bg_color', 'text_color', 'badge_color',
        ];

        foreach ($textFields as $field) {
            if ($request->has($field)) {
                SubscriptionSetting::set($field, $request->input($field));
            }
        }

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if it exists
            $oldLogo = SubscriptionSetting::get('logo_path');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }

            $path = $request->file('logo')->store('subscription/logos', 'public');
            SubscriptionSetting::set('logo_path', $path);
        }

        return redirect()->back()->with('success', 'Settings saved successfully.');
    }
}
