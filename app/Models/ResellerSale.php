<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResellerSale extends Model
{
    protected $table = 'reseller_sales';
    
    protected $fillable = [
        'reseller_id',
        'quantity',
        'amount',
        'commission_earned',
        'sale_date',
    ];

    protected $casts = [
        'amount' => 'float',
        'commission_earned' => 'float',
        'sale_date' => 'datetime',
    ];

    public function reseller(): BelongsTo
    {
        return $this->belongsTo(Reseller::class);
    }
}
