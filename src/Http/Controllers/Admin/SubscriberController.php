<?php

namespace Zain\BillForge\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Zain\BillForge\Models\Subscription;

class SubscriberController extends Controller
{
    public function index(Request $request)
    {
        $query = Subscription::with(['user', 'package'])
            ->orderByDesc('created_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $subscribers = $query->paginate(20)->withQueryString();

        return view('subscriptions::admin.subscribers.index', compact('subscribers'));
    }

    public function cancel($id)
    {
        $subscription = Subscription::findOrFail($id);
        $subscription->update(['status' => 'cancelled']);

        return redirect()->back()->with('success', 'Subscription cancelled successfully.');
    }

    public function activate($id)
    {
        $subscription = Subscription::findOrFail($id);
        $subscription->update(['status' => 'active', 'is_blocked' => false]);

        return redirect()->back()->with('success', 'Subscription activated successfully.');
    }

    public function block($id)
    {
        $subscription = Subscription::findOrFail($id);
        $subscription->update(['is_blocked' => true, 'status' => 'blocked']);

        return redirect()->back()->with('success', 'User has been blocked from subscribing.');
    }
}
