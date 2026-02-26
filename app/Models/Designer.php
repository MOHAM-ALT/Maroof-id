<?php

namespace App\Models;

use App\Enums\GeneralStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Designer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'portfolio_url',
        'bio',
        'rating',
        'templates_count',
        'earnings',
        'status',
    ];

    protected $casts = [
        'rating' => 'float',
        'earnings' => 'float',
        'status' => GeneralStatus::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function templates(): HasMany
    {
        return $this->hasMany(Template::class, 'designer_id');
    }
}
