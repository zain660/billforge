<?php

namespace Zain\BillForge\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPackage extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'currency',
        'billing_cycle',
        'trial_days',
        'stripe_price_id',
        'paypal_plan_id',
        'authorize_plan_id',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function routes()
    {
        return $this->hasMany(PackageRoute::class , 'package_id');
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class , 'package_id');
    }
}
