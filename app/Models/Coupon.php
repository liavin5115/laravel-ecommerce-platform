<?php

namespace App\Models;

use App\Models\Concerns\HasUuidPrimaryKey;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'organization_id',
    'code',
    'discount_type',
    'discount_value',
    'minimum_order',
    'usage_limit',
    'used_count',
    'expires_at',
])]
class Coupon extends Model
{
    use HasFactory, HasUuidPrimaryKey;

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'order_coupon');
    }

    protected function casts(): array
    {
        return [
            'discount_value' => 'decimal:2',
            'minimum_order' => 'decimal:2',
            'usage_limit' => 'integer',
            'used_count' => 'integer',
            'expires_at' => 'datetime',
        ];
    }
}
