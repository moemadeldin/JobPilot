<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Company extends Model
{
    use SoftDeletes, HasUuids;

    protected $guarded = ['id'];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function vacancies(): HasMany
    {
        return $this->hasMany(JobVacancy::class);
    }

    protected function casts(): array
    {
        return [
            'is_active' => Status::class,
        ];
    }
}
