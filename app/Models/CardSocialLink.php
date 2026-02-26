<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CardSocialLink extends Model
{
    protected $fillable = [
        'card_id',
        'platform',
        'url',
        'label',
        'icon',
        'sort_order',
        'is_active',
        'clicks_count',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_active' => 'boolean',
        'clicks_count' => 'integer',
    ];

    /**
     * Get the card
     */
    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class);
    }

    /**
     * Increment clicks count
     */
    public function recordClick(): void
    {
        $this->increment('clicks_count');
    }

    /**
     * Get platform icon
     */
    public function getPlatformIconAttribute(): string
    {
        $icons = [
            'facebook' => 'fab fa-facebook',
            'twitter' => 'fab fa-twitter',
            'linkedin' => 'fab fa-linkedin',
            'instagram' => 'fab fa-instagram',
            'youtube' => 'fab fa-youtube',
            'tiktok' => 'fab fa-tiktok',
            'snapchat' => 'fab fa-snapchat',
            'whatsapp' => 'fab fa-whatsapp',
            'telegram' => 'fab fa-telegram',
            'github' => 'fab fa-github',
        ];

        return $this->icon ?? $icons[$this->platform] ?? 'fas fa-link';
    }
}
