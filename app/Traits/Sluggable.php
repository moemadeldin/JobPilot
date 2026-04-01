<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @property string $name
 * @property string $slug
 *
 * @phpstan-require-extends Model
 */
trait Sluggable
{
    protected static function bootSluggable(): void
    {
        static::creating(function (Model $model): void {
            /** @var static $model */
            $model->slug = Str::slug(is_string($model->getAttribute('name')) ? $model->getAttribute('name') : '');
        });

        static::updating(function (Model $model): void {
            /** @var static $model */
            if ($model->isDirty('name')) {
                $model->slug = Str::slug(is_string($model->getAttribute('name')) ? $model->getAttribute('name') : '');
            }
        });
    }
}
