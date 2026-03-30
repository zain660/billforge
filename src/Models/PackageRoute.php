<?php

namespace Zain\BillForge\Models;

use Illuminate\Database\Eloquent\Model;

class PackageRoute extends Model
{
    protected $fillable = [
        'package_id',
        'feature_name',
        'route_name',
        'route_uri',
    ];

    public function package()
    {
        return $this->belongsTo(SubscriptionPackage::class , 'package_id');
    }
}
