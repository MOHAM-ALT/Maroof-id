<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BusinessAccount extends Model
{
    protected $fillable = [
        'company_name',
        'admin_id',
        'employee_count',
        'plan',
        'annual_revenue',
        'website',
        'status',
        'subscription_end_date',
    ];

    protected $casts = [
        'annual_revenue' => 'float',
        'subscription_end_date' => 'datetime',
    ];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function employees(): HasMany
    {
        return $this->hasMany(User::class, 'business_account_id');
    }
}
