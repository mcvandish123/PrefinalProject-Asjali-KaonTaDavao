<?php

namespace App\Models;

use Database\Factories\EateryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Eatery extends Model
{
    /** @use HasFactory<EateryFactory> */
    use HasFactory;

    /** @var list<string> */
    protected $fillable = [
        'district_id', 'name', 'slug', 'address', 'price_range', 'specialty', 'is_open',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return ['is_open' => 'boolean'];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function permit(): HasOne
    {
        return $this->hasOne(Permit::class);
    }

    public function dishes(): HasMany
    {
        return $this->hasMany(Dish::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function features(): BelongsToMany
    {
        return $this->belongsToMany(Feature::class, 'eatery_feature');
    }
}
