<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;

/**
 * Trait for models that use UUID primary keys
 */
trait HasUuidPrimaryKey
{
    /**
     * Boot the trait
     */
    protected static function bootHasUuidPrimaryKey(): void
    {
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    /**
     * Get the incrementing keys for the model.
     *
     * @return bool
     */
    public function getIncrementing(): bool
    {
        return false;
    }

    /**
     * Get the key type for the model.
     *
     * @return string
     */
    public function getKeyType(): string
    {
        return 'string';
    }
}