<?php

namespace App\Models\Concerns;

/**
 * Assign monotonically increasing numeric `id` values for MongoDB documents.
 */
trait HasNumericId
{
    public static function bootHasNumericId(): void
    {
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (int) (static::max('_id') ?? 0) + 1;
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'id';
    }
}
