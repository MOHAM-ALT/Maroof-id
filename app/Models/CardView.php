<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CardView extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'card_id',
        'ip_address',
        'user_agent',
        'device_type',
        'browser',
        'platform',
        'country',
        'city',
        'referrer',
        'viewed_at',
    ];

    protected $casts = [
        'viewed_at' => 'datetime',
    ];

    /**
     * Get the card
     */
    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class);
    }
}
