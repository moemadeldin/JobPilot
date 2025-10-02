<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait Sluggable
{
    protected static function bootSluggable(): void
    {
        static::creating(function (Model $model): void {
            $model->slug = Str::slug($model->name);
        });

        static::updating(function (Model $model): void {
            if ($model->isDirty('name')) {
                $model->slug = Str::slug($model->name);
            }
        });
    }
}
