<?php

namespace App\Models;

use App\Models\Concerns\HasUuidPrimaryKey;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'customer_id',
    'label',
    'recipient_name',
    'phone',
    'address_line',
    'city',
    'province',
    'postal_code',
    'country',
    'is_default',
])]
class Address extends Model
{
    use HasFactory, HasUuidPrimaryKey;

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    protected function casts(): array
    {
        return [
            'is_default' => 'boolean',
        ];
    }
}
