<?php

namespace Zain\LaravelSubscriptions\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionCoupon extends Model
{
    protected $fillable = [
        'code',
        'type',
        'value',
        'max_uses',
        'used_count',
        'valid_until',
        'stripe_coupon_id',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'valid_until' => 'datetime',
    ];

    /**
     * Check if the coupon is currently valid
     * @return bool
     */
    public function isValid()
    {
        // Check expiration
        if ($this->valid_until && $this->valid_until->isPast()) {
            return false;
        }

        // Check usage limits
        if (!is_null($this->max_uses) && $this->used_count >= $this->max_uses) {
            return false;
        }

        return true;
    }

    /**
     * Apply the discount to a given price
     * @param float $price
     * @return float
     */
    public function calculateDiscount($price)
    {
        if (!$this->isValid()) {
            return 0;
        }

        if ($this->type === 'percentage') {
            return $price * ($this->value / 100);
        }

        if ($this->type === 'fixed') {
            return min($price, $this->value); // Discount can't be more than price
        }

        return 0;
    }

    /**
     * Increment the usage count
     */
    public function incrementUsage()
    {
        $this->increment('used_count');
    }
}
