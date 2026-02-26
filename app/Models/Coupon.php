<?php

namespace App\Models;

use App\Enums\DiscountType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'discount_type',
        'discount_value',
        'max_uses',
        'used_count',
        'min_order_amount',
        'expiry_date',
        'is_active',
    ];

    protected $casts = [
        'discount_type' => DiscountType::class,
        'discount_value' => 'float',
        'min_order_amount' => 'float',
        'expiry_date' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
