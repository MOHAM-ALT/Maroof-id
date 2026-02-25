<?php

namespace App\Models;

use App\Enums\GeneralStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reseller extends Model
{
    protected $fillable = [
        'user_id',
        'phone',
        'store_name',
        'city',
        'commission_rate',
        'status',
        'stock_alert_level',
    ];

    protected $casts = [
        'commission_rate' => 'float',
        'status' => GeneralStatus::class,
    ];

    // Belongs to user
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Has many inventory
    public function inventory(): HasMany
    {
        return $this->hasMany(ResellerInventory::class);
    }

    // Has many sales
    public function sales(): HasMany
    {
        return $this->hasMany(ResellerSale::class);
    }
}
