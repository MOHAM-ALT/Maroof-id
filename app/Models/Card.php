<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Hash;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Card extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'user_id',
        'template_id',
        'slug',
        'title',
        'bio',
        'full_name',
        'job_title',
        'company',
        'email',
        'phone',
        'whatsapp',
        'website',
        'address',
        'profile_image',
        'cover_image',
        'logo',
        'nfc_id',
        'qr_code',
        'design_settings',
        'custom_fields',
        'is_active',
        'is_public',
        'views_count',
        'last_viewed_at',
        'meta_title',
        'meta_description',
        'design_data',
        'password',
        'expires_at',
    ];

    protected $casts = [
        'design_settings' => 'array',
        'custom_fields' => 'array',
        'design_data' => 'array',
        'is_active' => 'boolean',
        'is_public' => 'boolean',
        'views_count' => 'integer',
        'last_viewed_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    protected $hidden = [
        'password',
    ];

    /**
     * Get the owner
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the template
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }

    /**
     * Get social links
     */
    public function socialLinks(): HasMany
    {
        return $this->hasMany(CardSocialLink::class)->orderBy('sort_order');
    }

    /**
     * Get active social links
     */
    public function activeSocialLinks(): HasMany
    {
        return $this->socialLinks()->where('is_active', true);
    }

    /**
     * Get views
     */
    public function views(): HasMany
    {
        return $this->hasMany(CardView::class);
    }

    /**
     * Get orders
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Increment views count
     */
    public function recordView(array $data = []): void
    {
        $this->increment('views_count');
        $this->update(['last_viewed_at' => now()]);
        
        $this->views()->create($data);
    }

    /**
     * Get public URL
     */
    public function getUrlAttribute(): string
    {
        return url($this->slug);
    }

    /**
     * Check if card is viewable
     */
    public function isViewable(): bool
    {
        return $this->is_active && $this->is_public && !$this->isExpired();
    }

    public function isPasswordProtected(): bool
    {
        return !empty($this->password);
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function checkPassword(string $password): bool
    {
        return Hash::check($password, $this->password);
    }

    /**
     * Register media collections
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('profile_image')
            ->singleFile()
            ->useFallbackUrl('/images/default-profile.png');

        $this->addMediaCollection('cover_image')
            ->singleFile();

        $this->addMediaCollection('logo')
            ->singleFile();
    }
}
