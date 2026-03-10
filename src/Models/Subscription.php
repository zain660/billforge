<?php

namespace Zain\LaravelSubscriptions\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'user_id',
        'package_id',
        'gateway_key',
        'gateway_subscription_id',
        'status',
        'trial_ends_at',
        'ends_at',
        'is_blocked',
    ];

    protected $casts = [
        'trial_ends_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_blocked' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(config('auth.providers.users.model', 'App\\Models\\User'));
    }

    public function package()
    {
        return $this->belongsTo(SubscriptionPackage::class , 'package_id');
    }

    public function isActive()
    {
        return $this->status === 'active' || $this->onTrial() || $this->onGracePeriod();
    }

    public function onTrial()
    {
        return $this->trial_ends_at && $this->trial_ends_at->isFuture();
    }

    public function onGracePeriod()
    {
        return $this->ends_at && $this->ends_at->isFuture();
    }
}
