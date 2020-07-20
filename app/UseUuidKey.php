<?php

declare(strict_types=1);

namespace App;

use Ramsey\Uuid\Uuid;

trait UseUuidKey
{
    /**
     * The "booting" method of the model.
     */
    public static function bootUseUuidKey(): void
    {
        static::creating(static function (self $model): void {
            $model->{$model->getKeyName()} = Uuid::uuid4()->toString();
        });
    }
}
