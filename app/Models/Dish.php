<?php

namespace App\Models;

use Database\Factories\DishFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dish extends Model
{
    /** @use HasFactory<DishFactory> */
    use HasFactory;

    /** @var list<string> */
    protected $fillable = ['eatery_id', 'name', 'price', 'is_bestseller'];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return ['is_bestseller' => 'boolean', 'price' => 'decimal:2'];
    }

    public function eatery(): BelongsTo
    {
        return $this->belongsTo(Eatery::class);
    }
}
