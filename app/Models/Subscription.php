<?php

namespace App\Models;

use App\Models\Concerns\HasUuidPrimaryKey;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'organization_id',
    'provider',
    'provider_subscription_id',
    'status',
    'monthly_price',
    'trial_ends_at',
    'renews_at',
])]
class Subscription extends Model
{
    use HasFactory, HasUuidPrimaryKey;

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    protected function casts(): array
    {
        return [
            'monthly_price' => 'decimal:2',
            'trial_ends_at' => 'datetime',
            'renews_at' => 'datetime',
        ];
    }
}
