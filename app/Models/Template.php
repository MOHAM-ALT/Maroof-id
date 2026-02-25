<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Template extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'template_category_id',
        'name_ar',
        'name_en',
        'slug',
        'description_ar',
        'description_en',
        'preview_image',
        'design_config',
        'html_content',
        'customizable_fields',
        'price',
        'is_premium',
        'is_active',
        'is_featured',
        'usage_count',
        'sort_order',
    ];

    protected $casts = [
        'design_config' => 'array',
        'customizable_fields' => 'array',
        'price' => 'decimal:2',
        'is_premium' => 'boolean',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'usage_count' => 'integer',
        'sort_order' => 'integer',
    ];

    /**
     * Scope to get only active templates
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get only featured templates
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Get the category
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(TemplateCategory::class, 'template_category_id');
    }

    /**
     * Get cards using this template
     */
    public function cards(): HasMany
    {
        return $this->hasMany(Card::class);
    }

    public function designer(): BelongsTo
    {
        return $this->belongsTo(Designer::class);
    }

    /**
     * Get name based on locale
     */
    public function getNameAttribute(): string
    {
        return app()->getLocale() === 'ar' ? $this->name_ar : $this->name_en;
    }

    /**
     * Get description based on locale
     */
    public function getDescriptionAttribute(): ?string
    {
        return app()->getLocale() === 'ar' ? $this->description_ar : $this->description_en;
    }

    /**
     * Increment usage count
     */
    public function incrementUsage(): void
    {
        $this->increment('usage_count');
    }

    /**
     * Check if template is free
     */
    public function isFree(): bool
    {
        return !$this->is_premium || $this->price == 0;
    }
}
