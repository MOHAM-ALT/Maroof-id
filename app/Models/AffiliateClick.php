<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AffiliateClick extends Model
{
    protected $table = 'affiliate_clicks';
    
    protected $fillable = [
        'affiliate_id',
        'source_url',
        'visitor_ip',
        'visitor_country',
        'converted',
        'conversion_id',
        'clicked_at',
    ];

    protected $casts = [
        'converted' => 'boolean',
        'clicked_at' => 'datetime',
    ];

    public function affiliate(): BelongsTo
    {
        return $this->belongsTo(Affiliate::class);
    }
}
