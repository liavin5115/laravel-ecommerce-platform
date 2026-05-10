<?php

namespace App\Models;

use App\Models\Concerns\HasUuidPrimaryKey;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['organization_id', 'event', 'response_status', 'request_payload', 'response_payload'])]
class WebhookLog extends Model
{
    use HasFactory, HasUuidPrimaryKey;

    public $timestamps = false;

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    protected function casts(): array
    {
        return [
            'response_status' => 'integer',
            'request_payload' => 'array',
            'response_payload' => 'array',
            'created_at' => 'datetime',
        ];
    }
}
