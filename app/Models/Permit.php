<?php

namespace App\Models;

use Database\Factories\PermitFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Permit extends Model
{
    /** @use HasFactory<PermitFactory> */
    use HasFactory;

    /** @var list<string> */
    protected $fillable = ['eatery_id', 'permit_no', 'expires_on'];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return ['expires_on' => 'date'];
    }

    public function eatery(): BelongsTo
    {
        return $this->belongsTo(Eatery::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_on->isPast();
    }
}
