<?php

namespace App\Models;

use App\Enums\GeneralStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Partner extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'city',
        'commission_rate',
        'status',
        'account_holder',
        'iban',
        'bank_name',
    ];

    protected $casts = [
        'commission_rate' => 'float',
        'status' => GeneralStatus::class,
    ];

    // Has many orders
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
