<?php

namespace Zain\LaravelSubscriptions\Models;

use Illuminate\Database\Eloquent\Model;

class GatewaySetting extends Model
{
    protected $fillable = [
        'key',
        'name',
        'is_active',
        'public_key',
        'secret_key',
        'webhook_secret',
        'additional_settings',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'additional_settings' => 'array',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
