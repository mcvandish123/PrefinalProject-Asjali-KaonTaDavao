<?php

namespace App\Models;

use Database\Factories\DistrictFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class District extends Model
{
    /** @use HasFactory<DistrictFactory> */
    use HasFactory;

    /** @var list<string> */
    protected $fillable = ['name'];

    public function eateries(): HasMany
    {
        return $this->hasMany(Eatery::class);
    }

    /**
     * Every review written about any eatery in this district.
     */
    public function reviews(): HasManyThrough
    {
        return $this->hasManyThrough(Review::class, Eatery::class);
    }
}
