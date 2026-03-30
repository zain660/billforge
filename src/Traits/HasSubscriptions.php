<?php

namespace Zain\BillForge\Traits;

use Zain\BillForge\Models\Subscription;

trait HasSubscriptions
{
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class , 'user_id');
    }

    public function activeSubscription()
    {
        return $this->subscriptions()
            ->where(function ($query) {
            $query->where('status', 'active')
                ->orWhere(function ($q) {
                $q->whereNotNull('trial_ends_at')->where('trial_ends_at', '>', now());
            }
            )
                ->orWhere(function ($q) {
                $q->whereNotNull('ends_at')->where('ends_at', '>', now());
            }
            );
        })
            ->latest()
            ->first();
    }

    public function hasSubscriptionPackage($packageId)
    {
        $activeSubscription = $this->activeSubscription();
        if (!$activeSubscription) {
            return false;
        }

        return $activeSubscription->package_id == $packageId;
    }

    public function canAccessRoute($routeName)
    {
        $activeSubscription = $this->activeSubscription();
        if (!$activeSubscription) {
            return false;
        }

        return $activeSubscription->package()->whereHas('routes', function ($query) use ($routeName) {
            $query->where('route_name', $routeName)
                ->orWhere('route_name', '*');
        })->exists();
    }
}
